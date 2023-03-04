<?php

namespace App\Controller\Api\Front;

use App\Entity\Customer;
use App\Form\RegisterCustomerApiType;
use App\Repository\AboutUsRepository;
use App\Repository\CountriesRepository;
use App\Repository\CoverImageRepository;
use App\Repository\CustomersTypesRolesRepository;
use App\Repository\FaqsRepository;
use App\Repository\TopicsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Helpers\EnqueueEmail;
use App\Constants\Constants;
use App\Form\ContactType;
use App\Form\ListPriceType;
use App\Helpers\SendCustomerToCrm;
use App\Repository\AdvertisementsRepository;
use App\Repository\BrandRepository;
use App\Repository\BrandsSectionsRepository;
use App\Repository\CategoryRepository;
use App\Repository\CommunicationStatesBetweenPlatformsRepository;
use App\Repository\CustomerRepository;
use App\Repository\CustomerStatusTypeRepository;
use App\Repository\ProductRepository;
use App\Repository\RegistrationTypeRepository;
use App\Repository\SectionsHomeRepository;
use App\Repository\TagRepository;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;
use Symfony\Component\Uid\Uuid;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;

/**
 * @Route("/api/front")
 */
class FrontApiController extends AbstractController
{


    /**
     * @Route("/searchTypeList", name="api_search_type_list",methods={"GET"})
     */
    public function searchTypeList(CategoryRepository $categoryRepository, TagRepository $tagRepository, BrandRepository $brandRepository): Response
    {

        $principalCategories = $categoryRepository->getPrincipalCategories();
        $principalBrands = $brandRepository->getPrincipalBrands();
        $principalTags = $tagRepository->getPrincipalTags();

        $searchList = [
            [
                "title" => 'Todos',
                "slug" => '',
                "items" => [],
            ],
            [
                "title" => 'Categorías',
                "slug" => "c=",
                "items" => $principalCategories
            ],
            [
                "title" => 'Marcas',
                "slug" => "b=",
                "items" => $principalBrands
            ],
            [
                "title" => 'Otros',
                "slug" => "t=",
                "items" => $principalTags
            ],
        ];

        return $this->json(
            $searchList,
            Response::HTTP_OK,
            ['Content-Type' => 'application/json']
        );
    }


    /**
     * @Route("/login", name="api_login",methods={"POST"})
     */
    public function login(Request $request, CustomerRepository $customerRepository, PasswordHasherFactoryInterface $passwordHasherFactoryInterface, JWTTokenManagerInterface $jwtManager): Response
    {
        $body = $request->getContent();
        $data = json_decode($body, true);

        if (!(@$data['email'] && @$data['password'])) {
            $validation = [];
            if (!@$data['email']) {
                $validation["email"] =  "Debe ingresar una dirección de correo.";
            }
            if (!@$data['password']) {
                $validation["password"] =  "El campo password es obligatorio.";
            }
            return $this->json(
                [
                    "status" => false,
                    'message' => 'Error de validacion',
                    'validation' => $validation
                ],
                Response::HTTP_BAD_REQUEST,
                ['Content-Type' => 'application/json']
            );
        }

        try {
            $customer = $customerRepository->findOneBy(["email" => @$data['email']]);
            if (!$passwordHasherFactoryInterface->getPasswordHasher($customer)->verify($customer->getPassword(), $data['password'])) {
                throw new Exception();
            }
        } catch (\Exception $e) {
            return $this->json(
                [
                    "status" => false,
                    'message' => 'Usuario y/o password incorrectos.',
                ],
                Response::HTTP_UNAUTHORIZED,
                ['Content-Type' => 'application/json']
            );
        }

        $jwt = $jwtManager->create($customer);



        return new JsonResponse([
            "status" => true,
            "token" => $jwt,
            "token_type" => "Bearer",
            "expires_in" => (int)$_ENV['JWT_TOKEN_TTL'],
            "user_data" => [
                "id" => (int)$customer->getId(),
                "name" => $customer->getName(),
                "image" => $customer->getImage(),
                "email" => $customer->getEmail(),
                "customer_type_role" => $customer->getCustomerTypeRole() ? (int)$customer->getCustomerTypeRole()->getId() : null,
                "country_phone_code" =>  $customer->getCountryPhoneCode() ? (int)$customer->getCountryPhoneCode()->getId() : null,
                "gender_type" => $customer->getGenderType() ? (int)$customer->getGenderType()->getId() : null,
                "wish_list" => [],
                "shop_cart" => [],
                "cel_phone" => $customer->getCelPhone(),
                "date_of_birth" => $customer->getDateOfBirth() ? $customer->getDateOfBirth()->format('Y-m-d') : null,
            ]
        ]);
    }

