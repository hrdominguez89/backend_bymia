<?php

namespace App\Controller\Secure;

use App\Entity\Specification;
use App\Entity\SpecificationTypes;
use App\Form\SpecificationType;
use App\Repository\SpecificationRepository;
use App\Repository\SpecificationTypesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/specifications")
 */
class CrudSpecificationController extends AbstractController
{

    /**
     * @Route("/", name="secure_crud_specification_type_index", methods={"GET"})
     */
    public function index(SpecificationTypesRepository $specificationTypesRepository): Response
    {
        $data['title'] = 'Tipos de especificaciones';
        $data['specification_types'] = $specificationTypesRepository->findAllSpecificationTypesOrdered();;
        $data['files_js'] = array('table_full_buttons.js?v=' . rand());
        $data['breadcrumbs'] = array(
            array('active' => true, 'title' => $data['title'])
        );

        return $this->render('secure/crud_specification/abm_specifications_types.html.twig', $data);
    }

    /**
     * @Route("/{specification_type_id}/new", name="secure_crud_specification_new", methods={"GET","POST"})
     */
    public function new($specification_type_id, SpecificationTypesRepository $specificationTypesRepository, Request $request, EntityManagerInterface $em): Response
    {

        $data['specification_type'] = $specificationTypesRepository->findOneBy(["id" => $specification_type_id]);
        $data['title'] = 'Nueva especificación de: ' . $data['specification_type']->getName();
        // $data['files_js'] = array('table_full_buttons.js?v=' . rand());
        $data['breadcrumbs'] = array(
            array('path' => 'secure_crud_specification_type_index', 'title' => 'Tipos de especificaciones'),
            array('active' => true, 'title' => $data['title'])
        );
        $data['specification'] = new Specification;
        $data['specification']->setSpecificationType($data['specification_type']);

        $form = $this->createForm(SpecificationType::class, $data['specification']);

        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            if ($data['specification_type']->getName() == 'Color') {
                $data['specification']->setColorHexadecimal($request->get('specification')['color']);
            }
            $entityManager = $em;
            $entityManager->persist($data['specification']);
            $entityManager->flush();

            return $this->redirectToRoute('secure_crud_specification_index', ['specification_type_id' => $specification_type_id]);
        }
        $data['form'] = $form;
        return $this->renderForm('secure/crud_specification/form_specifications.html.twig', $data);
    }

    /**
     * @Route("/{specification_id}/edit", name="secure_crud_specification_edit", methods={"GET","POST"})
     */
    public function edit(EntityManagerInterface $em,Request $request, SpecificationRepository $specificationRepository, $specification_id): Response
    {
        $data['specification'] = $specificationRepository->findOneBy(['id' => $specification_id]);

        $data['specification_type'] = $data['specification']->getSpecificationType();
        $data['title'] = 'Nueva especificación de: ' . $data['specification_type']->getName();
        // $data['files_js'] = array('table_full_buttons.js?v=' . rand());
        $data['breadcrumbs'] = array(
            array('path' => 'secure_crud_specification_type_index', 'title' => 'Tipos de especificaciones'),
            array('active' => true, 'title' => $data['title'])
        );

        $form = $this->createForm(SpecificationType::class, $data['specification']);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($data['specification_type']->getName() == 'Color') {
                $data['specification']->setColorHexadecimal($request->get('specification')['color']);
            }
            $entityManager = $em;
            $entityManager->persist($data['specification']);
            $entityManager->flush();

            return $this->redirectToRoute('secure_crud_specification_index', ['specification_type_id' => $data['specification_type']->getId()]);
        }
        $data['form'] = $form;
        return $this->renderForm('secure/crud_specification/form_specifications.html.twig', $data);
    }

    /**
     * @Route("/{specification_type_id}", name="secure_crud_specification_index", methods={"GET"})
     */
    public function specifications(SpecificationRepository $specificationRepository, SpecificationTypesRepository $specificationTypesRepository, $specification_type_id): Response
    {
        $data['specification_type'] = $specificationTypesRepository->findOneBy(["id" => $specification_type_id]);
        $data['title'] = 'Especificación: ' . $data['specification_type']->getName();
        $data['specifications'] = $specificationRepository->findAllSpecificationsOrdered($specification_type_id);
        $data['files_js'] = array('table_full_buttons.js?v=' . rand());
        $data['breadcrumbs'] = array(
            array('path' => 'secure_crud_specification_type_index', 'title' => 'Tipos de especificaciones'),
            array('active' => true, 'title' => $data['title'])
        );

        return $this->render('secure/crud_specification/abm_specifications.html.twig', $data);
    }
}
