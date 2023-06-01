<?php

namespace App\Controller\Api\Crm;

use App\Entity\DebitCreditNotesFiles;
use App\Entity\GuideNumbers;
use App\Entity\ItemsGuideNumber;
use App\Entity\PaymentsReceivedFiles;
use App\Repository\GuideNumbersRepository;
use App\Repository\ItemsGuideNumberRepository;
use App\Repository\OrdersRepository;
use App\Repository\PaymentsReceivedFilesRepository;
use App\Repository\ProductRepository;
use App\Repository\StatusOrderTypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
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
     * @Route("/order/{order_id}", name="api_crm_order",methods={"GET","PATCH"})
     * 
     */
    public function order(
        OrdersRepository $ordersRepository,
        Request $request,
        EntityManagerInterface $em,
        $order_id,
        StatusOrderTypeRepository $statusOrderTypeRepository,
        GuideNumbersRepository $guideNumbersRepository,
        ItemsGuideNumberRepository $itemsGuideNumberRepository,
        ProductRepository $productRepository,
        PaymentsReceivedFilesRepository $paymentsReceivedFilesRepository
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
                    break;
                case 'PATCH':

                    $body = $request->getContent();
                    $data = json_decode($body, true);

                    $status_obj = $statusOrderTypeRepository->find($data['status_order']);

                    $order->setStatus($status_obj);

                    if ($data['packages']) {
                        foreach ($data['packages'] as $package) {
                            //busco si ya fue creado el paquete
                            $package_obj = $guideNumbersRepository->findOneBy(['number_order' => $order_id, 'number' => $package['guide_number']]);

                            //si no fue creado creo el objeto y seteo valores
                            if (!$package_obj) {
                                $package_obj =  new GuideNumbers;
                                $package_obj->setNumberOrder($order);
                                $package_obj->setLb($package['lb']);
                                $package_obj->setHeight($package['height']);
                                $package_obj->setWidth($package['width']);
                                $package_obj->setDepth($package['depth']);
                                $package_obj->setCourierId($package['courier_id']);
                                $package_obj->setCourierName($package['courier_name']);
                                $package_obj->setNumber($package['guide_number']);
                            }
                            $package_obj->setServiceId($package['service_id']);
                            $package_obj->setServiceName($package['service_name']);

                            $em->persist($package_obj);


                            foreach ($package['items'] as $item) {
                                $product_obj = $productRepository->findOneBy(['id3pl' => $item['product_id']]);
                                if ($product_obj) {
                                    //Busco si existen items para ese numero de guia.
                                    $items_obj = $itemsGuideNumberRepository->findOneBy(['guide_number' => $package_obj->getId() ?: null, 'product' => $product_obj ? $product_obj->getId() : null]);
                                    if (!$items_obj) {
                                        $items_obj = new ItemsGuideNumber;
                                        $items_obj->setGuideNumber($package_obj);
                                        $items_obj->setProduct($product_obj);
                                        $items_obj->setQuantity($item['quantity']);

                                        $em->persist($items_obj);
                                    }
                                }
                            }
                        }
                    }

                    if ($data['bill_file']) {
                        $order->setBillFile($data['bill_file']);
                    }

                    if ($data['payments_received_files']) {
                        foreach ($data['payments_received_files'] as $payment_received_file) {
                            $payment_received_file_obj = $paymentsReceivedFilesRepository->findOneBy(['payment_received_file' => $payment_received_file]);
                            if (!$payment_received_file_obj) {
                                $payment_received_file_obj = new PaymentsReceivedFiles;
                                $payment_received_file_obj->setOrderNumber($order);
                                $payment_received_file_obj->setPaymentReceivedFile($payment_received_file);

                                $em->persist($payment_received_file_obj);
                            }
                        }
                    }

                    if ($data['debit_credit_notes_files']) {
                        foreach ($data['debit_credit_notes_files'] as $debit_credit_note_file) {
                            $debit_credit_note_file_obj = $paymentsReceivedFilesRepository->findOneBy(['debit_credit_note_file' => $debit_credit_note_file]);
                            if (!$debit_credit_note_file_obj) {
                                $debit_credit_note_file_obj = new DebitCreditNotesFiles;
                                $debit_credit_note_file_obj->setNumberOrder($order);
                                $debit_credit_note_file_obj->setDebitCreditNoteFile($debit_credit_note_file);

                                $em->persist($debit_credit_note_file_obj);
                            }
                        }
                    }


                    $em->persist($order);

                    try {
                        $em->flush();
                    } catch (Exception $e) {
                        return $this->json(
                            [
                                'error' => 'Error al intentar actualizar la orden',
                                'json_enviado' => json_encode($data),
                                'message' => $e->getMessage()
                            ],
                            Response::HTTP_INTERNAL_SERVER_ERROR,
                            ['Content-Type' => 'application/json']
                        );
                    }

                    return $this->json(
                        $order->generateOrderToCRM(),
                        Response::HTTP_ACCEPTED,
                        ['Content-Type' => 'application/json']
                    );

                    break;
            }
        }
        //si no encontro ni order en methodo get o order en patch retorno not found 
        return $this->json(
            ['message' => 'Not found.'],
            Response::HTTP_NOT_FOUND,
            ['Content-Type' => 'application/json']
        );
    }
}
