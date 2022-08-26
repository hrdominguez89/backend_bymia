<?php

namespace App\Controller\Secure;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Countries;
use App\Form\CountriesType;
use App\Repository\CitiesRepository;
use App\Repository\CountriesRepository;
use App\Repository\StatesRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\Country;

/**
 * @Route("/world")
 */
class WorldController extends AbstractController
{
    /**
     * @Route("/", name="secure_crud_world_index")
     */
    public function index(CountriesRepository $countriesRepository): Response
    {
        $data['title'] = 'Paises';
        $data['files_js'] = array('table_full_buttons.js?v=' . rand());
        $data['countries'] = $countriesRepository->listCountries();
        return $this->render('secure/world/abm_countries.html.twig', $data);
    }

    /**
     * @Route("/new", name="secure_crud_world_new_country")
     */
    public function newCountry(Request $request): Response
    {
        $data['title'] = 'Paises';
        $data['files_js'] = array('table_full_buttons.js?v=' . rand());
        $data['country'] = new Countries;
        $form = $this->createForm(CountriesType::class, $data['country']);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
        }
        $data['form'] = $form;
        return $this->renderForm('secure/world/form_country.html.twig', $data);
    }

    // public function new(Request $request): Response
    // {
    //     $data['title'] = "Nuevo cliente";
    //     $data['customer'] = new Customer();
    //     $form = $this->createForm(CustomerType::class, $data['customer']);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $data['customer']->setStatus(true);
    //         $data['customer']->setPassword($_ENV['PWD_NEW_USER']);
    //         if ($form->get('customer_type_role')->getData()->getId() == 2) {
    //             $data['customer']->setLastname(null);
    //             $data['customer']->setGenderType(null);
    //             $data['customer']->setDateOfBirth(null);
    //         }

    //         $entityManager = $this->getDoctrine()->getManager();
    //         $entityManager->persist($data['customer']);
    //         $entityManager->flush();

    //         return $this->redirectToRoute('secure_crud_customer_index', [], Response::HTTP_SEE_OTHER);
    //     }

    //     $data['form'] = $form;
    //     $data['files_js'] = array(
    //         'customers/customers.js?v=' . rand(),
    //     );
    //     return $this->renderForm('secure/crud_customer/customer_form.html.twig', $data);
    // }

    /**
     * @Route("/{country_id}/edit", name="secure_crud_world_edit_country")
     */
    public function editCountry($country_id, Request $request, CountriesRepository $countriesRepository): Response
    {
        $data['title'] = 'Paises';
        $data['files_js'] = array('table_full_buttons.js?v=' . rand());
        $data['country'] = $countriesRepository->find($country_id);
        $form = $this->createForm(CountriesType::class, $data['country']);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
        }
        $data['form'] = $form;
        return $this->renderForm('secure/world/form_country.html.twig', $data);
    }


    /**
     * @Route("/{country_id}/states", name="secure_crud_states_index")
     */
    public function indexStates($country_id, CountriesRepository $countriesRepository, StatesRepository $statesRepository): Response
    {
        $data['title'] = 'Estados/Provincias';
        $data['files_js'] = array('table_full_buttons.js?v=' . rand());
        $data['country'] = $countriesRepository->findOneBy(['id' => $country_id]);
        $data['states'] = $statesRepository->findStatesByCountryId($country_id);
        return $this->render('secure/world/abm_states.html.twig', $data);
    }

    /**
     * @Route("/{country_id}/state/new", name="secure_crud_state_new")
     */
    public function newState(CountriesRepository $countriesRepository): Response
    {
        $data['title'] = 'Paises';
        $data['files_js'] = array('table_full_buttons.js?v=' . rand());
        $data['countries'] = $countriesRepository->findOneBy(['name' => 'Argentina']);
        // dump($data['countries']);die();
        return $this->render('secure/world/abm_countries.html.twig', $data);
    }

    /**
     * @Route("/{country_id}/state/{state_id}/edit", name="secure_crud_state_edit")
     */
    public function editState(CountriesRepository $countriesRepository): Response
    {
        $data['title'] = 'Paises';
        $data['files_js'] = array('table_full_buttons.js?v=' . rand());
        $data['countries'] = $countriesRepository->findOneBy(['name' => 'Argentina']);
        // dump($data['countries']);die();
        return $this->render('secure/world/abm_countries.html.twig', $data);
    }

    /**
     * @Route("/{country_id}/state/{state_id}/cities", name="secure_crud_cities_index")
     */
    public function indexCities($country_id, $state_id, CountriesRepository $countriesRepository, StatesRepository $statesRepository, CitiesRepository $citiesRepository): Response
    {
        $data['title'] = 'Ciudades';
        $data['files_js'] = array('table_full_buttons.js?v=' . rand());
        $data['country'] = $countriesRepository->findOneBy(['id' => $country_id]);
        $data['state'] = $statesRepository->findOneBy(['id' => $state_id]);
        $data['cities'] = $citiesRepository->findCitiesByStateId($state_id);
        return $this->render('secure/world/abm_cities.html.twig', $data);
    }

    /**
     * @Route("/{country_id}/state/{state_id}/city/new", name="secure_crud_city_new")
     */
    public function newCity(CountriesRepository $countriesRepository): Response
    {
        $data['title'] = 'Paises';
        $data['files_js'] = array('table_full_buttons.js?v=' . rand());
        $data['countries'] = $countriesRepository->findOneBy(['name' => 'Argentina']);
        // dump($data['countries']);die();
        return $this->render('secure/world/abm_countries.html.twig', $data);
    }

    /**
     * @Route("/{country_id}/state/{state_id}/city/{city_id}/edit", name="secure_crud_city_edit")
     */
    public function editCity(CountriesRepository $countriesRepository): Response
    {
        $data['title'] = 'Paises';
        $data['files_js'] = array('table_full_buttons.js?v=' . rand());
        $data['countries'] = $countriesRepository->findOneBy(['name' => 'Argentina']);
        // dump($data['countries']);die();
        return $this->render('secure/world/abm_countries.html.twig', $data);
    }
}
