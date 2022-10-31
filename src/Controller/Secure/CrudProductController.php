<?php

namespace App\Controller\Secure;

use App\Entity\Brand;
use App\Entity\Product;
use App\Entity\ProductImages;
use App\Entity\ProductSpecification;
use App\Entity\ProductSubcategory;
use App\Entity\ProductTag;
use App\Entity\Specification;
use App\Entity\Subcategory;
use App\Entity\Tag;
use App\Form\Model\ProductSearchDto;
use App\Form\ProductSearchType;
use App\Form\ProductType;
use App\Repository\ProductImagesRepository;
use App\Repository\ProductRepository;
use App\Repository\ProductSpecificationRepository;
use App\Repository\ProductSubcategoryRepository;
use App\Repository\ProductTagRepository;
use App\Repository\SpecificationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * @Route("/_product")
 */
class CrudProductController extends AbstractController
{
    /**
     * @Route("/load", name="_secure_crud_product_load")
     */
    public function load(ParameterBagInterface $parameterBag, EntityManagerInterface $em, SpecificationRepository $specificationRepository)
    {
        //read product json
        $httpClient = HttpClient::create(['verify_peer' => false, 'verify_host' => false]);
        $urlClientAPI = $parameterBag->get('url.product.api');
        $response = $httpClient->request('GET', $urlClientAPI);
        $productsJSON = $response->toArray();

        foreach ($productsJSON as $item) {
            $childrens = $item['childrens'];
            $specifications = $item['specifications'];
            $new_product = new Product();
            $new_product
                ->setName($item['name'])
                ->setSku($item['sku'])
                ->setSales(0)
                ->setReviews(0)
                ->setStock(floatval($item['stock']))
                ->setPrice(floatval($item['price']));
            $em->persist($new_product);

            if (!empty($specifications)) {
                foreach ($specifications as $specification) {
                    $apiId = $specification['id_api'];
                    $objSpecification = $specificationRepository->findOneBy(['active' => true, 'apiId' => $apiId]);
                    if ($objSpecification) {
                        $values = $specification['values'];
                        foreach ($values as $val) {
                            $new_product_specifications = new ProductSpecification($new_product,$objSpecification);
                            $new_product_specifications
                                ->setCustomFieldsType($specification['type'])
                                ->setValue($val['value'])
                                ->setCustomFieldsValue($val['custum_value']);
                            $em->persist($new_product_specifications);
                        }
                    }
                }
            }

            if (!empty($childrens)) {
                foreach ($childrens as $child) {
                    $new_product_children = new Product();
                    $new_product_children
                        ->setName($child['name'])
                        ->setSku($child['sku'])
                        ->setPrice(floatval($child['price']))
                        ->setStock(floatval($child['stock']))
                        ->setSales(0)
                        ->setReviews(0)
                        ->setParentId($new_product->getId());
                    $em->persist($new_product_children);

                    $childrens_spacifications = $child['specifications'];
                    if (!empty($childrens_spacifications)) {
                        foreach ($childrens_spacifications as $specification_child) {
                            $apiId = $specification_child['id_api'];
                            $objSpecification = $specificationRepository->findOneBy(['active' => true, 'apiId' => $apiId]);
                            if ($objSpecification) {
                                $values = $specification_child['values'];
                                foreach ($values as $val) {
                                    $new_product_specifications = new ProductSpecification($new_product_children,$objSpecification);
                                    $new_product_specifications
                                        ->setCustomFieldsType($specification_child['type'])
                                        ->setValue($val['value'])
                                        ->setCustomFieldsValue($val['custum_value']);
                                    $em->persist($new_product_specifications);
                                }
                            }
                        }
                    }
                }
            }
            $em->flush();
        }
        $this->addFlash("success","Productos cargados satisfactoriamente");
        return $this->redirectToRoute('_secure_crud_product_index');
    }