    /**
     * @Route("/contact", name="api_contanct",methods={"POST"})
     */
    public function contact(EnqueueEmail $queue, Request $request, CountriesRepository $countriesRepository): Response
    {
        $body = $request->getContent();
        $data = json_decode($body, true);


        try {
            $data['country'] = $countriesRepository->findOneBy(["id" => @$data['country_id']]);
        } catch (\Exception $e) {
            return $this->json(
                [
                    'message' => 'Error de validación',
                    'validation' => ['country_id' => 'No fue posible encontrar un pais con el codigo indicado.']
                ],
                Response::HTTP_BAD_REQUEST,
                ['Content-Type' => 'application/json']
            );
        }


        $form = $this->createForm(ContactType::class);
        $form->submit($data, false);

        if (!$form->isValid()) {
            $error_forms = $this->getErrorsFromForm($form);
            return $this->json(
                [
                    'message' => 'Error de validación',
                    'validation' => $error_forms
                ],
                Response::HTTP_BAD_REQUEST,
                ['Content-Type' => 'application/json']
            );
        }

        //queue the email
        $id_email = $queue->enqueue(
            Constants::EMAIL_TYPE_CONTACT, //tipo de email
            Constants::EMAIL_CONTACT, //email destinatario
            [ //parametros
                "name" => $data['name'],
                "phone" => $data['country']->getPhonecode() . $data['phone'],
                'email' => $data['email'],
                "message" => $data['message'],
            ]
        );

        //Intento enviar el correo encolado
        $queue->sendEnqueue($id_email);


        return $this->json(
            ['message' => 'Formulario de contacto enviado correctamente.'],
            Response::HTTP_CREATED,
            ['Content-Type' => 'application/json']
        );
    }

    /**
     * @Route("/listPrice", name="api_list_price",methods={"POST"})
     */
    public function listPrice(EnqueueEmail $queue, Request $request): Response
    {
        $body = $request->getContent();
        $data = json_decode($body, true);

        $form = $this->createForm(ListPriceType::class);
        $form->submit($data, false);

        if (!$form->isValid()) {
            $error_forms = $this->getErrorsFromForm($form);
            return $this->json(
                [
                    'message' => 'Error de validación',
                    'validation' => $error_forms
                ],
                Response::HTTP_BAD_REQUEST,
                ['Content-Type' => 'application/json']
            );
        }

        //queue the email
        $id_email = $queue->enqueue(
            Constants::EMAIL_TYPE_PRICE_LIST, //tipo de email
            Constants::EMAIL_PRICE_LIST, //email destinatario
            [ //parametros
                'email' => $data['email'],
            ]
        );

        //Intento enviar el correo encolado
        $queue->sendEnqueue($id_email);


        return $this->json(
            ['message' => 'Solicitud enviada con exito.'],
            Response::HTTP_CREATED,
            ['Content-Type' => 'application/json']
        );
    }

