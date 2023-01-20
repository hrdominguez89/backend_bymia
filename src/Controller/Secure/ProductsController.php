<?php

namespace App\Controller\Secure;

use App\Constants\Constants;
use App\Entity\Product;
use App\Entity\ProductImages;
use App\Form\ProductType;
use App\Helpers\SendProductTo3pl;
use App\Repository\BrandRepository;
use App\Repository\CommunicationStatesBetweenPlatformsRepository;
use App\Repository\ProductImagesRepository;
use App\Repository\ProductRepository;
use App\Repository\ProductSubcategoryRepository;
use App\Repository\ProductTagRepository;
use App\Repository\SpecificationRepository;
use App\Repository\SubcategoryRepository;
use App\Repository\TagRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\FileUploader;
use Aws\S3\S3Client;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * @Route("/product")
 */
class ProductsController extends AbstractController
{

    private $pathImg = 'products';

    /**
     * ProductController constructor.
     * @param ProductRepository $productRepository
     */
    public function __construct(
        ProductRepository $productRepository,
        ParameterBagInterface $parameterBag,
        BrandRepository $brandRepository,
        SpecificationRepository $specificacionRepository,
        ProductTagRepository $productTagRepository,
        TagRepository $tagRepository,
        SubcategoryRepository $subcategoryRepository,
        ProductSubcategoryRepository $productSubcategoryRepository,
        ProductImagesRepository $productImagesRepository
    ) {
        $this->productRepository = $productRepository;
        $this->brandRepository = $brandRepository;
        $this->tagRepository = $tagRepository;
        $this->subcategoryRepository = $subcategoryRepository;
        $this->specificacionRepository = $specificacionRepository;
        $this->productSubcategoryRepository = $productSubcategoryRepository;
        $this->productTagRepository = $productTagRepository;
        $this->parameterBag = $parameterBag;
        $this->productImagesRepository = $productImagesRepository;
    }

    /**
     * @Route("/", name="secure_crud_product_index", methods={"GET"})
     */
    public function index(ProductRepository $productRepository, Request $request, PaginatorInterface $pagination): Response
    {
        $data['products'] = $productRepository->findAll();
        $data['title'] = 'Productos';
        $data['files_js'] = array('table_full_buttons.js?v=' . rand());
        $data['breadcrumbs'] = array(
            array('active' => true, 'title' => $data['title'])
        );

        return $this->render('secure/products/abm_products.html.twig', $data);
    }

    /**
     * @Route("/new", name="secure_crud_product_new", methods={"GET","POST"})
     */
    public function new(Request $request, SluggerInterface $slugger, SubcategoryRepository $subcategoryRepository, CommunicationStatesBetweenPlatformsRepository $communicationStatesBetweenPlatformsRepository, SendProductTo3pl $sendProductTo3pl): Response
    {
        $data['title'] = 'Nuevo producto';
        $data['breadcrumbs'] = array(
            array('path' => 'secure_crud_product_index', 'title' => 'Productos'),
            array('active' => true, 'title' => $data['title'])
        );
        $data['files_js'] = array('../uppy.min.js', 'product/upload_files.js?v=' . rand(), 'product/product.js?v=' . rand());
        $data['files_css'] = array('uppy.min.css');
        $data['product'] = new Product;
        $form = $this->createForm(ProductType::class, $data['product']);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data['product']->setStatusSent3pl($communicationStatesBetweenPlatformsRepository->find(Constants::CBP_STATUS_PENDING));
            $data['product']->setSubcategory($subcategoryRepository->findOneBy(['id' => $request->get('product')['subcategory']]));

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($data['product']);


            $productNameSlug = $slugger->slug($form->get('name')->getData());
            $imagesFilesBase64 = $form->get('images')->getData();
            $imagesFiles = explode('*,*', $imagesFilesBase64);

            if ($imagesFiles[0]) {
                try {
                    $s3 = new S3Client([
                        'region' => $_ENV['AWS_S3_BUCKET_REGION'],
                        'version' => 'latest',
                        'credentials' => [
                            'key' => $_ENV['AWS_S3_ACCESS_ID'],
                            'secret' => $_ENV['AWS_S3_ACCESS_SECRET'],
                        ],
                    ]);
                    $indexImage = 0;
                    foreach ($imagesFiles as $imageFile) {

                        $images = new ProductImages;
                        if ($indexImage == 0) {
                            $images->setPrincipal(true);
                            $indexImage++;
                        }

                        $file = base64_decode(explode(',', $imageFile)[1]);
                        $path = $this->pathImg . '/' . $productNameSlug . '-' . uniqid() . '.jpg';
                        // Upload the image to the S3 bucket
                        $s3->putObject([
                            'Bucket' => $_ENV['AWS_S3_BUCKET_NAME'],
                            'Key' => $path,
                            'Body' => $file,
                            'ACL' => 'public-read',
                        ]);
                        $images->setImage($_ENV['AWS_S3_URL'] . '/' . $path);
                        $images->setProduct($data['product']);
                        $entityManager->persist($images);
                    }
                } catch (\Exception $e) {
                    //ver como manejar este error
                }
            }
            $entityManager->flush();
            $sendProductTo3pl->send($data['product']);

            return $this->redirectToRoute('secure_crud_product_index');
        }

