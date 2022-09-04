<?php

namespace App\Controller\Secure;

use App\Entity\Brand;
use App\Entity\Product;
use App\Entity\ProductImages;
use App\Entity\ProductSpecification;
use App\Entity\ProductSubcategory;
use App\Entity\ProductTag;
use App\Entity\SpecificationType;
use App\Entity\Tag;
use App\Repository\BrandRepository;
use App\Repository\CategoryRepository;
use App\Repository\ProductImagesRepository;
use App\Repository\ProductRepository;
use App\Repository\ProductSpecificationRepository;
use App\Repository\ProductSubcategoryRepository;
use App\Repository\ProductTagRepository;
use App\Repository\SpecificacionTypeRepository;
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
    private $productRepository;
    private $productEspecificacionRepository;
    private $brandRepository;
    private $tagRepository;
    private $subcategoryRepository;
    private $specificacionRepository;
    private $productSubcategoryRepository;
    private $productTagRepository;
    private $parameterBag;
    private $productImagesRepository;

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
     * @Route("/load", name="secure_crud_product_load", methods={"GET","POST"})
     */
    public function loadAction()
    {
        $httpClient = HttpClient::create(['verify_peer' => false, 'verify_host' => false]);
        $urlClientAPI = $this->parameterBag->get('url.product.api');
        $response = $httpClient->request('GET', $urlClientAPI);
        $productsJSON = $response->toArray();
        $this->processLoad($productsJSON, null);
        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
        // return new  JsonResponse(array("productos" => $this->processLoad($productsJSON, null)));
    }

    public function processLoad($productsJSON, ?Product $parentProduct)
    {
        //process info
        foreach ($productsJSON as $productJSON) {

            $product = $this->productRepository->find($productJSON['id']);

            if ($product === null) {
                $product = new Product();
            }

            $product->setSku(isset($productJSON['sku']) ? $productJSON['sku'] : null);
            $product->setParentId(isset($productJSON['productIdParent']) ? $productJSON['productIdParent'] : null);
            $product->setName(isset($productJSON['name']) ? $productJSON['name'] : (!is_null($parentProduct) ? $parentProduct->getName() : "-"));
            $product->setDescription(isset($productJSON['description']) ? $productJSON['description'] : null);
            $product->setStock(isset($productJSON['stock']) ? floatval($productJSON['stock']) : 0);
            $product->setUrl(isset($productJSON['url']) ? $productJSON['url'] : null);
            $product->setBrandId(isset($productJSON['brand']) ? $this->brandRepository->findOneBy(['apiId' => $productJSON['brand']]) : null);
            $product->setWeight(isset($productJSON['weight']) ? floatval($productJSON['weight']) : 0);
            $product->setPrice(isset($productJSON['price']) ? floatval($productJSON['price']) : 0);
            $product->setOfferPrice(isset($productJSON['offerPrice']) ? floatval($productJSON['offerPrice']) : 0);
            // $product->setOfferStartDate(new \DateTime());
            // $product->setOfferEndDate(new \DateTime());
            $product->setHtmlDescription(isset($productJSON['htmlDescription']) ? $productJSON['htmlDescription'] : null);
            $product->setShortDescription(isset($productJSON['shortDescription']) ? $productJSON['shortDescription'] : null);
            $product->setColor(isset($productJSON['color']) ? $productJSON['color'] : null);
            $product->setLength(isset($productJSON['length']) ? floatval($productJSON['length']) : 0);
            $product->setDimensions(isset($productJSON['dimentions']) ? $productJSON['dimentions'] : null);
            $product->setFeatured(isset($productJSON['featured']) ? (bool)$productJSON['featured'] : false);

            $this->getDoctrine()->getManager()->persist($product);
            $this->getDoctrine()->getManager()->flush();

            if (isset($productJSON['especifications']) && !empty($productJSON['especifications']))
                $this->createSpecifications($product, $productJSON['especifications']);

            if (isset($productJSON['tags']) && !empty($productJSON['tags']))
                $this->asociateTag($product, $productJSON['tags']);

            if (isset($productJSON['subcategory']) && !empty($productJSON['subcategory']))
                $this->asociateSubcategory($product, $productJSON['subcategory']);

            if (isset($productJSON['variation'])) {
                $this->processLoad($productJSON['variation'], $product);
            }
        }
    }

    public function createSpecifications($product, $array_specifications)
    {
        foreach ($array_specifications as $specifications) {
            $obj_specifications = $this->specificacionRepository->findOneBy(['apiId' => $specifications['id_api']]);
            if ($obj_specifications) {
                $element = $this->productEspecificacionRepository->findOneBy(['product' => $product, 'especificacion' => $obj_specifications]);
                if (!$element) {
                    $new_specifications_product = new ProductSpecification($product, $obj_specifications);
                    $new_specifications_product->setValue($specifications['value']);
                    $new_specifications_product->setCreateVariation(true);
                    $new_specifications_product->setCustomFieldsType($obj_specifications->getSpecificationTypeId()->getDefaultCustomFieldsType());
                    $new_specifications_product->setCustomFieldsValue($obj_specifications->getDefaultCustomFieldsValue());
                    $this->getDoctrine()->getManager()->persist($new_specifications_product);
                    $this->getDoctrine()->getManager()->flush();
                } else {
                    $element
                        ->setCreateVariation(true)
                        ->setValue($specifications['value']);
                    $this->getDoctrine()->getManager()->persist($element);
                    $this->getDoctrine()->getManager()->flush();
                }
            }
        }
    }

    public function asociateTag($product, $array_tag)
    {
        $this->deleteProductTag($product);

        foreach ($array_tag as $tag_id) {
            $obj_tag = $this->tagRepository->findOneBy(['apiId' => $tag_id['id_api']]);
            if ($obj_tag) {
                $new_product_tag = new ProductTag();
                $new_product_tag->setProductId($product);
                $new_product_tag->setTagId($obj_tag);
                $this->getDoctrine()->getManager()->persist($new_product_tag);
                $this->getDoctrine()->getManager()->flush();
            }
        }
    }

    public function asociateSubcategory($product, $array_subcategory)
    {
        $this->deleteProductSubcategory($product);

        foreach ($array_subcategory as $subcategory_id) {
            $obj_subcategory = $this->subcategoryRepository->findOneBy(['apiId' => $subcategory_id['id_api']]);
            if ($obj_subcategory) {
                $new_product_subcategory = new ProductSubcategory($product, $obj_subcategory);
                $this->getDoctrine()->getManager()->persist($new_product_subcategory);
                $this->getDoctrine()->getManager()->flush();
            }
        }
    }

    public function deleteProductTag($product)
    {
        $produtsTag = $this->productTagRepository->findBy(['productId' => $product]);
        foreach ($produtsTag as $element) {
            $this->getDoctrine()->getManager()->remove($element);
        }
        $this->getDoctrine()->getManager()->flush();
    }

    public function deleteProductSubcategory($product)
    {
        $produtsSubcategory = $this->productSubcategoryRepository->findBy(['productId' => $product]);
        foreach ($produtsSubcategory as $element) {
            $this->getDoctrine()->getManager()->remove($element);
        }
        $this->getDoctrine()->getManager()->flush();
    }


    /**
     * @Route("/get-all-tags", name="secure_crud_product_get_all_tags", methods={"GET","POST"})
     */
    public function getAllTagsAction(TagRepository $brandRepository)
    {
        $tags =  $brandRepository->findAll();
        $tagsArray = [];
        foreach ($tags as $item) {
            /** @var Tag $item */
            $tagsArray[] = array(
                'id' => $item->getId(),
                'name' => $item->getName(),
            );
        }
        return new JsonResponse($tagsArray);
    }

    /**
     * @Route("/get-all-brands", name="secure_crud_product_get_all_brands", methods={"GET","POST"})
     */
    public function getAllBrandsAction(BrandRepository $brandRepository)
    {
        $brands =  $brandRepository->findAll();
        $brandsArray = [];
        foreach ($brands as $item) {
            /** @var Brand $item */
            $brandsArray[] = array(
                'id' => $item->getId(),
                'name' => $item->getName(),
            );
        }
        return new JsonResponse($brandsArray);
    }

    /**
     * @Route("/get-all-specifications", name="secure_crud_product_get_all_specifications", methods={"GET","POST"})
     */
    public function getAllSpecificationsAction(SpecificacionTypeRepository $specificationTypeRepository)
    {
        $specificationsTypes =  $specificationTypeRepository->findAll();
        $specificationsTypesArray = [];
        foreach ($specificationsTypes as $item) {
            /** @var SpecificationType $item */
            $specifications = $item->getSpecifications();
            $specificationsArray = [];
            foreach ($specifications as $subItem) {
                $specificationsArray[] = array(
                    'id' => $subItem->getId(),
                    'name' => $subItem->getName(),
                    'slug' => $subItem->getSlug(),
                );
            }
            $specificationsTypesArray[] = array(
                'id' => $item->getId(),
                'name' => $item->getName(),
                'slug' => $item->getSlug(),
                'children' => $specificationsArray
            );
        }
        return new JsonResponse($specificationsTypesArray);
    }

    /**
     * @Route("/get-all-categorys", name="secure_crud_product_get_all_categorys", methods={"GET","POST"})
     */
    public function getAllCategoryAction(CategoryRepository $categoryRepository)
    {
        $categorys =  $categoryRepository->findAll();
        $categorysArray = [];
        foreach ($categorys as $item) {
            /** @var Category $item */
            $subcategory = $item->getSubcategories();
            $subcategorysArray = [];
            foreach ($subcategory as $subItem) {
                $subcategorysArray[] = array(
                    'id' => $subItem->getId(),
                    'name' => $subItem->getName(),
                    'slug' => $subItem->getSlug(),
                );
            }
            $categorysArray[] = array(
                'id' => $item->getId(),
                'name' => $item->getName(),
                'slug' => $item->getSlug(),
                'children' => $subcategorysArray
            );
        }
        return new JsonResponse($categorysArray);
    }

    /**
     * @Route("/get-all/{page}", name="secure_crud_product_get", methods={"GET","POST"})
     */
    public function getAction(ProductRepository $productRepository, Request $request, int $page)
    {
        $limit = $request->get('limit') ? $request->get('limit') : 10;
        $offset = 0;
        if (isset($page))
            $offset = ($page - 1) * $limit;

        $products_array = $productRepository->list($limit, $offset);
        $cantArray = $productRepository->cant($limit, $offset);
        $cant = $cantArray[0]['count'];

        $products = [];
        foreach ($products_array as $item) {
            /** @var Product $item */

            $productSpecifications = $this->productEspecificacionRepository->findBy(['productId' => $item['id']]);
            $productSpecificationsArray = [];
            foreach ($productSpecifications  as $subItem) {
                /** @var ProductSpecification $subItem */
                $productSpecificationsArray[] = array(
                    'value' => $subItem->getValue(),
                    'especificacion' => array(
                        'id' => $subItem->getSpecificationId()->getId(),
                        'name' => $subItem->getSpecificationId()->getName(),
                        'id_tipo_especificacion' => array(
                            'name' => $subItem->getSpecificationId()->getSpecificationTypeId()->getName()
                        )
                    )
                );
            }
            $productSubcategorias = $this->productSubcategoryRepository->findBy(['productId' => $item['id']]);
            $productSubcategoriasArray = [];
            foreach ($productSubcategorias  as $subItem) {
                /** @var ProductSubcategory $subItem */
                $productSubcategoriasArray[] = array(
                    'subCategoria' => array(
                        'id' => $subItem->getSubCategory()->getId()
                    )
                );
            }
            $productTags = $this->productTagRepository->findBy(['productId' => $item['id']]);
            $productTagsArray = [];
            foreach ($productTags  as $subItem) {
                /** @var ProductTag $subItem */
                $productTagsArray[] = array(
                    'tag' => array(
                        'id' => $subItem->getTagId()->getId(),
                        'name' => $subItem->getTagId()->getName()
                    )
                );
            }
            $productImages = $this->productImagesRepository->findBy(['productId' => $item['id']]);
            $productImagesArray = [];
            foreach ($productImages  as $subItem) {
                /** @var ProductImages $subItem */
                $productImagesArray[] = array(
                    'id' => $subItem->getId(),
                    'image' => $subItem->getImage(),
                );
            }
            $childrens = $productRepository->findBy(array('parentId' => $item['parent_id']));
            $childrensArray = [];
            foreach ($childrens  as $subItem) {
                /** @var Product $subItem */

                $productSpecificationsChild = $subItem->getProductSpecifications();
                $productSpecificationsChildArray = [];
                foreach ($productSpecificationsChild  as $subItemChild) {
                    /** @var ProductSpecification $subItemChild */
                    $productSpecificationsChildArray[] = array(
                        'value' => $subItemChild->getValue(),
                        'especificacion' => array(
                            'id' => $subItemChild->getSpecificationId()->getId(),
                            'name' => $subItemChild->getSpecificationId()->getName()
                        )
                    );
                }
                $productImagesChild = $subItem->getImages();
                $productImagesChildArray = [];
                foreach ($productImagesChild  as $subItemChild) {
                    /** @var ProductImages $subItemChild */
                    $productImagesChildArray[] = array(
                        'id' => $subItemChild->getId(),
                        'image' => $subItemChild->getImage(),
                    );
                }
                $childrensArray[] = array(
                    'id' => $subItem->getId(),
                    'sku' => $subItem->getSku(),
                    'dimentions' => $subItem->getDimensions(),
                    'shortDescription' => $subItem->getShortDescription(),
                    'price' => $subItem->getPrice(),
                    'offerPrice' => $subItem->getOfferPrice(),
                    'offerStartDate' => $subItem->getOfferStartDate(),
                    'offerEndDate' => $subItem->getOfferEndDate(),
                    'images' => $productImagesChildArray,
                    'productEspecificacions' => $productSpecificationsChildArray
                );
            }

            $products[] = array(
                'id' => $item['id'],
                'image' => ($item['image']) ? $item['image'] : 'uploads/images/default.jpg',
                'name' => $item['name'],
                'price' => $item['price'],
                'destacado' => $item['featured'],
                'date' => (new \DateTime($item['date']))->format("c"),
                'htmlDescription' => (!is_null($item['html_description'])) ? $item['html_description'] : "",
                'shortDescription' => (!is_null($item['short_description'])) ? $item['short_description'] : "",

                'brand' => array(
                    'id' => $item['brand_id']
                ),
                'productEspecificacions' => $productSpecificationsArray,
                'productSubcategorias' => $productSubcategoriasArray,
                'productTag' => $productTagsArray,
                'images' => $productImagesArray,
                'childrens' => $childrensArray,
            );
        }
        return new JsonResponse(array(
            'total_elements' => $cant,
            'current_page' => $page,
            'elements_per_page' => intval($limit),
            'total_page' => intval($cant) % $limit > 0 ? intval(intval($cant) / $limit) + 1 : intval($cant),
            'products' => $products
        ));
    }

    /**
     * @Route("/image/edit/{id}", name="secure_crud_product_image_edit", methods={"GET","POST"})
     */
    public function imageEditAction(
        string $id,
        Request $request,
        FileUploader $fileUploader
    ) {
        $parametersAsArray = [];
        if ($content = $request->getContent()) {
            $parametersAsArray = json_decode($content, true);
        }
        $product = $this->productRepository->find($id);

        $image = isset($parametersAsArray['image']) ? $parametersAsArray['image'] : null;
        if ($image) {
            $filename = $fileUploader->uploadBase64File($image);
            $product->setImage("/uploads/images/" . $filename);
        } else {
            $product->setImage(null);
        }

        $this->getDoctrine()->getManager()->persist($product);
        $this->getDoctrine()->getManager()->flush();
        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * @Route("/images/add/{id}", name="secure_crud_product_images_add", methods={"GET","POST"})
     */
    public function imagesAddAction(
        string $id,
        Request $request,
        FileUploader $fileUploader
    ) {
        $parametersAsArray = [];
        if ($content = $request->getContent()) {
            $parametersAsArray = json_decode($content, true);
        }

        $product = $this->productRepository->find($id);

        $idImage = $parametersAsArray['idImage'];
        $image = $parametersAsArray['image'];

        $filename = $fileUploader->uploadBase64File($image);

        $productImagen = new ProductImages($idImage, $product,);
        $productImagen->setImage("/uploads/images/" . $filename);
        $productImagen->setProductId($product);

        $this->getDoctrine()->getManager()->persist($productImagen);
        $this->getDoctrine()->getManager()->flush();
        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * @Route("/images/delete/{id}", name="secure_crud_product_images_delete", methods={"DELETE"})
     */
    public function imagesDeleteAction(string $id, ProductImagesRepository $productImagesRepository)
    {
        $productImages = $productImagesRepository->find($id);

        $this->getDoctrine()->getManager()->remove($productImages);
        $this->getDoctrine()->getManager()->flush();
        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * @Route("/featured/{id}", name="secure_crud_product_featured", methods={"GET","POST"})
     */
    public function featuredAction(
        string $id
    ) {
        $product = $this->productRepository->find($id);

        $product->setFeatured(!$product->isFeatured());
        $this->getDoctrine()->getManager()->persist($product);
        $this->getDoctrine()->getManager()->flush();
        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * @Route("/edit/{id}", name="secure_crud_product_edit", methods={"GET","POST"})
     */
    public function editAction(
        string $id,
        Request $request,
        BrandRepository $brandRepository,
        SpecificationRepository $especificacionRepository,
        SubcategoryRepository $subcategoriaRepository,
        TagRepository $tagRepository
    ) {
        $parametersAsArray = [];
        if ($content = $request->getContent()) {
            $parametersAsArray = json_decode($content, true);
        }

        $product = $this->productRepository->find($id);

        if (isset($parametersAsArray['sku']) && $parametersAsArray['sku'] != '')
            $product->setSku($parametersAsArray['sku']);
        if (isset($parametersAsArray['productIdParent']) && $parametersAsArray['productIdParent'] != '')
            $product->setParentId($parametersAsArray['productIdParent']);
        if (isset($parametersAsArray['name']) && $parametersAsArray['name'] != '')
            $product->setName($parametersAsArray['name']);
        if (isset($parametersAsArray['description']) && $parametersAsArray['description'] != '')
            $product->setDescription($parametersAsArray['description']);
        if (isset($parametersAsArray['stock']) && $parametersAsArray['stock'] != '')
            $product->setStock($parametersAsArray['stock']);
        if (isset($parametersAsArray['url']) && $parametersAsArray['url'] != '')
            $product->setUrl($parametersAsArray['url']);

        if (isset($parametersAsArray['brand']) && $parametersAsArray['brand'] != '')
            $product->setBrandId($brandRepository->find($parametersAsArray['brand']));

        if (isset($parametersAsArray['weight']) && $parametersAsArray['weight'] != '')
            $product->setWeight($parametersAsArray['weight']);
        if (isset($parametersAsArray['price']) && $parametersAsArray['price'] != '')
            $product->setPrice($parametersAsArray['price']);
        if (isset($parametersAsArray['offerPrice']) && $parametersAsArray['offerPrice'] != '')
            $product->setOfferPrice($parametersAsArray['offerPrice']);

        if (isset($parametersAsArray['offerStartDate']) && $parametersAsArray['offerStartDate'] != '')
            $product->setOfferStartDate(new \DateTime($parametersAsArray['offerStartDate']));
        if (isset($parametersAsArray['offerEndDate']) && $parametersAsArray['offerEndDate'] != '')
            $product->setOfferEndDate(new \DateTime($parametersAsArray['offerEndDate']));

        if (isset($parametersAsArray['htmlDescription']) && $parametersAsArray['htmlDescription'] != '')
            $product->setHtmlDescription($parametersAsArray['htmlDescription']);
        if (isset($parametersAsArray['shortDescription']) && $parametersAsArray['shortDescription'] != '')
            $product->setShortDescription($parametersAsArray['shortDescription']);
        if (isset($parametersAsArray['color']) && $parametersAsArray['color'] != '')
            $product->setColor($parametersAsArray['color']);
        if (isset($parametersAsArray['length']) && $parametersAsArray['length'] != '')
            $product->setLength($parametersAsArray['length']);
        if (isset($parametersAsArray['dimentions']) && $parametersAsArray['dimentions'] != '')
            $product->setDimensions($parametersAsArray['dimentions']);
        if (isset($parametersAsArray['destacado']) && $parametersAsArray['destacado'] != '')
            $product->setFeatured($parametersAsArray['destacado']);

        $this->getDoctrine()->getManager()->persist($product);
        $this->getDoctrine()->getManager()->flush();

        //product especificaciones
        foreach ($product->getProductSpecifications() as $productEspecificacion) {
            $this->getDoctrine()->getManager()->remove($productEspecificacion);
            $this->getDoctrine()->getManager()->flush();
        }
        if (isset($parametersAsArray['especifications'])) {
            $productEspecificacions = $parametersAsArray['especifications'];
            foreach ($productEspecificacions as $productEspecificacionId) {
                $especificacion = $especificacionRepository->find($productEspecificacionId['specification_selected']);
                $value = $productEspecificacionId['value_'];
                $variaciones = $productEspecificacionId['variaciones'];
                $productEspecificacion = new ProductSpecification($product, $especificacion);
                $productEspecificacion->setValue($value[0]);
                $productEspecificacion->setCreateVariation($variaciones);
                $productEspecificacion->setCustomFieldsType($especificacion->getSpecificationTypeId()->getDefaultCustomFieldsType());
                $productEspecificacion->setCustomFieldsValue($especificacion->getDefaultCustomFieldsValue());
                $this->getDoctrine()->getManager()->persist($productEspecificacion);
                $this->getDoctrine()->getManager()->flush();
            }
        }
        //product subcategorias
        foreach ($product->getProductSubcategories() as $productSubcategorias) {
            $this->getDoctrine()->getManager()->remove($productSubcategorias);
            $this->getDoctrine()->getManager()->flush();
        }
        if (isset($parametersAsArray['subcategory'])) {
            $productSubcategorias = $parametersAsArray['subcategory'];
            foreach ($productSubcategorias as $productSubcategoriaId) {
                if (is_array($productSubcategoriaId))
                    $subcategoria = $subcategoriaRepository->find($productSubcategoriaId['id_li']);
                else
                    $subcategoria = $subcategoriaRepository->find($productSubcategoriaId);
                $productSubcategoria = new ProductSubcategory($product, $subcategoria);
                $this->getDoctrine()->getManager()->persist($productSubcategoria);
                $this->getDoctrine()->getManager()->flush();
            }
        }
        //product tags
        foreach ($product->getProductTag() as $productTag) {
            $this->getDoctrine()->getManager()->remove($productTag);
            $this->getDoctrine()->getManager()->flush();
        }
        if (isset($parametersAsArray['tags'])) {
            $productTags = $parametersAsArray['tags'];
            foreach ($productTags as $productTagId) {
                $tag = $tagRepository->find($productTagId);
                $productTag = new ProductTag($product, $tag);
                $productTag->setProductId($product);
                $productTag->setTagId($tag);
                $this->getDoctrine()->getManager()->persist($productTag);
                $this->getDoctrine()->getManager()->flush();
            }
        }
        //product childrens
        if (isset($parametersAsArray['childrens'])) {
            $childrens = $parametersAsArray['childrens'];
            $this->process($childrens, $product);
        }

        $statusCode = $product ? Response::HTTP_CREATED : Response::HTTP_BAD_REQUEST;
        $data = $product ?? "Error en el Servidor";
        return new JsonResponse($statusCode);
    }

    public function process($productsJSON, ?Product $parentProduct)
    {
        //process info
        foreach ($productsJSON as $productJSON) {

            $product = $this->productRepository->findOneBy(['sku' => $productJSON['sku']]);

            $offerStartDate = $productJSON['offerStartDate'] == '' ? null : new \DateTime($productJSON['offerStartDate']);
            $offerEndDate = $productJSON['offerEndDate'] == '' ? null : new \DateTime($productJSON['offerEndDate']);

            $length = isset($productJSON['length']) ? floatval($productJSON['length']) : 0;
            $width = isset($productJSON['width']) ? floatval($productJSON['width']) : 0;
            $heigth = isset($productJSON['heigth']) ? floatval($productJSON['heigth']) : 0;
            $dimentions  = $length . 'x' . $width . 'x' . $heigth;

            if ($product === null) {
                $product = new Product();
            }
            $product->setSku(isset($productJSON['sku']) ? $productJSON['sku'] : null);
            $product->setParentId(isset($productJSON['productIdParent']) ? $productJSON['productIdParent'] : null);
            $product->setName(isset($productJSON['name']) ? $productJSON['name'] : (!is_null($parentProduct) ? $parentProduct->getName() : "-"));
            $product->setDescription(isset($productJSON['description']) ? $productJSON['description'] : null);
            $product->setStock(isset($productJSON['stock']) ?  floatval($productJSON['stock']) : 0);
            $product->setUrl(isset($productJSON['url']) ? $productJSON['url'] : null);
            $product->setWeight(isset($productJSON['weigth']) ? floatval($productJSON['weigth']) : 0);
            $product->setPrice(isset($productJSON['price']) ? floatval($productJSON['price']) : 0);
            $product->setOfferPrice(($productJSON['offerPrice'] != '') ? floatval($productJSON['offerPrice']) : 0);
            $product->setOfferStartDate($offerStartDate);
            $product->setOfferEndDate($offerEndDate);
            $product->setHtmlDescription(isset($productJSON['htmlDescription']) ? $productJSON['htmlDescription'] : null);
            $product->setShortDescription(isset($productJSON['short_description']) ? $productJSON['short_description'] : null);
            $product->setColor(isset($productJSON['color']) ? $productJSON['color'] : null);
            $product->setLength($length);
            $product->setDimensions($dimentions);
            $product->setFeatured(isset($productJSON['destacado']) ? (bool)$productJSON['destacado'] : false);

            // $product->setParent($parentProduct);
            $this->getDoctrine()->getManager()->persist($product);
            $this->getDoctrine()->getManager()->flush();

            if (isset($productJSON['childrens'])) {
                $this->process($productJSON['childrens'], $product);
            }
        }
    }
}