    /**
     * @Route("/index", name="_secure_crud_product_index")
     */
    public function index(ProductRepository $productRepository, Request $request): Response
    {
        $productSearchType = new ProductSearchDto();
        $form = $this->createForm(ProductSearchType::class, $productSearchType);
        $form->handleRequest($request);

        $data = $productRepository->getParents($request, $productSearchType);
        return $this->render('secure/crud_product/index.html.twig', [
            'products' => $data,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/new", name="_secure_crud_product_new")
     */
    public function new(ProductRepository $productRepository, Request $request): Response
    {
        $productSearchType = new ProductSearchDto();
        $form = $this->createForm(ProductSearchType::class, $productSearchType);
        $form->handleRequest($request);

        $data = $productRepository->getParents($request, $productSearchType);
        return $this->render('secure/crud_product/index.html.twig', [
            'products' => $data,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/saveImage/{id}", name="secure_crud_product_save_image", methods={"POST","GET"})
     */
    public function saveImage(
        EntityManagerInterface $em,
        $id,
        SluggerInterface $slugger,
        ProductRepository $productRepository,
        ProductImagesRepository $productImagesRepository
    ): Response {
        $type = $_FILES["image"]["type"];
        $ROOT_DIR = dirname(__DIR__, 3) . '' . \DIRECTORY_SEPARATOR . 'public' . '' . \DIRECTORY_SEPARATOR . 'uploads' . '' . \DIRECTORY_SEPARATOR . 'images';
        if (($type == "image/pjpeg") || ($type == "image/jpeg") || ($type == "image/png") || ($type == "image/gif")) {
            $extension = explode('/', $type);
            $originalFilename = pathinfo($_FILES["image"]["name"], PATHINFO_FILENAME);
            $safeFilename = $slugger->slug($originalFilename);
            $fileName = $safeFilename . '-' . uniqid() . '.' . $extension[1];
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $ROOT_DIR . '' . \DIRECTORY_SEPARATOR . $fileName)) {
                $newPI = new ProductImages();
                $newPI
                    ->setImage($_ENV['SITE_URL'] . '/uploads/images/' . $fileName)
                    ->setProductId($productRepository->find($id))
                    ->setNew(true);
                $em->persist($newPI);
                $em->flush();

                $data_return = [];
                $data_db = $productImagesRepository->findBy(['productId' => $id]);
                foreach ($data_db as $item) {
                    $data_return[] = [
                        'id' => $item->getId(),
                        'images' => $item->getImage(),
                        'id_product' => $item->getProductId()->getId()
                    ];
                }
                return new JsonResponse(['success' => true, 'message' => "Datos actualizados", 'images' => $data_return]);
            } else {
                return new JsonResponse(['success' => false, 'message' => "No se ha podido guardar la imagen", 'images' => []]);
            }
        } else {
            return new JsonResponse(['success' => false, 'message' => "El formato enviado no coincide con lo admitido en el sistema.", 'images' => []]);
        }
    }

    /**
     * @Route("/saveMainImage/{id}", name="secure_crud_product_main_save_image", methods={"POST","GET"})
     */
    public function saveMainImage(
        EntityManagerInterface $em,
        $id,
        SluggerInterface $slugger,
        ProductRepository $productRepository
    ): Response {
        $type = $_FILES["image"]["type"];
        $ROOT_DIR = dirname(__DIR__, 3) . '' . \DIRECTORY_SEPARATOR . 'public' . '' . \DIRECTORY_SEPARATOR . 'uploads' . '' . \DIRECTORY_SEPARATOR . 'images';
        if (($type == "image/pjpeg") || ($type == "image/jpeg") || ($type == "image/png") || ($type == "image/gif")) {
            $extension = explode('/', $type);
            $originalFilename = pathinfo($_FILES["image"]["name"], PATHINFO_FILENAME);
            $safeFilename = $slugger->slug($originalFilename);
            $fileName = $safeFilename . '-' . uniqid() . '.' . $extension[1];
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $ROOT_DIR . '' . \DIRECTORY_SEPARATOR . $fileName)) {
                $objProduct = $productRepository->find($id);
                $objProduct->setNewImage($_ENV['SITE_URL'] . '/uploads/images/' . $fileName);
                $em->persist($objProduct);
                $em->flush();
                return new JsonResponse(['success' => true, 'message' => "Datos actualizados", 'src' => $objProduct->getNewImage()]);
            } else {
                return new JsonResponse(['success' => false, 'message' => "No se ha podido guardar la imagen", 'src' => '']);
            }
        } else {
            return new JsonResponse(['success' => false, 'message' => "El formato enviado no coincide con lo admitido en el sistema.", 'src' => '']);
        }
    }

    /**
     * @Route("/deleteImage/{id}", name="secure_crud_product_delete_image", methods={"POST","GET"})
     */
    public function deleteImage(EntityManagerInterface $em, $id, ProductImagesRepository $productImagesRepository): Response
    {
        $productImage = $productImagesRepository->find($id);
        $product = $productImage->getProductId()->getId();
        $em->remove($productImage);
        $em->flush();

        $data_return = [];
        $data_db = $productImagesRepository->findBy(['productId' => $product]);
        foreach ($data_db as $item) {
            $data_return[] = [
                'id' => $item->getId(),
                'images' => $item->getImage(),
                'id_product' => $item->getProductId()->getId()
            ];
        }
        return new JsonResponse(['success' => true, 'message' => "Imagen eliminada", 'images' => $data_return]);
    }


    /**
     * @Route("/deleteMainImage/{id}", name="secure_crud_product_main_delete_image", methods={"POST","GET"})
     */
    public function deleteMainImage(EntityManagerInterface $em, $id, ProductRepository $productRepository): Response
    {
        $product = $productRepository->find($id);
        if ($product->getNewImage() != '') {
            $product->setNewImage(null);
            $em->persist($product);
            $em->flush();
            return new JsonResponse(['success' => true, 'message' => "Imagen eliminada", 'src' => $product->getImage()]);
        } else {
            $product->setImage(null);
            $em->persist($product);
            $em->flush();
            return new JsonResponse(['success' => true, 'message' => "Imagen eliminada", 'src' => '']);
        }
    }

    /**
     * @Route("/deleteAllNewImage/{id}", name="secure_crud_product_delete_all_new_image", methods={"POST","GET"})
     */
    public function deleteAllNewImage(EntityManagerInterface $em, $id, ProductImagesRepository $productImagesRepository, ProductRepository $productRepository): Response
    {
        $childrens = $productRepository->getChildrens($id);
        $product = $productRepository->find($id);
        $product->setNewImage(null);
        $em->persist($product);
        foreach ($childrens as $child) {
            $child->setNewImage(null);
            $em->persist($child);
            $productImage = $productImagesRepository->findBy(['productId' => $child->getId(), 'new' => true]);
            foreach ($productImage as $item) {
                $em->remove($item);
            }
        }
        $productImage = $productImagesRepository->findBy(['productId' => $id, 'new' => true]);
        foreach ($productImage as $item) {
            $em->remove($item);
        }

        $em->flush();
        return $this->redirectToRoute('_secure_crud_product_index');
    }

    /**
     * @Route("/{id}/edit", name="secure_crud_product_edit_crud", methods={"GET","POST"})
     */
    public
    function edit(
        EntityManagerInterface $em,
        Request $request,
        Product $product,
        ProductRepository $productRepository,
        ProductImagesRepository $productImagesRepository,
        ProductSubcategoryRepository $productSubcategoryRepository,
        ProductSpecificationRepository $productSpecificationRepository,
        ProductTagRepository $productTagRepository
    ): Response {
        $childrens = $productRepository->getChildrens($product->getId());
        $array_childrens = [];
        $variatns_specification = [];
        foreach ($childrens as $key => $item_product) {
            //las imagenes de las varicaicones de ese producto
            $array_childrens[] = [
                'name' => $item_product->getName(),
                'id' => $item_product->getId(),
                'son' => true,
                'images' => $productImagesRepository->getDataImages($item_product->getId())
            ];

            $productSpecification = $productSpecificationRepository->findProductoSpecificationsByProduct(
                $item_product->getId()
            );

            if ($productSpecification) $variatns_specification[] = $productSpecification;
        }
        //las imagenes del producto con parent = null
        $array_childrens[] = [
            'name' => $product->getName(),
            'id' => $product->getId(),
            'son' => false,
            'images' => $productImagesRepository->getDataImages($product->getId())
        ];

        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('secure_crud_product_index', [], Response::HTTP_SEE_OTHER);
        }

        $brands = $em->getRepository(Brand::class)->findAll();
        $tags = $em->getRepository(Tag::class)->findAll();
        $specifications = $em->getRepository(Specification::class)->findBy(['active' => true]);

        /***** Specifications ParentProduct ******/
        $distinctTypes = $productSpecificationRepository->getDistincSpecification($product->getId());
        $rowSpacificationMainProduct = [];
        foreach ($distinctTypes as $item) {
            $product_specifications = $productSpecificationRepository->getDataSpecification($product->getId(), $item['custom_fields_type']);
            $values = [];
            foreach ($product_specifications as $element) {
                $values[] =
                    [
                        'property' => $element->getValue(),
                        'value' => $element->getCustomFieldsValue() == '' ? $element->getValue() : $element->getCustomFieldsValue()
                    ];
            }
            $rowSpacificationMainProduct[] = [
                'specification' => $item['specification_id'],
                'specification_name' => $item['name'],
                'type' => $item['custom_fields_type'],
                'items' => $values
            ];
        }
        /***** End Specifications ParentProduct ******/

        /***** Tags ParentProduct ******/
        $productsTags = $productTagRepository->getDataTags($product->getId());
        $products_tags = [];
        foreach ($productsTags as $item) {
            $products_tags[] = [
                'id' => $item->getTagId()->getId(),
                'name' => $item->getTagId()->getName()
            ];
        }
        /***** End Tags ParentProduct ******/

        /***** Subcategory ParentProduct ******/
        $product_subcategory_result = $productSubcategoryRepository->getIds($product->getId());
        $product_subcategorys = [];
        foreach ($product_subcategory_result as $key => $product_subcategory) {
            $product_subcategorys[$key] = $product_subcategory->getSubCategory()->getId();
        }
        /***** End Subcategory ParentProduct ******/

        /***** Specifications Data ******/
        $specifications_data = [];
        foreach ($specifications as $key => $item) {
            /** @var Specification $item */
            $specifications_data[] = [
                'id' => $item->getId(),
                'name' => $item->getName(),
            ];
        }
        /***** End Specifications Data ******/
        return $this->render('secure/crud_product/edit.html.twig', [
            'product' => $product,
            'form' => $form,
            'brands' => $brands,
            'tags' => $tags,
            'specifications' => $specifications_data,
            'childrens' => $childrens,
            'aux_arrar_childrens' => $array_childrens,
            'product_specifications' => $rowSpacificationMainProduct,
            'product_tags' => $products_tags,
            'product_subcategory' => $product_subcategorys,
            'main_image' => $product->getImage(),
            'variatns_specification' => $variatns_specification

        ]);
    }

    /**
     * @Route("/save-product-edit", name="seve_product")
     */
    public function SaveProduct(
        Request $request,
        EntityManagerInterface $em,
        ProductRepository $productRepository,
        ProductImagesRepository $productImagesRepository,
        SpecificationRepository $specificationRepository,
        ProductSubcategoryRepository $productSubcategoryRepository,
        ProductTagRepository $productTagRepository,
        ProductSpecificationRepository $productSpecificationRepository
    ): JsonResponse {
        // dd($request);

        $product = $productRepository->find($request->get('id_product'));

        // save imagen specifications and create specification
        // db.id_specification = $specification->specification
        // db.custom_field_type = $specification->type
        // db.value = $specification->$property->property
        // db.custom_field_value = $specification->$property->value
        $specifications = json_decode($request->get('specifications'));

        $ROOT_DIR = dirname(__DIR__, 3) . '' . \DIRECTORY_SEPARATOR . 'public' . '' . \DIRECTORY_SEPARATOR . 'uploads' . '' . \DIRECTORY_SEPARATOR . 'images';

        //clear some data
        $clearSpecification = $productSpecificationRepository->findBy([
            'productId' => $product->getId()
        ]);
        foreach ($clearSpecification as $item) {
            $em->remove($item);
        }

        foreach ($specifications as $key => $specification) {

            // add specification data
            foreach ($specification->items as $keyItems => $property) {

                $specificationObj = $specificationRepository->find($specification->specification);

                $newProductSpecification = new ProductSpecification($product, $specificationObj);
                $newProductSpecification->setCustomFieldsType($specification->type);
                $newProductSpecification->setValue($property->property);

                if ($specification->type == "imagen") {

                    $file = $request->files->get($property->value);
                    if ($file) {
                        move_uploaded_file(
                            $file,
                            $ROOT_DIR . \DIRECTORY_SEPARATOR . $property->value . '.png'
                        );
                        /* $file->move(
                            $ROOT_DIR,
                            $property->value . '.png'
                        ); */
                        $newProductSpecification->setCustomFieldsValue($_ENV['SITE_URL'] . '/uploads/images/' . $property->value . '.png');
                    } else
                        $newProductSpecification->setCustomFieldsValue($property->value);
                } else
                    $newProductSpecification->setCustomFieldsValue($property->value);

                $em->persist($newProductSpecification);
            }
        }

        // data general product
        $large_description = $request->get('large_description');
        $short_description = $request->get('short_description');
        $price = $request->get('price');
        $ofert_price = $request->get('ofert_price');
        $start_ofert_day = $request->get('start_ofert_day');
        $end_ofert_day = $request->get('end_ofert_day');

        if ($large_description) $product->setDescription($large_description);
        if ($short_description) $product->setShortDescription($short_description);
        if ($price) $product->setPrice($price);
        if ($ofert_price) $product->setOfferPrice(floatval($ofert_price));
        if ($start_ofert_day) $product->setOfferStartDate(new \DateTime($start_ofert_day));
        if ($end_ofert_day) $product->setOfferEndDate(new \DateTime($end_ofert_day));

        $em->persist($product);

        //refresh images
        $this->updateProductImages($product->getId(), $productImagesRepository, $em);
        $this->updateProductMainImage($product->getId(), $productRepository, $em);

        // variations        
        $list_variations = json_decode($request->get('list_variations'));

        foreach ($list_variations as $key => $variation) {

            // Variations products
            $variation_product = $productRepository->find($variation->id_producto);

            if ($variation->large_description) $variation_product->setDescription($variation->large_description);
            if ($variation->short_description) $variation_product->setShortDescription($variation->short_description);
            if ($variation->offer_price) $variation_product->setOfferPrice(floatval($variation->offer_price));
            if ($variation->offer_start_date) $variation_product->setOfferStartDate(new \DateTime($variation->offer_start_date));
            if ($variation->offer_end_date) $variation_product->setOfferEndDate(new \DateTime($variation->offer_end_date));
            if ($variation->dimensions) $variation_product->setDimensions($variation->dimensions);
            if ($variation->weight) $variation_product->setWeight($variation->weight);

            //clear some data
            $clearSpecification = $productSpecificationRepository->findBy([
                'productId' => $variation_product->getId()
            ]);
            foreach ($clearSpecification as $item) {
                $em->remove($item);
            }

            // specification of variations
            foreach ($variation->specification as $keyItems => $specification) {


                // add specification data

                $specificationObj = $specificationRepository->find($specification->specification);

                $newVariantProductSpecification = new ProductSpecification($variation_product, $specificationObj);
                $newVariantProductSpecification->setCustomFieldsType($specification->type);
                $newVariantProductSpecification->setValue($specification->property);

                if ($specification->type == "imagen") {
                    $newVariantProductSpecification->setCustomFieldsValue($_ENV['SITE_URL'] . '/uploads/images/' . $property->property . '.png');
                } else
                    $newVariantProductSpecification->setCustomFieldsValue($specification->value);

                $em->persist($newVariantProductSpecification);

                //refresh images
                $this->updateProductImages($variation->id_producto, $productImagesRepository, $em);
                $this->updateProductMainImage($variation->id_producto, $productRepository, $em);
            }
        }

        //save categoris array_element{id_li}

        $existSubProduct = $productSubcategoryRepository->findBy(['productId' => $product->getId()]);
        foreach ($existSubProduct as $item) {
            $em->remove($item);
        }

        $array_categorys = json_decode($request->get('array_element'));
        foreach ($array_categorys as $categoryId) {
            $subcategory = $em->getRepository(Subcategory::class)->find($categoryId);
            $newProductSubc = new ProductSubcategory($product, $subcategory);
            $em->persist($newProductSubc);
        }

        //save tags array_tag

        $existTagsProduct = $productTagRepository->findBy(['productId' => $product->getId()]);
        foreach ($existTagsProduct as $item) {
            $em->remove($item);
        }

        $array_tags = json_decode($request->get('array_tag'));
        // dd($array_tags);
        foreach ($array_tags as $tagsId) {
            $tag = $em->getRepository(Tag::class)->find($tagsId);
            $newTagProduct = new ProductTag();
            $newTagProduct->setProductId($product);
            $newTagProduct->setTagId($tag);
            $em->persist($newTagProduct);
        }

        $em->flush();

        return $this->json([]);
    }

    /***
     * @param ProductImagesRepository $productImagesRepository
     * @param EntityManagerInterface $em
     * @param int $id_product
     * @description Pasa todas las imagenes secundarias guardadas en la entidad ProductImages del estado de nuevas a estables, para usar en el front.
     */
    public function updateProductImages(int $id_product, ProductImagesRepository $productImagesRepository, EntityManagerInterface $em)
    {
        $productImages = $productImagesRepository->findBy(['productId' => $id_product, 'new' => true]);
        foreach ($productImages as $pImages) {
            $pImages->setNew(null);
            $em->persist($pImages);
        }
        $em->flush();
    }

    /***
     * @param ProductRepository $productRepository
     * @param EntityManagerInterface $em
     * @param int $id_product
     * @description Actualizar la imagen del producto por la imgen temporal
     */
    public function updateProductMainImage(int $id_product, ProductRepository $productRepository, EntityManagerInterface $em)
    {
        $objProduct = $productRepository->find($id_product);
        if ($objProduct->getNewImage()) {
            $objProduct->setImage($objProduct->getNewImage());
            $em->persist($objProduct);
            $em->flush();
        }
    }
}
