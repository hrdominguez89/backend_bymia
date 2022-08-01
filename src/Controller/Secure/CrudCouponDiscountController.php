<?php

namespace App\Controller\Secure;

use App\Entity\CouponDiscount;
use App\Form\CouponDiscountType;
use App\Repository\CouponDiscountRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/coupon-discount")
 */
class CrudCouponDiscountController extends AbstractController
{
    /**
     * @Route("/", name="secure_crud_coupon_discount_index", methods={"GET"})
     */
    public function index(CouponDiscountRepository $couponDiscountRepository, Request $request,PaginatorInterface $pagination): Response
    {
        $data = $couponDiscountRepository->findAll();
        $paginator = $pagination->paginate(
            $data,
            $request->query->getInt('page', $request->get("page") || 1), /*page number*/
            15, /*limit per page*/
            ['align' => 'center', 'style' => 'bottom',]
        );
        return $this->render('secure/crud_coupon_discount/index.html.twig', [
            'coupon_discounts' => $paginator,
        ]);
    }

    /**
     * @Route("/new", name="secure_crud_coupon_discount_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $couponDiscount = new CouponDiscount();
        $form = $this->createForm(CouponDiscountType::class, $couponDiscount);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $cant = $request->get('cantidad');
            $entityManager = $this->getDoctrine()->getManager();
            $Strings = '0123456789abcdefghijklmnopqrstuvwxyz';
            if ($cant > 1) {
                for ($i = 0; $i < $cant; $i++) {
                    $newCoupon = new CouponDiscount();
                    $newCoupon
                        ->setNro(substr(str_shuffle($Strings), 0, 10))
                        ->setNumberOfUses($couponDiscount->getNumberOfUses())
                        ->setValue($couponDiscount->getValue())
                        ->setPercent($couponDiscount->isPercent());
                    $entityManager->persist($newCoupon);
                }
            } else {
                $couponDiscount->setNumberOfUses(1);
                if ($couponDiscount->getNro() == '') {
                    $couponDiscount->setNro(substr(str_shuffle($Strings), 0, 10));
                }
                $entityManager->persist($couponDiscount);
            }
            $entityManager->flush();

            return $this->redirectToRoute('secure_crud_coupon_discount_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('secure/crud_coupon_discount/new.html.twig', [
            'coupon_discount' => $couponDiscount,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="secure_crud_coupon_discount_show", methods={"GET"})
     */
    public function show(CouponDiscount $couponDiscount): Response
    {
        return $this->render('secure/crud_coupon_discount/show.html.twig', [
            'coupon_discount' => $couponDiscount,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="secure_crud_coupon_discount_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, CouponDiscount $couponDiscount): Response
    {
        $form = $this->createForm(CouponDiscountType::class, $couponDiscount);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('secure_crud_coupon_discount_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('secure/crud_coupon_discount/edit.html.twig', [
            'coupon_discount' => $couponDiscount,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="secure_crud_coupon_discount_delete", methods={"POST"})
     */
    public function delete(Request $request, CouponDiscount $couponDiscount): Response
    {
        if ($this->isCsrfTokenValid('delete'.$couponDiscount->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($couponDiscount);
            $entityManager->flush();
        }

        return $this->redirectToRoute('secure_crud_coupon_discount_index', [], Response::HTTP_SEE_OTHER);
    }
}
