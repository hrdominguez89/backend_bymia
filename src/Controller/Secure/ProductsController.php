<?php

namespace App\Controller\Secure;

use App\Constants\Constants;
use App\Entity\HistoricalPriceCost;
use App\Entity\Product;
use App\Entity\ProductDiscount;
use App\Entity\ProductImages;
use App\Form\ProductDiscountType;
use App\Form\ProductTagType;
use App\Form\ProductType;
use App\Helpers\SendProductTo3pl;
use App\Repository\BrandRepository;
use App\Repository\CommunicationStatesBetweenPlatformsRepository;
use App\Repository\ProductDiscountRepository;
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
use Intervention\Image\ImageManager;
use Aws\S3\S3Client;
use Doctrine\ORM\EntityManagerInterface;
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
        ProductImagesRepository $productImagesRepository,
        ImageManager $imageManager
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
        $this->imageManager = $imageManager;
    }

    /**
     * @Route("/", name="secure_product_index", methods={"GET"})
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
     * @Route("/new", name="secure_product_new", methods={"GET","POST"})
     */
    public function new(EntityManagerInterface $em, Request $request, SluggerInterface $slugger, SubcategoryRepository $subcategoryRepository, CommunicationStatesBetweenPlatformsRepository $communicationStatesBetweenPlatformsRepository, SendProductTo3pl $sendProductTo3pl): Response
    {
        $data['title'] = 'Nuevo producto';
        $data['breadcrumbs'] = array(
            array('path' => 'secure_product_index', 'title' => 'Productos'),
            array('active' => true, 'title' => $data['title'])
        );
        $data['files_js'] = array('../uppy.min.js', '../select2.min.js', 'product/upload_files.js?v=' . rand(), 'product/product.js?v=' . rand());
        $data['files_css'] = array('uppy.min.css', 'select2.min.css', 'select2-bootstrap4.min.css');
        $data['product'] = new Product;
        $form = $this->createForm(ProductType::class, $data['product']);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $data['product']->setStatusSent3pl($communicationStatesBetweenPlatformsRepository->find(Constants::CBP_STATUS_PENDING));
            $data['product']->setSubcategory($subcategoryRepository->findOneBy(['id' => (int)$request->get('product')['subcategory']]));

            $entityManager = $em;
            $entityManager->persist($data['product']);


            $productNameSlug = $slugger->slug($form->get('name')->getData());
            $imagesFilesBase64 = $form->get('images')->getData();
            $imagesFiles = explode('*,*', $imagesFilesBase64);

            $historicalPriceCost = new HistoricalPriceCost;
            $historicalPriceCost->setProduct($data['product']);
            $historicalPriceCost->setPrice($form->get('price')->getData());
            $historicalPriceCost->setCost($form->get('cost')->getData());
            $historicalPriceCost->setCreatedByUser($this->getUser());
            $entityManager->persist($historicalPriceCost);

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
                        $tmpImage = $this->imageManager->make($file);
                        $tmpImagePath = sys_get_temp_dir() . '/' . uniqid() . '.jpg';
                        $tmpImage->save($tmpImagePath);

                        $originalImagePath = $_ENV['APP_ENV'] === 'dev' ? 'testing/' . $this->pathImg . '/' . $productNameSlug . '-' . uniqid() . '.jpg' : $this->pathImg . '/' . $productNameSlug . '-' . uniqid() . '.jpg';
                        $scaledImagePath = $_ENV['APP_ENV'] === 'dev' ? 'testing/' . $this->pathImg . '/thumbnails' . '/' . $productNameSlug . '-' . uniqid() . '.jpg' : $this->pathImg . '/' . $productNameSlug . '-' . uniqid() . '.jpg';

                        // Subir la imagen original al S3
                        $s3->putObject([
                            'Bucket' => $_ENV['AWS_S3_BUCKET_NAME'],
                            'Key' => $originalImagePath,
                            'Body' => file_get_contents($tmpImagePath),
                            'ACL' => 'public-read',
                        ]);

                        // Escalar la imagen proporcionalmente
                        $scaledImage = $tmpImage->resize(200, null, function ($constraint) {
                            $constraint->aspectRatio();
                            $constraint->upsize();
                        });

                        // Subir la imagen escalada al S3
                        $s3->putObject([
                            'Bucket' => $_ENV['AWS_S3_BUCKET_NAME'],
                            'Key' => $scaledImagePath,
                            'Body' => $scaledImage->encode('jpg'),
                            'ACL' => 'public-read',
                        ]);

                        // Eliminar el archivo temporal
                        unlink($tmpImagePath);

                        $images->setImage($_ENV['AWS_S3_URL'] . '/' . $originalImagePath);
                        // Guardar la ruta de la imagen escalada en la entidad de imágenes si es necesario
                        $images->setImgThumbnail($_ENV['AWS_S3_URL'] . '/' . $scaledImagePath);
                        $images->setProduct($data['product']);
                        $entityManager->persist($images);
                    }
                } catch (\Exception $e) {
                    // Manejar el error
                }
            }
            $entityManager->flush();
            $entityManager->refresh($data['product']);
            $sendProductTo3pl->send($data['product']);

            return $this->redirectToRoute('secure_product_index');
        }

        $data['form'] = $form;
        return $this->renderForm('secure/products/form_products.html.twig', $data);
    }

    /**
     * @Route("/{id}/edit", name="secure_product_edit", methods={"GET","POST"})
     */
    public function edit(EntityManagerInterface $em, $id, Request $request, SluggerInterface $slugger, ProductRepository $productRepository, SubcategoryRepository $subcategoryRepository, CommunicationStatesBetweenPlatformsRepository $communicationStatesBetweenPlatformsRepository, SendProductTo3pl $sendProductTo3pl): Response
    {
        $data['title'] = 'Editar producto';
        $data['breadcrumbs'] = array(
            array('path' => 'secure_product_index', 'title' => 'Productos'),
            array('active' => true, 'title' => $data['title'])
        );
        $data['files_js'] = array('../uppy.min.js', '../pgwslideshow.min.js', '../select2.min.js', 'product/upload_files.js?v=' . rand(), 'product/product.js?v=' . rand());
        $data['files_css'] = array('uppy.min.css', 'pgwslideshow.min.css', 'select2.min.css', 'select2-bootstrap4.min.css');
        $data['product'] = $productRepository->find($id);
        $form = $this->createForm(ProductType::class, $data['product']);


        $skuArray = explode('-', $data['product']->getSku());
        $form->get('vp1')->setData($skuArray[4]);
        $form->get('vp2')->setData(@$skuArray[5] ? $skuArray[5] : '');
        $form->get('vp3')->setData(@$skuArray[6] ? $skuArray[6] : '');


        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $em;
            $data['product']->setSubcategory($subcategoryRepository->findOneBy(['id' => (int)@$request->get('product')['subcategory']]));
            //si precio o costo no son iguales a los ultimos valores registrados, guardo los nuevos valores de costo y precio,
            if ($form->get('price')->getData() !== $data['product']->getPrice() || $form->get('cost')->getData() !== $data['product']->getCost()) {
                $historicalPriceCost = new HistoricalPriceCost;
                $historicalPriceCost->setProduct($data['product']);
                $historicalPriceCost->setPrice($form->get('price')->getData());
                $historicalPriceCost->setCost($form->get('cost')->getData());
                $historicalPriceCost->setCreatedByUser($this->getUser());
                $entityManager->persist($historicalPriceCost);
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
                        $tmpImage = $this->imageManager->make($file);
                        $tmpImagePath = sys_get_temp_dir() . '/' . uniqid() . '.jpg';
                        $tmpImage->save($tmpImagePath);

                        $originalImagePath = $_ENV['APP_ENV'] === 'dev' ? 'testing/' . $this->pathImg . '/' . $productNameSlug . '-' . uniqid() . '.jpg' : $this->pathImg . '/' . $productNameSlug . '-' . uniqid() . '.jpg';
                        $scaledImagePath = $_ENV['APP_ENV'] === 'dev' ? 'testing/' . $this->pathImg . '/thumbnails' . '/' . $productNameSlug . '-' . uniqid() . '.jpg' : $this->pathImg . '/' . $productNameSlug . '-' . uniqid() . '.jpg';

                        // Subir la imagen original al S3
                        $s3->putObject([
                            'Bucket' => $_ENV['AWS_S3_BUCKET_NAME'],
                            'Key' => $originalImagePath,
                            'Body' => file_get_contents($tmpImagePath),
                            'ACL' => 'public-read',
                        ]);

                        // Escalar la imagen proporcionalmente
                        $scaledImage = $tmpImage->resize(200, null, function ($constraint) {
                            $constraint->aspectRatio();
                            $constraint->upsize();
                        });

                        // Subir la imagen escalada al S3
                        $s3->putObject([
                            'Bucket' => $_ENV['AWS_S3_BUCKET_NAME'],
                            'Key' => $scaledImagePath,
                            'Body' => $scaledImage->encode('jpg'),
                            'ACL' => 'public-read',
                        ]);

                        // Eliminar el archivo temporal
                        unlink($tmpImagePath);

                        $images->setImage($_ENV['AWS_S3_URL'] . '/' . $originalImagePath);
                        // Guardar la ruta de la imagen escalada en la entidad de imágenes si es necesario
                        $images->setImgThumbnail($_ENV['AWS_S3_URL'] . '/' . $scaledImagePath);
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

            return $this->redirectToRoute('secure_product_index');
        }

        $data['form'] = $form;
        return $this->renderForm('secure/products/form_products.html.twig', $data);
    }

    /**
     * @Route("/{product_id}/discount", name="secure_product_discount", methods={"GET","POST"})
     */
    public function discount(EntityManagerInterface $em, $product_id, Request $request, ProductRepository $productRepository, ProductDiscountRepository $productDiscountRepository): Response
    {
        $data['title'] = 'Descuento de producto';
        $data['breadcrumbs'] = array(
            array('path' => 'secure_product_index', 'title' => 'Productos'),
            array('active' => true, 'title' => $data['title'])
        );
        $data['files_js'] = array('table_full_buttons.js?v=' . rand());
        $data['product'] = $productRepository->find($product_id);
        $data['products_discount'] = $productDiscountRepository->findBy(['product' => $product_id]);
        $data['new_product_discount'] = new ProductDiscount;
        $form = $this->createForm(ProductDiscountType::class, $data['new_product_discount']);


        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $em;
            $now = new \DateTime();
            if ($form->get('start_date')->getData() > $now) {
                $data['new_product_discount']->setActive(false);
            }
            $data['new_product_discount']->setProduct($data['product']);
            $data['new_product_discount']->setCreatedByUser($this->getUser());
            $entityManager->persist($data['new_product_discount']);

            $productDiscountRepository->disableAllDiscountProduct($product_id);

            $entityManager->flush();
            $this->addFlash(
                'message',
                [
                    "alert-color" => "success",
                    "title" => 'Nuevo descuento generado.',
                    "message" => 'Se generó un nuevo descuento correctamente.'
                ]
            );
            return $this->redirectToRoute('secure_product_discount', ['product_id' => $product_id]);
        }

        $data['form'] = $form;
        return $this->renderForm('secure/products/form_discount.html.twig', $data);
    }

    /**
     * @Route("/discount/{discount_id}/disable", name="secure_product_discount_disable", methods={"GET"})
     */
    public function disableDiscount(EntityManagerInterface $em, $discount_id, ProductDiscountRepository $productDiscountRepository): Response
    {
        $data['products_discount'] = $productDiscountRepository->find($discount_id);
        $entityManager = $em;
        $data['products_discount']->setActive(false);
        $entityManager->persist($data['products_discount']);
        $entityManager->flush();
        $this->addFlash(
            'message',
            [
                "alert-color" => "success",
                "title" => 'Descuento desactivado.',
                "message" => 'Se desactivó el descuento correctamente.'
            ]
        );
        return $this->redirectToRoute('secure_product_discount', ['product_id' => $data['products_discount']->getProduct()->getId()]);
    }

    /**
     * @Route("/{product_id}/tag", name="secure_product_tag", methods={"GET","POST"})
     */
    public function tag(EntityManagerInterface $em, $product_id, Request $request, ProductRepository $productRepository): Response
    {
        $data['title'] = 'Etiqueta';
        $data['breadcrumbs'] = array(
            array('path' => 'secure_product_index', 'title' => 'Productos'),
            array('active' => true, 'title' => $data['title'])
        );
        $data['files_js'] = array('../select2.min.js', 'product/tag.js?v=' . rand());
        $data['files_css'] = array('select2.min.css', 'select2-bootstrap4.min.css');
        $data['product'] = $productRepository->find($product_id);
        $form = $this->createForm(ProductTagType::class, $data['product']);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if (!$form->get('tag_expires')->getData()) {
                $data['product']->setTagExpires(false);
                $data['product']->setTagExpirationDate(null);
            }

            $entityManager = $em;

            $entityManager->persist($data['product']);

            $entityManager->flush();

            return $this->redirectToRoute('secure_product_index');
        }

        $data['form'] = $form;
        return $this->renderForm('secure/products/form_tag.html.twig', $data);
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
    public function deleteImageProduct(EntityManagerInterface $em, ProductImagesRepository $productImagesRepository, Request $request): Response
    {
        $em = $em;
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
            $paththumbnail = explode($_ENV['AWS_S3_URL'] . '/', $imageObject->getImgThumbnail())[1];


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

            $s3->deleteObject([
                'Bucket' => $_ENV['AWS_S3_BUCKET_NAME'],
                'Key'    => $paththumbnail
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
    public function newPrincipalImage(EntityManagerInterface $em, ProductImagesRepository $productImagesRepository, Request $request): Response
    {

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


    /**
     * @Route("/updateVisible/product", name="secure_product_update_visible", methods={"post"})
     */
    public function updateVisible(EntityManagerInterface $em, Request $request, ProductRepository $ProductRepository): Response
    {
        $id = (int)$request->get('id');
        $visible = $request->get('visible');


        $entity_object = $ProductRepository->find($id);

        if ($visible == 'on') {
            $entity_object->setVisible(false);
            $data['visible'] = false;
        } else {
            $entity_object->setVisible(true);
            $data['visible'] = true;
        }

        $entityManager = $em;
        $entityManager->persist($entity_object);
        $entityManager->flush();

        $data['status'] = true;

        return new JsonResponse($data);
    }
}