    /**
     * @Route("/products/search?c=celulares&b=samsung&k=celular&l=4&i= samsung a20", name="api_products_search",methods={"GET"})
     */
    public function productsSearch(
        CategoryRepository $categoryRepository,
        TagRepository $tagRepository,
        BrandRepository $brandRepository,
        ProductRepository $productRepository,
        Request $request
    ): Response {
        // $category = $categoryRepository->find($categoryId); //reemplazar para qe busque categorias visibles con id3pl.
        // Definicion de filtros
        // k=keyword
        // c=categorias,
        // b=marcas,
        // t=etiquetas,
        // i=indice,
        // l=limit,
        // [
        //     id,
        //     name,
        //     brand,
        //     price,
        //     old_price,
        //     reviews,
        //     rating,
        //     image
        // ]

        $limit = $request->query->getInt('l', 4);
        $index = $request->query->getInt('i', 0) * $limit;

        $keyword = $request->query->get('k', null);
        $category = $request->query->get('c', null) ? $categoryRepository->find($request->query->get('c')) : null;
        $brand = $request->query->get('b', null) ? $brandRepository->find($request->query->get('b')) : null;
        $tag = $request->query->get('t', null) ? $tagRepository->find($request->query->get('t')) : null;

        $filters = [];
        if ($keyword) {
            $filters[] = [
                "method" => 'LIKE',
                "parameter" => $keyword,
            ];
        }
        if ($category) {
            $filters[] = [
                "method" => '=',
                "parameter" => $category,
            ];
        }
        if ($brand) {
            $filters[] = [
                "method" => '=',
                "parameter" => $brand,
            ];
        }
        if ($tag) {
            $filters[] = [
                "method" => '=',
                "parameter" => $tag,
            ];
        }

        $products = $productRepository->findProductByFilters($filters, $limit, $index);
        dd($products);



        return $this->json(
            ['a' => 'b'],
            Response::HTTP_OK,
            ['Content-Type' => 'application/json']
        );

        return $this->json(
            ['message' => 'Not found'],
            Response::HTTP_NOT_FOUND,
            ['Content-Type' => 'application/json']
        );
    }

    /**
     * @Route("/products/tag/{slug_tag}", name="api_products_tag",methods={"GET"})
     */
    public function productsTag($slug_tag, Request $request, CategoryRepository $categoryRepository, TagRepository $tagRepository, ProductRepository $productRepository): Response
    {
        if ($slug_tag == 'random') {
        } else {
            $tag = $tagRepository->findTagVisibleBySlug($slug_tag);
            if ($tag) {

                $categoryLaptops = $categoryRepository->findOneBySlug('laptops');
                $categoryCelulares = $categoryRepository->findOneBySlug('celulares');
                $categoryAudio = $categoryRepository->findOneBySlug('audio');

                $limit = $request->query->getInt('l', 4);
                $index = $request->query->getInt('i', 0) * $limit;

                $productsLaptops = $productRepository->findProductsVisibleByTag($tag, $categoryLaptops, $limit, $index);
                $productsCelulares = $productRepository->findProductsVisibleByTag($tag, $categoryCelulares, $limit, $index);
                $productsAudio = $productRepository->findProductsVisibleByTag($tag, $categoryAudio, $limit, $index);


                //productos por placas de video
                $productsByCategory = [];

                foreach ($productsAudio as $productAudio) {
                    $productsByCategory[] = $productAudio->getBasicDataProduct();
                }

                $products[] = [
                    "category" => "Audio",
                    "products" => $productsByCategory
                ];

                // productos por categoria laptops
                $productsByCategory = [];

                foreach ($productsLaptops as $productLaptop) {
                    $productsByCategory[] = $productLaptop->getBasicDataProduct();
                }

                $products[] = [
                    "category" => 'Laptops',
                    "products" => $productsByCategory
                ];

                // productos por categoria celulares

                $productsByCategory = [];

                foreach ($productsCelulares as $productCelular) {
                    $productsByCategory[] = $productCelular->getBasicDataProduct();
                }

                $products[] = [
                    "category" => "Celulares",
                    "products" => $productsByCategory
                ];



                return $this->json(
                    $products,
                    Response::HTTP_OK,
                    ['Content-Type' => 'application/json']
                );
            } else {
                return $this->json(
                    ['message' => 'Not found'],
                    Response::HTTP_NOT_FOUND,
                    ['Content-Type' => 'application/json']
                );
            }
        }
    }


