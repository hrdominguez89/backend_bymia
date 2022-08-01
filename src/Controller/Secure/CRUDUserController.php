<?php

namespace App\Controller\Secure;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Helpers\FileUploader;
use App\Helpers\SendMail;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\ResetPassword\ResetPasswordHelperInterface;

/**
 * @Route("/crud-user")
 */
class CRUDUserController extends AbstractController
{
    /**
     * @Route("/", name="secure_crud_user_index", methods={"GET"})
     */
    public function index(UserRepository $userRepository): Response
    {
        $data['users'] =  $userRepository->findAll();
        // $data['files_css'] = array('hola.css?v='.rand());
        $data['files_js'] = array('operators.js?v=' . rand());
        return $this->render('secure/crud_user/index.html.twig', $data);
    }

    /**
     * @Route("/new", name="secure_crud_user_new", methods={"GET","POST"})
     */
    public function new(
        Request $request,
        FileUploader $fileUploader,
        ResetPasswordHelperInterface $resetPasswordHelper,
        UrlGeneratorInterface $router,
        TranslatorInterface $translator,
        SendMail $sendMail
    ): Response {



        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $imageFile */
            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                $imageFileName = $fileUploader->upload($imageFile);
                $user->setImage('uploads/images/' . $imageFileName);
            }
            $user->setPassword($_ENV['PWD_NEW_USER']);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $resetToken = $resetPasswordHelper->generateResetToken($user);
            $url = $router->generate('app_reset_password', ['token' => $resetToken->getToken()], UrlGeneratorInterface::ABSOLUTE_URL);
            $msg = 'Felicitaciones es usted miembro de nuestro equipo de trabajo, use la siguiente dirección para acceder al sistema: <a href="' . $url . '"></a>' . $url . '<br>Este vinculo caducará en ' . $translator->trans($resetToken->getExpirationMessageKey(), $resetToken->getExpirationMessageData(), 'ResetPasswordBundle');
            ($sendMail)($user->getEmail(), $user->getName(), 'Credenciales del sistema', $msg);

            return $this->redirectToRoute('secure_crud_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('secure/crud_user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="secure_crud_user_show", methods={"GET"})
     */
    public function show(User $user): Response
    {
        return $this->render('secure/crud_user/show.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="secure_crud_user_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, User $user, FileUploader $fileUploader): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $imageFile */
            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                $imageFileName = $fileUploader->upload($imageFile);
                $user->setImage('uploads/images/' . $imageFileName);
            }
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('secure_crud_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('secure/crud_user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="secure_crud_user_delete", methods={"POST"})
     */
    public function delete(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('secure_crud_user_index', [], Response::HTTP_SEE_OTHER);
    }
}
