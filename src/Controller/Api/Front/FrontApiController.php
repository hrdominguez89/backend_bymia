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

/**
 * @Route("/api/front")
 */
class FrontApiController extends AbstractController
{
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
     * @Route("/register", name="api_register_customer", methods={"POST"})
     */
    public function register(EntityManagerInterface $em, Request $request, CustomersTypesRolesRepository $customersTypesRolesRepository, CountriesRepository $countriesRepository): Response
    {
        $body = $request->getContent();
        $data = json_decode($body, true);

        $country = $countriesRepository->find($data['country_code_cel_phone']);
        $customerTypeRole = $customersTypesRolesRepository->find($data['customer_type_role']);

        
        $customer = new Customer();
        $customer->setCountryCodeCelPhone($country->getPhonecode());
        $customer->setCustomerTypeRole($customerTypeRole);
        
        $form = $this->createForm(RegisterCustomerApiType::class, $customer);
        $form->submit($data);

        if (!$form->isValid()) {
            $error_forms = $this->getErrorsFromForm($form);
            return $this->json(
                $error_forms,
                Response::HTTP_BAD_REQUEST,
                ['Content-Type' => 'application/json']
            );
        }
        $em->persist($customer);
        $em->flush();

        return $this->json(
            ['message' => 'Usuario creado'],
            Response::HTTP_CREATED,
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