    /**
     * @Route("/products/sections", name="api_products_sections",methods={"GET"})
     */
    public function sections(Request $request, ProductRepository $productRepository, SectionsHomeRepository $sectionsHomeRepository): Response
    {
        //traigo la linea 1 de secciones.
        $sections = $sectionsHomeRepository->findAll()[0];

        if ($sections) {

            $products_by_sections = [];

            $limit = $request->query->getInt('l', 4);
            $index = $request->query->getInt('i', 0) * $limit;

            for ($i = 1; $i <= 4; $i++) {
                //creo variables para luego utilizarlas como funciones para poder traerme los datos de cada seccion
                $getTitleSectionN = "getTitleSection" . $i;
                $getTagSectionN = "getTagSection" . $i;

                //genero un array con cada seccion con las categorias de cada seccion
                $products_by_sections[] =
                    [
                        "title" => $sections->$getTitleSectionN(),
                        "categories" => []
                    ];

                for ($j = 1; $j <= 3; $j++) {
                    //genero variable para utilizarla luego como funcion
                    $getCategoryNSectionN = "getCategory" . $j . "Section" . $i;

                    $products = $productRepository->findProductsVisibleByTag($sections->$getTagSectionN(), $sections->$getCategoryNSectionN(), $limit, $index);

                    $productsByCategory = [];

                    foreach ($products as $product) {
                        $productsByCategory[] = $product->getBasicDataProduct();
                    }

                    $products_by_sections[$i - 1]['categories'][] =
                        [
                            "category" => $sections->$getCategoryNSectionN()->getName(),
                            "products" => $productsByCategory
                        ];
                }
            }

            return $this->json(
                $products_by_sections,
                Response::HTTP_OK,
                ['Content-Type' => 'application/json']
            );
        } else {
            return $this->json(
                ['message' => 'Not found'],
                Response::HTTP_NOT_FOUND,
                ['Content-Type' => 'application/json']
            );
        }
    }

    /**
     * @Route("/sliders", name="api_sliders",methods={"GET"})
     */
    public function sliders(CoverImageRepository $coverImageRepository): Response
    {

        $sliders = $coverImageRepository->findCoverImage();

        return $this->json(
            $sliders,
            Response::HTTP_OK,
            ['Content-Type' => 'application/json']
        );
    }

    /**
     * @Route("/banners", name="api_banners",methods={"GET"})
     */
    public function banners(AdvertisementsRepository $advertisementsRepository): Response
    {

        $banners = $advertisementsRepository->find(1);

        return $this->json(
            $banners->getBanners(),
            Response::HTTP_OK,
            ['Content-Type' => 'application/json']
        );
    }

    /**
     * @Route("/brands", name="api_brands",methods={"GET"})
     */
    public function brands(BrandsSectionsRepository $brandsSectionsRepository): Response
    {

        $brands = $brandsSectionsRepository->find(1);

        return $this->json(
            $brands->getBrands(),
            Response::HTTP_OK,
            ['Content-Type' => 'application/json']
        );
    }

    /**
     * @Route("/about-us", name="api_about_us",methods={"GET"})
     */
    public function aboutUs(AboutUsRepository $aboutUsRepository): Response
    {

        $aboutUs = $aboutUsRepository->findAboutUsDescription();
        return $this->json(
            $aboutUs,
            Response::HTTP_OK,
            ['Content-Type' => 'application/json']
        );
    }

