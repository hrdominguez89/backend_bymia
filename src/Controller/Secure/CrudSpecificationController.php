<?php

namespace App\Controller\Secure;

use App\Entity\Specification;
use App\Form\SpecificationType;
use App\Repository\SpecificationRepository;
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
     * @Route("/", name="secure_crud_specification_index", methods={"GET"})
     */
    public function index(SpecificationRepository $specificationRepository, EntityManagerInterface $em): Response
    {
        $data = $specificationRepository->findBy(['active'=>true]);
        return $this->render('secure/crud_specification/index.html.twig', [
            'specifications' => $data
        ]);
    }

    /**
     * @Route("/new", name="secure_crud_specification_new", methods={"GET","POST"})
     */
    public function new(Request $request, EntityManagerInterface $em): Response
    {

        $specification = new Specification();
        $form = $this->createForm(SpecificationType::class, $specification);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($specification);
            $entityManager->flush();

            return $this->redirectToRoute('secure_crud_specification_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('secure/crud_specification/new.html.twig', [
            'specification' => $specification,
            'form' => $form
            ]);
    }

    /**
     * @Route("/{id}", name="secure_crud_specification_show", methods={"GET"})
     */
    public function show(Specification $specification): Response
    {
        return $this->render('secure/crud_specification/show.html.twig', [
            'specification' => $specification
        ]);
    }

    /**
     * @Route("/{id}/edit", name="secure_crud_specification_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Specification $specification): Response
    {
        $form = $this->createForm(SpecificationType::class, $specification);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('secure_crud_specification_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('secure/crud_specification/edit.html.twig', [
            'specification' => $specification,
            'form' => $form
        ]);
    }

    /**
     * @Route("/specification/{id}", name="secure_crud_specification_delete", methods={"POST"})
     */
    public function delete(Request $request, Specification $specification): Response
    {
        if ($this->isCsrfTokenValid('delete' . $specification->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($specification->setActive(false));
            $entityManager->flush();
        }

        return $this->redirectToRoute('secure_crud_specification_index',
            [],
            Response::HTTP_SEE_OTHER);
    }
}
