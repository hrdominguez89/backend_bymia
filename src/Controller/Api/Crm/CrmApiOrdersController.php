<?php

namespace App\Controller\Api\Crm;

use App\Repository\OrdersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;



/**
 * @Route("/api/crm")
 */
class CrmApiOrdersController extends AbstractController
{
    /**
     * @Route("/order/{order_id}", name="api_order",methods={"GET","PATCH"})
     * 
     */
    public function order(
        OrdersRepository $ordersRepository,
        Request $request,
        EntityManagerInterface $em,
        $order_id
    ): Response {
        $order = $ordersRepository->find($order_id);
        if ($order) {

            switch ($request->getMethod()) {
                case 'GET':
                    return $this->json(
                        $order->generateOrderToCRM(),
                        Response::HTTP_OK,
                        ['Content-Type' => 'application/json']
                    );
                case 'PATCH':
                    break;
            }
        }
        //si no encontro ni customer en methodo get o customer en post retorno not found 
        return $this->json(
            ['message' => 'Not found.'],
            Response::HTTP_NOT_FOUND,
            ['Content-Type' => 'application/json']
        );
    }
}