    /**
     * @Route("/faqs", name="api_faqs",methods={"GET"})
     */
    public function faqs(FaqsRepository $faqsRepository, TopicsRepository $topicsRepository): Response
    {
        $topics = $topicsRepository->getTopics();
        for ($i = 0; $i < count($topics); $i++) {
            $topics[$i]['faqs'] = $faqsRepository->getFaqsByTopic($topics[$i]['topic_id']);
        }
        return $this->json(
            $topics,
            Response::HTTP_OK,
            ['Content-Type' => 'application/json']
        );
    }

    /**
     * @Route("/customer-type", name="api_customer_type",methods={"GET"})
     */
    public function customerType(CustomersTypesRolesRepository $customersTypesRolesRepository): Response
    {

        $customersTypeRoles = $customersTypesRolesRepository->findCustomerTypesRole();
        return $this->json(
            $customersTypeRoles,
            Response::HTTP_OK,
            ['Content-Type' => 'application/json']
        );
    }

    /**
     * @Route("/country-code", name="api_country_code",methods={"GET"})
     */
    public function countryCode(CountriesRepository $countriesRepository): Response
    {

        $countries = $countriesRepository->getCountries();
        return $this->json(
            $countries,
            Response::HTTP_OK,
            ['Content-Type' => 'application/json']
        );
    }

    /**
     * @Route("/categoriesList", name="api_categories_list",methods={"GET"})
     */
    public function categoriesList(CategoryRepository $categoryRepository): Response
    {

        $categories = $categoryRepository->getVisibleCategories();

        $categoryObject = [];

        foreach ($categories as $category) {
            array_push(
                $categoryObject,
                [
                    "id" => $category->getId(),
                    "name" => $category->getName(),
                    "description_es" => $category->getDescriptionEs(),
                    "description_en" => $category->getDescriptionEn(),
                    "principal" => $category->getPrincipal(),
                    "image" => $category->getImage(),
                    "slug" => 'c=' . $category->getId(),
                ]
            );
        }
        return $this->json(
            $categoryObject,
            Response::HTTP_OK,
            ['Content-Type' => 'application/json']
        );
    }

    /**
     * @Route("/register", name="api_register_customer", methods={"POST"})
     */
    public function register(

        EntityManagerInterface $em,
        Request $request,
        CustomerStatusTypeRepository $customerStatusTypeRepository,
        RegistrationTypeRepository $registrationTypeRepository,
        CustomersTypesRolesRepository $customersTypesRolesRepository,
        CountriesRepository $countriesRepository,
        CommunicationStatesBetweenPlatformsRepository $communicationStatesBetweenPlatformsRepository,
        EnqueueEmail $queue,
        SendCustomerToCrm $sendCustomerToCrm

    ): Response {

        $body = $request->getContent();
        $data = json_decode($body, true);

        //find relational objects
        $country = $countriesRepository->find($data['country_phone_code']);
        $customer_type_role = $customersTypesRolesRepository->find($data['customer_type_role']);
        $status_customer = $customerStatusTypeRepository->find(Constants::CUSTOMER_STATUS_PENDING);
        $registration_type = $registrationTypeRepository->find(Constants::REGISTRATION_TYPE_WEB);
        $status_sent_crm = $communicationStatesBetweenPlatformsRepository->find(Constants::CBP_STATUS_PENDING);


        //set Customer data
        $customer = new Customer();
        $customer->setCountryPhoneCode($country)
            ->setCustomerTypeRole($customer_type_role)
            ->setVerificationCode(Uuid::v4())
            ->setStatus($status_customer)
            ->setRegistrationType($registration_type)
            ->setRegistrationDate(new \DateTime)
            ->setStatusSentCrm($status_sent_crm);


        $form = $this->createForm(RegisterCustomerApiType::class, $customer);
        $form->submit($data, false);

        if (!$form->isValid()) {
            $error_forms = $this->getErrorsFromForm($form);
            return $this->json(
                [
                    'message' => 'Error de validación',
                    'validation' => $error_forms
                ],
                Response::HTTP_BAD_REQUEST,
                ['Content-Type' => 'application/json']
            );
        }
        $em->persist($customer);
        $em->flush();

        //queue the email
        $id_email = $queue->enqueue(
            Constants::EMAIL_TYPE_VALIDATION, //tipo de email
            $customer->getEmail(), //email destinatario
            [ //parametros
                'name' => $customer->getName(),
                'url_front_validation' => $_ENV['FRONT_URL'] . $_ENV['FRONT_VALIDATION'] . '?code=' . $customer->getVerificationCode() . '&id=' . $customer->getId(),
            ]
        );

        //Intento enviar el correo encolado
        $queue->sendEnqueue($id_email);
        //envio por helper los datos del cliente al crm
        $sendCustomerToCrm->SendCustomerToCrm($customer);

        return $this->json(
            ['message' => 'Usuario creado'],
            Response::HTTP_CREATED,
            ['Content-Type' => 'application/json']
        );
    }

