<?php

namespace App\Controller\Secure;

use App\Entity\Inventory;
use App\Entity\Warehouses;
use App\Helpers\Login3pl;
use App\Repository\InventoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\WarehousesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * @Route("/warehouse")
 */
class WarehouseController extends AbstractController
{

    private $unauthorized;
    private $attempts;
    private $message;
    private $warehousesRepository;
    private $inventoryRepository;
    private $client;
    private $login3pl;
    private $requestStack;

    public function __construct(
        WarehousesRepository $warehousesRepository,
        InventoryRepository $inventoryRepository,
        HttpClientInterface $client,
        Login3pl $login3pl,
        RequestStack $requestStack
    ) {
        $this->unauthorized = false;
        $this->attempts = 0;

        $this->warehousesRepository = $warehousesRepository;
        $this->inventoryRepository = $inventoryRepository;
        $this->client = $client;
        $this->login3pl = $login3pl;
        $this->requestStack = $requestStack;
    }

    /**
     * @Route("/", name="secure_crud_warehouse_index")
     */
    public function index(
        Request $request,
        EntityManagerInterface $em
    ): Response {
        if ($request->getMethod() == 'POST') {

            $session = $this->requestStack->getSession();

            if ($session->get('3pl_data')) { //si existe la sesion la guardo con su status true.
                $response_login = [
                    'status' => true,
                    '3pl_data' => $session->get('3pl_data')
                ];
            } else { //si no existe logueo y guardo la info en la variable, y si existe 3pl_data lo guardo en una variable de sesion
                $response_login = $this->login3pl->Login();
                if (isset($response_login['3pl_data'])) {
                    $session->set('3pl_data', $response_login['3pl_data']);
                    $session->save();
                }
            }

            if ($response_login['status']) {
                try {
                    $response = $this->client->request(
                        'GET',
                        $_ENV['ML_API'] . '/inventory/list',
                        [
                            'headers'   => [
                                'Authorization' => 'Bearer ' . $response_login['3pl_data']['access_token'],
                                'Content-Type'  => 'application/json',
                            ]
                        ]
                    );

                    $body = $response->getContent(false);
                    $data_response = json_decode($body, true);


                    switch ($response->getStatusCode()) {

                        case Response::HTTP_OK:
                            foreach ($data_response as $inventory_response) {
                                $inventory = $this->inventoryRepository->findOneBy(['id3pl' => $inventory_response['id']]);
                                if (!$inventory) {
                                    $warehouse = $this->warehousesRepository->findOneBy(['id3pl' => $inventory_response['warehouse_id']]);
                                    if (!$warehouse) {
                                        $warehouse = new Warehouses;
                                        $warehouse->setId3pl($inventory_response['warehouse_id']);
                                        $em->persist($warehouse);
                                        $em->flush();
                                    }
                                    $inventory = new Inventory;
                                    $inventory->setWarehouse($warehouse);
                                    $inventory->setId3pl($inventory_response['id']);
                                    $inventory->setClientId($inventory_response['client_id']);
                                    $inventory->setCod($inventory_response['cod']);
                                    $inventory->setName($inventory_response['name']);
                                    $created_at = \DateTime::createFromFormat('Y-m-d H:i:s', $inventory_response['created_at']);
                                    $updated_at = \DateTime::createFromFormat('Y-m-d H:i:s', $inventory_response['updated_at']);
                                    $inventory->setCreatedAt($created_at);
                                    $inventory->setUpdatedAt($updated_at);
                                    $em->persist($inventory);
                                    $em->flush();
                                }
                            }
                            $this->message = [
                                "alert-color" => "success",
                                "message" => "Se actualizó el listado de warehouses e inventarios."
                            ];
                            break;

                        case Response::HTTP_UNAUTHORIZED:
                            $this->unauthorized = true;
                            $this->attempts++;
                            $this->message = [
                                "alert-color" => "danger",
                                "message" => "No autorizado, Error al intentar iniciar sesion con el 3pl."
                            ];
                            break;

                        default:
                            //leer error
                            $this->attempts++;
                            $this->message = [
                                "alert-color" => "danger",
                                "message" => "Error de servidor de 3pl, aguarde unos minutos e intente nuevamente."
                            ];
                            break;
                    }
                } catch (TransportExceptionInterface $e) {
                    $this->attempts++;
                    $this->message = [
                        "alert-color" => "danger",
                        "message" => $e->getMessage()
                    ];
                }
            } else {
                $this->attempts++;
                $this->message = [
                    "alert-color" => "danger",
                    "message" => "Fallo la comunicación con el 3pl."
                ];
            }

            if ($this->unauthorized && $this->attempts < 2) {
                $response_login = $this->login3pl->Login();
                if (isset($response_login['3pl_data'])) {
                    $session->set('3pl_data', $response_login['3pl_data']);
                    $session->save();
                    $this->index($request, $em);
                }
            }
            $data['message'] = $this->message;
        }
        $data['warehouses'] = $this->warehousesRepository->findAllWarehouseAndInventories();
        $data['title'] = 'Warehouses e Inventarios';
        $data['files_js'] = array('table_full_buttons.js?v=' . rand());
        $data['breadcrumbs'] = array(
            array('active' => true, 'title' => $data['title'])
        );


        return $this->render('secure/warehouse/abm_warehouses.html.twig', $data);
    }
}
