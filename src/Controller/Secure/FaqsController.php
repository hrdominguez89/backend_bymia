<?php

namespace App\Controller\Secure;

use App\Entity\Faqs;
use App\Form\FaqType;
use App\Entity\Topics;
use App\Form\TopicType;
use App\Helpers\FileUploader;
use App\Repository\FaqsRepository;
use App\Repository\TopicsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/faqs")
 */
class FaqsController extends AbstractController
{

    /**
     * @Route("/topics", name="abm_topics_faqs")
     */
    public function index(TopicsRepository $topicsRepository): Response
    {
        $data['title'] = 'Temas';
        $data['breadcrumbs'] = array(
            array('active' => true, 'title' => 'FAQS'),
            array('active' => true, 'title' => $data['title'])
        );
        $data['files_js'] = array('table_reorder.js?v=' . rand());
        $data['topics'] = $topicsRepository->findBy(array(), array('visible' => 'DESC', 'number_order' => 'ASC'));
        return $this->render('secure/faqs/abm_topics_faqs.html.twig', $data);
    }

    /**
     * @Route("/topics/new", name="new_topic")
     */
    public function new_topic(EntityManagerInterface $em, Request $request, FileUploader $fileUploader): Response
    {
        $data['title'] = 'Nuevo tema';
        $data['breadcrumbs'] = array(
            array('active' => true, 'title' => 'FAQS'),
            array('path' => 'abm_topics_faqs', 'title' => 'Temas'),
            array('active' => true, 'title' => $data['title'])
        );
        $data['topic'] = new Topics;
        $form = $this->createForm(TopicType::class, $data['topic']);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $imageFile */
            $imageFile = $form->get('icon')->getData();
            if ($imageFile) {
                $imageFileName = $fileUploader->upload($imageFile);
                $data['topic']->setIcon('uploads/images/' . $imageFileName);
            }

            $entityManager = $em;
            $entityManager->persist($data['topic']);
            $entityManager->flush();

            return $this->redirectToRoute('abm_topics_faqs');
        }
        $data['form'] = $form;
        return $this->renderForm('secure/faqs/form_topic_faq.html.twig', $data);
    }

    /**
     * @Route("/topics/{topic_id}/edit", name="edit_topic")
     */
    public function edit_topic(EntityManagerInterface $em, $topic_id, TopicsRepository $topicsRepository, Request $request, FileUploader $fileUploader): Response
    {
        $data['title'] = 'Editar tema';
        $data['delete_icon'] = true;
        $data['breadcrumbs'] = array(
            array('active' => true, 'title' => 'FAQS'),
            array('path' => 'abm_topics_faqs', 'title' => 'Temas'),
            array('active' => true, 'title' => $data['title'])
        );
        $data['topic'] = $topicsRepository->find($topic_id);
        $form = $this->createForm(TopicType::class, $data['topic']);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $imageFile */
            $imageFile = $form->get('icon')->getData();
            if ($imageFile) {
                $imageFileName = $fileUploader->upload($imageFile);
                $data['topic']->setIcon('uploads/images/' . $imageFileName);
            } else {
                if ($form->get('delete_icon')->getData()) {
                    $data['topic']->setIcon('');
                }
            }

            $entityManager = $em;
            $entityManager->persist($data['topic']);
            $entityManager->flush();

            return $this->redirectToRoute('abm_topics_faqs');
        }
        $data['form'] = $form;
        return $this->renderForm('secure/faqs/form_topic_faq.html.twig', $data);
    }
    /**
     * @Route("/topics/{topic_id}/faq", name="abm_faqs")
     */
    public function index_faq($topic_id, TopicsRepository $topicsRepository, FaqsRepository $faqsRepository): Response
    {
        $data['topic'] = $topicsRepository->findOneBy(array('id' => $topic_id), ['visible' => 'DESC', 'number_order' => 'ASC']);
        $data['faqs'] = $faqsRepository->findByTopicId($topic_id);
        $data['title'] = 'Preguntas de ' . $data['topic']->getName();
        $data['breadcrumbs'] = array(
            array('active' => true, 'title' => 'FAQS'),
            array('path' => 'abm_topics_faqs', 'title' => 'Temas'),
            array('active' => true, 'title' => $data['title'])
        );
        $data['files_js'] = array('table_reorder.js?v=' . rand());
        return $this->render('secure/faqs/abm_faqs.html.twig', $data);
    }

    /**
     * @Route("/topics/{topic_id}/faq/new", name="new_faq")
     */
    public function new_faq(EntityManagerInterface $em, $topic_id, Request $request, FileUploader $fileUploader, TopicsRepository $topicsRepository): Response
    {
        $data['topic'] = $topicsRepository->findOneBy(array('id' => $topic_id));
        $data['title'] = 'Nueva pregunta de ' . $data['topic']->getName();
        $data['faq'] = new Faqs;

        $data['files_js'] = array(
            'ckeditor_text_area.js?v=' . rand(),
        );

        $data['breadcrumbs'] = array(
            array('active' => true, 'title' => 'FAQS'),
            array('path' => 'abm_topics_faqs', 'title' => 'Temas'),
            array('path' => 'abm_faqs', 'path_parameters' => ['topic_id' => $topic_id], 'title' => 'Preguntas de ' . $data['topic']->getName()),
            array('active' => true, 'title' => $data['title'])
        );
        $form = $this->createForm(FaqType::class, $data['faq']);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data['faq']->setTopic($data['topic']);
            /** @var UploadedFile $imageFile */
            $imageFile = $form->get('icon')->getData();
            if ($imageFile) {
                $imageFileName = $fileUploader->upload($imageFile);
                $data['faq']->setIcon('uploads/images/' . $imageFileName);
            }

            $entityManager = $em;
            $entityManager->persist($data['faq']);
            $entityManager->flush();

            return $this->redirectToRoute('abm_faqs', ['topic_id' => $topic_id]);
        }
        $data['form'] = $form;

        return $this->renderForm('secure/faqs/form_faq.html.twig', $data);
    }

    /**
     * @Route("/topics/{topic_id}/faq/edit/{faq_id}", name="edit_faq")
     */
    public function edit_faq(EntityManagerInterface $em, $topic_id, $faq_id, TopicsRepository $topicsRepository, FaqsRepository $faqsRepository, Request $request, FileUploader $fileUploader): Response
    {
        $data['topic'] = $topicsRepository->find($topic_id);
        $data['title'] = 'Editar pregunta de ' . $data['topic']->getName();
        $data['faq'] = $faqsRepository->find($faq_id);
        $data['delete_icon'] = true;
        $data['breadcrumbs'] = array(
            array('active' => true, 'title' => 'FAQS'),
            array('path' => 'abm_topics_faqs', 'title' => 'Temas'),
            array('path' => 'abm_faqs', 'path_parameters' => ['topic_id' => $topic_id], 'title' => 'Preguntas de ' . $data['topic']->getName()),
            array('active' => true, 'title' => $data['title'])
        );
        $data['files_js'] = array(
            'ckeditor_text_area.js?v=' . rand(),
        );
        $form = $this->createForm(FaqType::class, $data['faq']);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $imageFile */
            $imageFile = $form->get('icon')->getData();
            if ($imageFile) {
                $imageFileName = $fileUploader->upload($imageFile);
                $data['faq']->setIcon('uploads/images/' . $imageFileName);
            } else {
                if ($form->get('delete_icon')->getData()) {
                    $data['faq']->setIcon('');
                }
            }

            $entityManager = $em;
            $entityManager->persist($data['faq']);
            $entityManager->flush();

            return $this->redirectToRoute('abm_faqs', ['topic_id' => $topic_id]);
        }
        $data['form'] = $form;
        return $this->renderForm('secure/faqs/form_faq.html.twig', $data);
    }

    /**
     * @Route("/updateVisible/{entity_name}", name="secure_faqs_update_visible", methods={"POST"})
     */
    public function updateVisible(EntityManagerInterface $em, $entity_name, Request $request, TopicsRepository $topicsRepository, FaqsRepository $faqsRepository): Response
    {
        $id = (int)$request->get('id');
        $visible = $request->get('visible');

        switch ($entity_name) {
            case 'Topics':
                $entity_object = $topicsRepository->find($id);
                break;
            case 'Faqs':
                $entity_object = $faqsRepository->find($id);
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

    /**
     * @Route("/updateOrder/{entity_name}", name="secure_faqs_update_order", methods={"POST"})
     */
    public function updateOrder(EntityManagerInterface $em, $entity_name, Request $request, TopicsRepository $topicsRepository, FaqsRepository $faqsRepository): Response
    {
        $ids = $request->get('orderData')['ids'];
        $orders = $request->get('orderData')['orders'];

        switch ($entity_name) {
            case 'Topics':
                foreach ($topicsRepository->findById($ids) as $obj) {
                    $obj->setNumberOrder($orders[array_search($obj->getId(), $ids)]);
                }
                break;
            case 'Faqs':
                foreach ($faqsRepository->findById($ids) as $obj) {
                    $obj->setNumberOrder($orders[array_search($obj->getId(), $ids)]);
                }
                break;
        }

        $entityManager = $em;
        // $entityManager->persist($entity_object);
        $entityManager->flush();

        $data['status'] = true;

        return new JsonResponse($data);
    }
}