    /**
     * @Route("/validate", name="api_validate_customer", methods={"POST"})
     */
    public function validate(

        EntityManagerInterface $em,
        Request $request,
        CustomerRepository $customerRepository,
        CustomerStatusTypeRepository $customerStatusTypeRepository,
        CommunicationStatesBetweenPlatformsRepository $communicationStatesBetweenPlatformsRepository,
        EnqueueEmail $queue

    ): Response {

        $body = $request->getContent();
        $data = json_decode($body, true);

        //get Customer data
        $customer = $customerRepository->findOneBy(['id' => $data['id']]);

        if (!$customer || (!$customer->getVerificationCode() || !$customer->getVerificationCode()->equals(Uuid::fromString($data['code'])))) {
            return $this->json(
                ['message' => 'No fue posible encontrar el usuario o el enlace expiró.'],
                Response::HTTP_NOT_FOUND,
                ['Content-Type' => 'application/json']
            );
        }

        if ($customer->getStatus()->getId() !== Constants::CUSTOMER_STATUS_PENDING) {
            return $this->json(
                ['message' => 'Su cuenta ya se encuentra validada.'],
                Response::HTTP_OK,
                ['Content-Type' => 'application/json']
            );
        }
        //find relational objects
        $status_sent_crm = $communicationStatesBetweenPlatformsRepository->find(Constants::CBP_STATUS_PENDING);
        $status_customer = $customerStatusTypeRepository->find(Constants::CUSTOMER_STATUS_VALIDATED);

        $customer->setStatus($status_customer)
            ->setStatusSentCrm($status_sent_crm)
            ->setAttemptsSendCrm(0)
            ->setVerificationCode(null);

        $em->persist($customer);
        $em->flush();

        //queue the email
        $id_email = $queue->enqueue(
            Constants::EMAIL_TYPE_WELCOME, //tipo de email
            $customer->getEmail(), //email destinatario
            [ //parametros
                'name' => $customer->getName(),
                'url_front_login' => $_ENV['FRONT_URL'] . $_ENV['FRONT_LOGIN'],
            ]
        );

        //Intento enviar el correo encolado
        $queue->sendEnqueue($id_email);

        return $this->json(
            ['message' => 'Cuenta de correo verificada con éxito.'],
            Response::HTTP_ACCEPTED,
            ['Content-Type' => 'application/json']
        );
    }

    private function getErrorsFromForm(FormInterface $form)
    {
        $errors = array();
        foreach ($form->getErrors() as $error) {
            $errors[] = $error->getMessage();
        }
        foreach ($form->all() as $childForm) {
            if ($childForm instanceof FormInterface) {
                if ($childErrors = $this->getErrorsFromForm($childForm)) {
                    $errors[$childForm->getName()] = $childErrors;
                }
            }
        }
        return $errors;
    }
}
