<?php

namespace App\Controller\Secure;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Countries;
use App\Repository\CitiesRepository;
use App\Repository\CountriesRepository;
use App\Repository\StatesRepository;

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
    public function newCountry(CountriesRepository $countriesRepository): Response
    {
        $data['title'] = 'Paises';
        $data['files_js'] = array('table_full_buttons.js?v=' . rand());
        $data['countries'] = $countriesRepository->findOneBy(['name' => 'Argentina']);
        // dump($data['countries']);die();
        return $this->render('secure/world/abm_countries.html.twig', $data);
    }

    /**
     * @Route("/{country_id}/edit", name="secure_crud_world_edit_country")
     */
    public function editCountry(CountriesRepository $countriesRepository): Response
    {
        $data['title'] = 'Paises';
        $data['files_js'] = array('table_full_buttons.js?v=' . rand());
        $data['countries'] = $countriesRepository->findOneBy(['name' => 'Argentina']);
        // dump($data['countries']);die();
        return $this->render('secure/world/abm_countries.html.twig', $data);
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
