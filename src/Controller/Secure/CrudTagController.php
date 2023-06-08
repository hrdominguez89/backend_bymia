<?php

namespace App\Controller\Secure;

use App\Entity\Tag;
use App\Form\TagType;
use App\Repository\TagRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/tag")
 */
class CrudTagController extends AbstractController
{

    /**
     * @Route("/", name="secure_crud_tag_index", methods={"GET"})
     */
    public function index(TagRepository $tagRepository): Response
    {
        $data['title'] = 'Etiquetas';
        $data['tags'] = $tagRepository->findAll();
        $data['files_js'] = array('table_full_buttons.js?v=' . rand());
        $data['breadcrumbs'] = array(
            array('active' => true, 'title' => $data['title'])
        );
        return $this->render('secure/crud_tag/abm_tags.html.twig', $data);
    }

    /**
     * @Route("/new", name="secure_crud_tag_new", methods={"GET","POST"})
     */
    public function new(EntityManagerInterface $em, Request $request): Response
    {
        $data['title'] = 'Nueva etiqueta';
        $data['breadcrumbs'] = array(
            array('path' => 'secure_crud_tag_index', 'title' => 'Etiquetas'),
            array('active' => true, 'title' => $data['title'])
        );
        $data['tag'] = new Tag();
        $form = $this->createForm(TagType::class, $data['tag']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $em;
            $entityManager->persist($data['tag']);
            $entityManager->flush();

            return $this->redirectToRoute('secure_crud_tag_index', [], Response::HTTP_SEE_OTHER);
        }

        $data['form'] = $form;
        return $this->renderForm('secure/crud_tag/form_tag.html.twig', $data);
    }

    /**
     * @Route("/{id}/edit", name="secure_crud_tag_edit", methods={"GET","POST"})
     */
    public function edit(EntityManagerInterface $em, $id, Request $request, TagRepository $tagRepository): Response
    {
        $data['title'] = 'Editar etiqueta';
        $data['breadcrumbs'] = array(
            array('path' => 'secure_crud_tag_index', 'title' => 'Etiquetas'),
            array('active' => true, 'title' => $data['title'])
        );
        $data['tag'] = $tagRepository->find($id);
        $form = $this->createForm(TagType::class, $data['tag']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $em;
            $entityManager->persist($data['tag']);
            $entityManager->flush();

            return $this->redirectToRoute('secure_crud_tag_index', [], Response::HTTP_SEE_OTHER);
        }

        $data['form'] = $form;
        return $this->renderForm('secure/crud_tag/form_tag.html.twig', $data);
    }

    /**
     * @Route("/updateVisible/tag", name="secure_tag_update_visible", methods={"post"})
     */
    public function updateVisible(EntityManagerInterface $em, Request $request, TagRepository $TagRepository): Response
    {
        $id = (int)$request->get('id');
        $visible = $request->get('visible');


        $entity_object = $TagRepository->find($id);

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
