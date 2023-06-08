<?php

namespace App\Controller\Secure;

use App\Entity\Order;
use App\Form\Model\OrderSearchDto;
use App\Form\OrderSearchType;
use App\Helpers\SendMail;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;

/**
 * @Route("/order")
 */
class OrderController extends AbstractController
{
    /**
     * @Route("/index", name="order")
     */
    public function index(OrderRepository $orderRepository, Request $request): Response
    {
        $orderSearch = new OrderSearchDto();
        $form = $this->createForm(OrderSearchType::class, $orderSearch);
        $form->handleRequest($request);


        $page = $request->query->getInt('page', $request->get("page") || 1);
        $limit = 15;
        $orders = $orderRepository->list($page, $limit, $orderSearch);

        return $this->render('secure/order/index.html.twig', [
            'orders' => $orders,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/show", name="secure_order_show", methods={"GET"})
     */
    public function show(Order $order): Response
    {
        return $this->render('secure/order/show.html.twig', [
            'order' => $order,
        ]);
    }

    /**
     * @Route("/{id}/{status}/change-status", name="secure_order_change_status", methods={"GET"})
     */
    public function updateOrder(EntityManagerInterface $em, Order $order, $status, MailerInterface $mailer)
    {
        if ($order) {
            $order->setCheckoutStatus($status);
            $em->persist($order);
            $em->flush();

            $email = (new TemplatedEmail())
                ->from(new Address('noreply@bymia.do', 'MIACARGO'))
                ->to($order->getCheckoutBillingEmail())
                ->subject('Cambio de estado de su Orden ' . $order->getCheckoutId())
                ->htmlTemplate('secure/order/email.html.twig')
                ->context([
                    'status' => $status,
                    'order' => $order->asArray(),
                    'urlFront' => $_ENV['FRONT_URL'],
                    'urlSite' => $_ENV['SITE_URL'],
                    'eemail' => array(
                        'toName' => $order->getCheckoutBillingEmail()
                    ),
                    'contact' => array(
                        'email' => $order->getCheckoutBillingEmail(),
                        'address' => $order->getCheckoutBillingAddress(),
                        'phone' => $order->getCheckoutBillingPhone()
                    )
                ]);

            $mailer->send($email);
        }
        return $this->redirect($this->generateUrl('order'));
    }
}
