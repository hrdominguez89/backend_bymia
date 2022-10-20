<?php

namespace App\Controller\Secure;

use App\Repository\BrandRepository;
use App\Repository\ProductImagesRepository;
use App\Repository\ProductRepository;
use App\Repository\ProductSpecificationRepository;
use App\Repository\ProductSubcategoryRepository;
use App\Repository\ProductTagRepository;
use App\Repository\SpecificationRepository;
use App\Repository\SubcategoryRepository;
use App\Repository\TagRepository;
use App\Service\FileUploader;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/product")
 */
class ProductsController extends AbstractController
{
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
}