        $data['form'] = $form;
        return $this->renderForm('secure/crud_product/form_products.html.twig', $data);
    }

    /**
     * @Route("/{id}/edit", name="secure_crud_product_edit", methods={"GET","POST"})
     */
    public function edit($id, Request $request, SluggerInterface $slugger, ProductRepository $productRepository, SubcategoryRepository $subcategoryRepository, CommunicationStatesBetweenPlatformsRepository $communicationStatesBetweenPlatformsRepository, SendProductTo3pl $sendProductTo3pl): Response
    {
        $data['title'] = 'Editar producto';
        $data['breadcrumbs'] = array(
            array('path' => 'secure_crud_product_index', 'title' => 'Productos'),
            array('active' => true, 'title' => $data['title'])
        );
        $data['files_js'] = array('../uppy.min.js', '../pgwslideshow.min.js', 'product/upload_files.js?v=' . rand(), 'product/product.js?v=' . rand());
        $data['files_css'] = array('uppy.min.css', 'pgwslideshow.min.css');
        $data['product'] = $productRepository->find($id);
        $form = $this->createForm(ProductType::class, $data['product']);


        $skuArray = explode('-', $data['product']->getSku());
        $form->get('vp1')->setData($skuArray[4]);
        $form->get('vp2')->setData(@$skuArray[5] ? $skuArray[5] : '');
        $form->get('vp3')->setData(@$skuArray[6] ? $skuArray[6] : '');


        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();

            if ($form->get('subcategory')->getData()) {
                $data['product']->setSubcategory($subcategoryRepository->find($form->get('subcategory')->getData()));
            }

            $entityManager->persist($data['product']);


            $productNameSlug = $slugger->slug($form->get('name')->getData());
            $imagesFilesBase64 = $form->get('images')->getData();
            $imagesFiles = explode('*,*', $imagesFilesBase64);

            if ($imagesFiles[0]) {
                try {
                    $s3 = new S3Client([
                        'region' => $_ENV['AWS_S3_BUCKET_REGION'],
                        'version' => 'latest',
                        'credentials' => [
                            'key' => $_ENV['AWS_S3_ACCESS_ID'],
                            'secret' => $_ENV['AWS_S3_ACCESS_SECRET'],
                        ],
                    ]);
                    $indexImage = !$data['product']->getImage()[0] ? 0 : 1;
                    foreach ($imagesFiles as $imageFile) {
                        $images = new ProductImages;
                        if ($indexImage == 0) {
                            $images->setPrincipal(true);
                        }
                        $indexImage++;
                        $file = base64_decode(explode(',', $imageFile)[1]);
                        $path = $this->pathImg . '/' . $productNameSlug . '-' . uniqid() . '.jpg';
                        // Upload the image to the S3 bucket
                        $s3->putObject([
                            'Bucket' => $_ENV['AWS_S3_BUCKET_NAME'],
                            'Key' => $path,
                            'Body' => $file,
                            'ACL' => 'public-read',
                        ]);
                        $images->setImage($_ENV['AWS_S3_URL'] . '/' . $path);
                        $images->setProduct($data['product']);
                        $entityManager->persist($images);
                    }
                } catch (\Exception $e) {
                    //ver como manejar este error
                }
            }

            $data['product']->setStatusSent3pl($communicationStatesBetweenPlatformsRepository->find(Constants::CBP_STATUS_PENDING));
            $data['product']->setAttemptsSend3pl(0);
            $entityManager->flush();
            $sendProductTo3pl->send($data['product'], 'PUT', 'update');

            return $this->redirectToRoute('secure_crud_product_index');
        }

        $data['form'] = $form;
        return $this->renderForm('secure/crud_product/form_products.html.twig', $data);
    }

    /**
     * @Route("/consultFreeSku/{sku}", name="secure_consult_free_sku", methods={"GET"})
     */
    public function consultFreeSku($sku, ProductRepository $productRepository, Request $request): Response
    {
        $data['data'] = $productRepository->findFreeSku($sku, $request->get('product_id'));
        if (!$data['data']) {
            $data['status'] = true;
        } else {
            $data['status'] = false;
            $data['message'] = 'El SKU ya se encuentra registrado para ver el producto haga <a target="_blank" href="/secure/product/' . $data['data']['id'] . '/edit">click aquí</a>';
        }
        return new JsonResponse($data);
    }

    /**
     * @Route("/deleteImageProduct", name="secure_delete_image_product", methods={"POST"})
     */
    public function deleteImageProduct(ProductImagesRepository $productImagesRepository, Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();
        $image_id = $request->get('image_id');
        $principal_image = (bool)$request->get('principal_image');
        $imageObject = $productImagesRepository->find($image_id);
        if ($imageObject) {
            if ($principal_image) {
                $next_image_not_principal = $productImagesRepository->getImageNotPrincipal($imageObject->getProduct()->getId());
                if ($next_image_not_principal) {
                    $next_image_principal = $productImagesRepository->find($next_image_not_principal[0]['id']);
                    $next_image_principal->setPrincipal(true);
                    $em->persist($next_image_principal);
                }
            }

            $path = explode($_ENV['AWS_S3_URL'] . '/', $imageObject->getImage())[1];

            $em->remove($imageObject);
            $em->flush();


            $s3 = new S3Client([
                'region' => $_ENV['AWS_S3_BUCKET_REGION'],
                'version' => 'latest',
                'credentials' => [
                    'key' => $_ENV['AWS_S3_ACCESS_ID'],
                    'secret' => $_ENV['AWS_S3_ACCESS_SECRET'],
                ],
            ]);

            $s3->deleteObject([
                'Bucket' => $_ENV['AWS_S3_BUCKET_NAME'],
                'Key'    => $path
            ]);

            $data['status'] = true;
            $data['new_principal_image_id'] = $principal_image ? ($next_image_not_principal ? $next_image_not_principal[0]['id'] : false) : false;
        } else {
            $data['status'] = false;
            $data['message'] = 'No se encontro la imagen que se desea eliminar';
        }
        return new JsonResponse($data);
    }

    /**
     * @Route("/newPrincipalImage", name="secure_new_principal_image_product", methods={"POST"})
     */
    public function newPrincipalImage(ProductImagesRepository $productImagesRepository, Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();

        $old_principal_image_id = $request->get('old_principal_image_id');
        $new_principal_image_id = $request->get('new_principal_image_id');

        $imageObjectOldPrincipal = $productImagesRepository->find($old_principal_image_id);
        $imageObjectNewPrincipal = $productImagesRepository->find($new_principal_image_id);

        if ($imageObjectOldPrincipal && $imageObjectNewPrincipal) {

            $imageObjectOldPrincipal->setPrincipal(false);
            $imageObjectNewPrincipal->setPrincipal(true);

            $em->persist($imageObjectOldPrincipal);
            $em->persist($imageObjectNewPrincipal);
            $em->flush();

            $data['status'] = true;
        } else {
            $data['status'] = false;
            $data['message'] = 'No se encontro la imagen.';
        }
        return new JsonResponse($data);
    }
}
