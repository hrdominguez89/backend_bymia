<?php

namespace App\Controller\Secure;

use App\Entity\Product;
use App\Entity\ProductImages;
use App\Form\ProductType;
use App\Repository\BrandRepository;
use App\Repository\ProductImagesRepository;
use App\Repository\ProductRepository;
use App\Repository\ProductSpecificationRepository;
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
        ProductSpecificationRepository $productEspecificacionRepository,
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
        $this->productEspecificacionRepository = $productEspecificacionRepository;
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
     * @Route("/index", name="secure_crud_product_index", methods={"GET"})
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
    public function new(Request $request, FileUploader $fileUploader): Response
    {
        $data['title'] = 'Nuevo producto';
        $data['breadcrumbs'] = array(
            array('path' => 'secure_crud_brand_index', 'title' => 'Productos'),
            array('active' => true, 'title' => $data['title'])
        );
        $data['product'] = new Product;
        $form = $this->createForm(ProductType::class, $data['product']);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                foreach ($form->get('image')->getData() as $file) {
                    $images = new ProductImages;
                    $imageFileName = $fileUploader->upload($file, $this->pathImg, $form->get('title')->getData());
                    $images->setImage($_ENV['AWS_S3_URL'] . '/' . $this->pathImg . '/' . $imageFileName);
                    $images->setProduct($data['product']);
                    $entityManager->persist($images);
                }
            }
            $entityManager->persist($data['product']);
            $entityManager->flush();

            return $this->redirectToRoute('secure_crud_product_index', [], Response::HTTP_SEE_OTHER);
        }

        $data['form'] = $form;
        return $this->renderForm('secure/crud_product/form_products.html.twig', $data);
    }
}
