<?php

namespace App\Controller\Api\_3pl;

use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/3pl")
 */
class Api3plProductController extends AbstractController
{
    /**
     * @Route("/product/{product_3pl_id}", name="api3pl_product",methods={"GET","PATCH"})
     */
    public function product(
        ProductRepository $productRepository,
        Request $request,
        EntityManagerInterface $em,
        $product_3pl_id
    ): Response {
        $product = $productRepository->findOneBy(['id3pl' => $product_3pl_id]);
        if ($product) {
            
            switch ($request->getMethod()) {
                case 'GET':
                    return $this->json(
                        $product->getFullDataProduct(),
                        Response::HTTP_OK,
                        ['Content-Type' => 'application/json']
                    );
                // case 'PATCH':
                //     $body = $request->getContent();
                //     $data = json_decode($body, true);

                //     //Busco los objetos de cada relacion
                //     $customer_type_role = $customersTypesRolesRepository->findOneBy(['id' => @$data['customer_type_role']]) ?: null;
                //     $country_phone_code = $countriesRepository->findOneBy(['id' => @$data['country_phone_code']]) ?: null;
                //     $status_type = $customerStatusTypeRepository->findOneBy(['id' => @$data['status']]) ?: null;
                //     $gender_type = $genderTypeRepository->findOneBy(['id' => @$data['gender_type']]) ?: null;


                //     // seteo valores de los objetos de relacion al objeto
                //     if ($customer_type_role) $customer->setCustomerTypeRole($customer_type_role);
                //     if ($country_phone_code) $customer->setCountryPhoneCode($country_phone_code);
                //     if ($status_type) $customer->setStatus($status_type);
                //     if ($gender_type) $customer->setGenderType($gender_type);



                //     //creo el formulario para hacer las validaciones    
                //     $form = $this->createForm(RegisterCustomerApiType::class, $customer);
                //     $form->submit($data, false);

                //     if (!$form->isValid()) {
                //         $error_forms = $this->getErrorsFromForm($form);
                //         return $this->json(
                //             [
                //                 'message' => 'Error de validación.',
                //                 'validation' => $error_forms
                //             ],
                //             Response::HTTP_BAD_REQUEST,
                //             ['Content-Type' => 'application/json']
                //         );
                //     }

                //     try {
                //         $em->persist($customer);
                //         $em->flush();
                //     } catch (\Exception $e) {
                //         return $this->json(
                //             [
                //                 'message' => 'Error al intentar grabar en la base de datos.',
                //                 'validation' => ['others' => $e->getMessage()]
                //             ],
                //             Response::HTTP_UNPROCESSABLE_ENTITY,
                //             ['Content-Type' => 'application/json']
                //         );
                //     }

                //     return $this->json(
                //         [
                //             'message' => 'Cliente actualizado con éxito.',
                //             'customer_updated' => $customer->getCustomerTotalInfo()
                //         ],
                //         Response::HTTP_CREATED,
                //         ['Content-Type' => 'application/json']
                //     );
                //     break;
            }
        }
        //si no encontro ni customer en methodo get o customer en post retorno not found 
        return $this->json(
            ['message' => 'Not found'],
            Response::HTTP_NOT_FOUND,
            ['Content-Type' => 'application/json']
        );
    }
}
