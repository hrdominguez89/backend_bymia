<?php

namespace App\Controller\Secure;

use App\Entity\Cities;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Countries;
use App\Entity\States;
use App\Form\CitiesType;
use App\Form\CountriesType;
use App\Form\StatesType;
use App\Repository\CitiesRepository;
use App\Repository\CountriesRepository;
use App\Repository\StatesRepository;
use App\Repository\SubregionTypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

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
        $data['breadcrumbs'] = array(
            array('active' => true, 'title' => $data['title'])
        );
        $data['files_js'] = array('table_full_buttons.js?v=' . rand());
        $data['countries'] = $countriesRepository->listCountries();
        return $this->render('secure/world/abm_countries.html.twig', $data);
    }

    /**
     * @Route("/new", name="secure_crud_world_new_country")
     */
    public function newCountry(EntityManagerInterface $em, Request $request, SubregionTypeRepository $subregionTypeRepository): Response
    {
        $data['title'] = 'Nuevo país';
        $data['breadcrumbs'] = array(
            array('path' => 'secure_crud_world_index', 'title' => 'Paises'),
            array('active' => true, 'title' => $data['title'])
        );
        $data['files_js'] = array(
            'world/country.js?v=' . rand(),
        );
        $data['country'] = new Countries;
        $form = $this->createForm(CountriesType::class, $data['country']);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data['country']->setSubregionType($subregionTypeRepository->find($request->get('countries')['subregion']));

            $entityManager = $em;
            $entityManager->persist($data['country']);
            $entityManager->flush();

            return $this->redirectToRoute('secure_crud_world_index');
        }
        $data['form'] = $form;
        return $this->renderForm('secure/world/form_country.html.twig', $data);
    }


    /**
     * @Route("/{country_id}/edit", name="secure_crud_world_edit_country")
     */
    public function editCountry(EntityManagerInterface $em, $country_id, Request $request, CountriesRepository $countriesRepository, SubregionTypeRepository $subregionTypeRepository): Response
    {
        $data['title'] = 'Editar País';
        $data['breadcrumbs'] = array(
            array('path' => 'secure_crud_world_index', 'title' => 'Paises'),
            array('active' => true, 'title' => $data['title'])
        );
        $data['files_js'] = array(
            'world/country.js?v=' . rand(),
        );
        $data['country'] = $countriesRepository->find($country_id);
        $form = $this->createForm(CountriesType::class, $data['country']);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data['country']->setSubregionType($subregionTypeRepository->find($request->get('countries')['subregion']));

            $entityManager = $em;
            $entityManager->persist($data['country']);
            $entityManager->flush();

            return $this->redirectToRoute('secure_crud_world_index');
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
        $data['country'] = $countriesRepository->find($country_id);
        $data['states'] = $statesRepository->findStatesByCountryId($country_id);
        $data['breadcrumbs'] = array(
            array('path' => 'secure_crud_world_index', 'title' => 'Paises'),
            array('active' => true, 'title' => $data['title'] . ' de ' . $data['country']->getName())
        );
        return $this->render('secure/world/abm_states.html.twig', $data);
    }

    /**
     * @Route("/{country_id}/state/new", name="secure_crud_state_new")
     */
    public function newState(EntityManagerInterface $em, $country_id, CountriesRepository $countriesRepository, Request $request): Response
    {
        $data['title'] = 'Nuevo Estado/Provincia';
        $data['country'] = $countriesRepository->find($country_id);
        $data['state'] = new States;
        $data['breadcrumbs'] = array(
            array('path' => 'secure_crud_world_index', 'title' => 'Paises'),
            array('path' => 'secure_crud_states_index', 'path_parameters' => ['country_id' => $country_id], 'title' => 'Estados/Provincias ' . $data['country']->getName()),
            array('active' => true, 'title' => $data['title'])
        );
        $form = $this->createForm(StatesType::class, $data['state']);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $data['state']->setCountry($data['country']);

            $entityManager = $em;
            $entityManager->persist($data['state']);
            $entityManager->flush();

            return $this->redirectToRoute('secure_crud_states_index', ['country_id' => $country_id]);
        }
        $data['form'] = $form;
        return $this->renderForm('secure/world/form_state.html.twig', $data);
    }

    /**
     * @Route("/{country_id}/state/{state_id}/edit", name="secure_crud_state_edit")
     */
    public function editState(EntityManagerInterface $em, $country_id, $state_id, Request $request, CountriesRepository $countriesRepository, StatesRepository $statesRepository): Response
    {
        $data['title'] = 'Editar Estado/Provincia';
        $data['country'] = $countriesRepository->find($country_id);
        $data['state'] = $statesRepository->find($state_id);
        $data['breadcrumbs'] = array(
            array('path' => 'secure_crud_world_index', 'title' => 'Paises'),
            array('path' => 'secure_crud_states_index', 'path_parameters' => ['country_id' => $country_id], 'title' => 'Estados/Provincias de ' . $data['country']->getName()),
            array('active' => true, 'title' => $data['title'])
        );
        $form = $this->createForm(StatesType::class, $data['state']);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $em;
            $entityManager->persist($data['state']);
            $entityManager->flush();

            return $this->redirectToRoute('secure_crud_states_index', ['country_id' => $country_id]);
        }
        $data['form'] = $form;
        return $this->renderForm('secure/world/form_state.html.twig', $data);
    }

    /**
     * @Route("/{country_id}/state/{state_id}/cities", name="secure_crud_cities_index")
     */
    public function indexCities($country_id, $state_id, CountriesRepository $countriesRepository, StatesRepository $statesRepository, CitiesRepository $citiesRepository): Response
    {
        $data['title'] = 'Ciudades';
        $data['files_js'] = array('table_full_buttons.js?v=' . rand());
        $data['country'] = $countriesRepository->find($country_id);
        $data['state'] = $statesRepository->find($state_id);
        $data['cities'] = $citiesRepository->findCitiesByStateId($state_id);
        $data['breadcrumbs'] = array(
            array('path' => 'secure_crud_world_index', 'title' => 'Paises'),
            array('path' => 'secure_crud_states_index', 'path_parameters' => ['country_id' => $country_id], 'title' => 'Estados/Provincias de ' . $data['country']->getName()),
            array('active' => true, 'title' => $data['title'] . ' de ' . $data['state']->getName())
        );
        return $this->render('secure/world/abm_cities.html.twig', $data);
    }

    /**
     * @Route("/{country_id}/state/{state_id}/city/new", name="secure_crud_city_new")
     */
    public function newCity(EntityManagerInterface $em, $country_id, $state_id, Request $request, CountriesRepository $countriesRepository, StatesRepository $statesRepository): Response
    {
        $data['title'] = 'Nueva Ciudad';
        $data['country'] = $countriesRepository->find($country_id);
        $data['state'] = $statesRepository->find($state_id);
        $data['city'] = new Cities;
        $data['breadcrumbs'] = array(
            array('path' => 'secure_crud_world_index', 'title' => 'Paises'),
            array('path' => 'secure_crud_states_index', 'path_parameters' => ['country_id' => $country_id], 'title' => 'Estados/Provincias de ' . $data['country']->getName()),
            array('path' => 'secure_crud_cities_index', 'path_parameters' => ['country_id' => $country_id, 'state_id' => $state_id], 'title' => 'Ciudades de ' . $data['state']->getName()),
            array('active' => true, 'title' => $data['title'])
        );
        $form = $this->createForm(CitiesType::class, $data['city']);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $data['city']->setCountry($data['country']);
            $data['city']->setState($data['state']);


            $entityManager = $em;
            $entityManager->persist($data['city']);
            $entityManager->flush();

            return $this->redirectToRoute('secure_crud_cities_index', ['country_id' => $country_id, 'state_id' => $state_id]);
        }
        $data['form'] = $form;
        return $this->renderForm('secure/world/form_city.html.twig', $data);
    }

    /**
     * @Route("/{country_id}/state/{state_id}/city/{city_id}/edit", name="secure_crud_city_edit")
     */
    public function editCity(EntityManagerInterface $em, $country_id, $state_id, $city_id, Request $request, CountriesRepository $countriesRepository, StatesRepository $statesRepository, CitiesRepository $citiesRepository): Response
    {
        $data['title'] = 'Editar Ciudad';
        $data['country'] = $countriesRepository->find($country_id);
        $data['state'] = $statesRepository->find($state_id);
        $data['city'] = $citiesRepository->find($city_id);
        $data['breadcrumbs'] = array(
            array('path' => 'secure_crud_world_index', 'title' => 'Paises'),
            array('path' => 'secure_crud_states_index', 'path_parameters' => ['country_id' => $country_id], 'title' => 'Estados/Provincias de ' . $data['country']->getName()),
            array('path' => 'secure_crud_cities_index', 'path_parameters' => ['country_id' => $country_id, 'state_id' => $state_id], 'title' => 'Ciudades de ' . $data['state']->getName()),
            array('active' => true, 'title' => $data['title'])
        );
        $form = $this->createForm(CitiesType::class, $data['city']);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $em;
            $entityManager->persist($data['city']);
            $entityManager->flush();

            return $this->redirectToRoute('secure_crud_cities_index', ['country_id' => $country_id, 'state_id' => $state_id]);
        }
        $data['form'] = $form;
        return $this->renderForm('secure/world/form_city.html.twig', $data);
    }


    /**
     * @Route("/getSubregiones/{region_id}", name="secure_world_get_subregion", methods={"GET"})
     */
    public function getSubregiones($region_id, SubregionTypeRepository $subregionTypeRepository): Response
    {
        $data['data'] = $subregionTypeRepository->findSubregiones($region_id);
        if ($data['data']) {
            $data['status'] = true;
        } else {
            $data['status'] = false;
            $data['message'] = 'No se encontraron subregiones con el id indicado';
        }
        return new JsonResponse($data);
    }

    /**
     * @Route("/updateVisible/{entity_name}", name="secure_world_update_visible", methods={"POST"})
     */
    public function updateVisible(EntityManagerInterface $em, $entity_name, Request $request, CountriesRepository $countriesRepository, StatesRepository $statesRepository, CitiesRepository $citiesRepository): Response
    {
        $id = (int)$request->get('id');
        $visible = $request->get('visible');

        switch ($entity_name) {
            case 'Countries':
                $entity_object = $countriesRepository->find($id);
                break;
            case 'States':
                $entity_object = $statesRepository->find($id);
                break;
            case 'Cities':
                $entity_object = $citiesRepository->find($id);
                break;
        }

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
