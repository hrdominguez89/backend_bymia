<?php

namespace App\Controller\Api\_3pl;

use App\Entity\HistoryProductStockUpdated;
use App\Form\UpdateStockProductType;
use App\Repository\ActionsProductTypeRepository;
use App\Repository\HistoryProductStockUpdatedRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/3pl")
 */
class Api3plProductController extends AbstractController
{
    /**
     * @Route("/product/{product_3pl_id}", name="api3pl_product",methods={"GET","PATCH"})
     */
    public function product(
        ProductRepository $productRepository,
        ActionsProductTypeRepository $actionsProductTypeRepository,
        Request $request,
        EntityManagerInterface $em,
        $product_3pl_id
    ): Response {
        $product = $productRepository->findOneBy(['id3pl' => $product_3pl_id]);
        if ($product) {

            switch ($request->getMethod()) {
                case 'GET':
                    return $this->json(
                        $product->getFullDataProduct(),
                        Response::HTTP_OK,
                        ['Content-Type' => 'application/json']
                    );
                case 'PATCH':
                    $body = $request->getContent();
                    $data = json_decode($body, true);


                    //creo el formulario para hacer las validaciones    

                    $form = $this->createForm(UpdateStockProductType::class, $product);
                    $form->submit($data, false);

                    if (!$form->isValid()) {
                        $error_forms = $this->getErrorsFromForm($form);
                        return $this->json(
                            [
                                'message' => 'Error de validación.',
                                'validation' => $error_forms
                            ],
                            Response::HTTP_BAD_REQUEST,
                            ['Content-Type' => 'application/json']
                        );
                    }

                    $historyObject = new HistoryProductStockUpdated;

                    //Busco los objetos de cada relacion
                    $action = $actionsProductTypeRepository->findOneBy(['name' => @$data['action']]);

                    // seteo valores de los objetos de relacion al objeto si no vinieron esos valores seteo los que tiene productos en ese momento.
                    $historyObject
                        ->setProduct($product)
                        ->setAction($action)
                        ->setOnhand(@$data['onhand'] ?: $product->getOnhand())
                        ->setCommited(@$data['commited'] ?: $product->getCommited())
                        ->setIncomming(@$data['incomming'] ?: $product->getIncomming())
                        ->setAvailable(@$data['available'] ?: $product->getAvailable());

                    try {
                        $em->persist($historyObject);
                        $em->persist($product);
                        $em->flush();
                    } catch (\Exception $e) {
                        return $this->json(
                            [
                                'message' => 'Error al intentar grabar en la base de datos.',
                                'validation' => ['others' => $e->getMessage()]
                            ],
                            Response::HTTP_UNPROCESSABLE_ENTITY,
                            ['Content-Type' => 'application/json']
                        );
                    }

                    return $this->json(
                        [
                            'message' => 'Stock de producto actualizado con éxito.',
                            'product_updated' => $product->getFullDataProduct()
                        ],
                        Response::HTTP_OK,
                        ['Content-Type' => 'application/json']
                    );
                    break;
            }
        }
        //si no encontro ni customer en methodo get o customer en post retorno not found 
        return $this->json(
            ['message' => 'Not found'],
            Response::HTTP_NOT_FOUND,
            ['Content-Type' => 'application/json']
        );
    }

    private function getErrorsFromForm(FormInterface $form)
    {
        $errors = array();
        foreach ($form->getErrors() as $error) {
            $errors[] = $error->getMessage();
        }
        foreach ($form->all() as $childForm) {
            if ($childForm instanceof FormInterface) {
                if ($childErrors = $this->getErrorsFromForm($childForm)) {
                    $errors[$childForm->getName()] = $childErrors;
                }
            }
        }
        return $errors;
    }
}
