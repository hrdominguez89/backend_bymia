<?php

namespace App\Controller\Secure;

use App\Repository\OrderRepository;
use App\Repository\ShoppingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\UrlHelper;
use Symfony\Component\Routing\Annotation\Route;

use App\Service\slugify;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class DashboardController
 * @package App\Controller
 * @Route("/dashboard")
 */
class DashboardController extends AbstractController
{

    private slugify $slugify;
    private $baseUrl;

    /**
     * DashboardController constructor.
     * @param slugify $slugify
     * @param UrlHelper $urlHelper
     */
    public function __construct(slugify $slugify, RequestStack $requestStack)
    {
        $this->slugify = $slugify;
        $this->baseUrl =  $requestStack->getCurrentRequest()->getSchemeAndHttpHost();
    }

    /**
     * @Route("/", name="dashboard_app", methods={"GET"})
     */
    public function main()
    {
        return $this->render('home/dashboard.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }
    /**
     * @Route("/order/sumary", name="order_sumary", methods={"GET"})
     */
    public function orderSummaryAction(ShoppingRepository $shoppingCartRepository)
    {
        $year = date('Y');
        $month =  date('m');
        $result = array();

        $custom = $shoppingCartRepository->summary($year, $month);
        for ($i = 1; $i <= $this->getLastDay((int)$month, $year); $i++) {
            $date = $year . '-' . $month . '-' . str_pad($i, 2, '0', STR_PAD_LEFT);

            $data = array(
                "date" => $date,
                "cant" => 0,
                "amount" => 0
            );
            foreach ($custom as $c) {
                if ($c['date'] == $date) {
                    $data["cant"] = number_format($c['cant'], 2, '.', '');
                    $data["amount"] = number_format($c['amount'], 2, '.', '');
                    break;
                }
            }
            $result['custom'][] = $data;
        }

        $last = $shoppingCartRepository->summary($year, (int)$month - 1);
        for ($i = 1; $i <= $this->getLastDay((int)$month - 1, $year); $i++) {
            $date = $year . '-' . str_pad((int)$month - 1, 2, '0', STR_PAD_LEFT) . '-' . str_pad($i, 2, '0', STR_PAD_LEFT);

            $data = array(
                "date" => $date,
                "cant" => 0,
                "amount" => 0
            );
            foreach ($last as $l) {
                if ($l['date'] == $date) {
                    $data["cant"] = number_format($l['cant'], 2, '.', '');
                    $data["amount"] = number_format($l['amount'], 2, '.', '');
                    break;
                }
            }
            $result['last'][] = $data;
        }
        return new JsonResponse($result);
    }

    private function getLastDay($month, $year)
    {
        if ($month == 2)
            if ($year % 4 == 0)
                return 29;
            else return 28;
        if ($month == 1 || $month == 3 || $month == 5 || $month == 7 || $month == 8 || $month == 10 || $month == 12) {
            return 31;
        }
        return 30;
    }

    /**
     * @Route("/summary/by-month", name="order_sumary_by_month", methods={"GET"})
     */
    public function summaryByMonthAction(ShoppingRepository $shoppingCartRepository)
    {
        $year = date('Y');
        $months = $shoppingCartRepository->summaryByMonth($year);

        $result = array(
            array("sales" => 0, "amount" => 0),
            array("sales" => 0, "amount" => 0),
            array("sales" => 0, "amount" => 0),
            array("sales" => 0, "amount" => 0),
            array("sales" => 0, "amount" => 0),
            array("sales" => 0, "amount" => 0),
            array("sales" => 0, "amount" => 0),
            array("sales" => 0, "amount" => 0),
            array("sales" => 0, "amount" => 0),
            array("sales" => 0, "amount" => 0),
            array("sales" => 0, "amount" => 0),
            array("sales" => 0, "amount" => 0),
        );
        $i = 0;
        foreach ($months as $month) {
            $result[((int)$month['month'] - 1)]["sales"] = number_format($month['cant'], 2, '.', '');
            $result[((int)$month['month'] - 1)]["amount"] = number_format($month['amount'], 2, '.', '');
        }
        return new JsonResponse($result);
    }

    /**
     * @Route("/products/best-seller", name="products_best_seller", methods={"GET"})
     */
    public function productBestSellerAction(ShoppingRepository $shoppingCartRepository)
    {
        $month = date('m');
        $year = date('Y');
        $products = $shoppingCartRepository->bestSeller($month, $year);

        $result = array();
        $i = 0;
        $otrosSales = 0;
        $otrosAmount = 0;
        foreach ($products as $product) {
            if ($i++ < 4) {
                $result[] = array(
                    "name" => $product['name'],
                    "sales" => $product['sales'],
                    "price" => $product['price'],
                    "amount" => $product['amount']
                );
            } else {
                $otrosSales += $product['sales'];
                $otrosAmount += $product['amount'];
            }
        }
        if ($otrosSales > 0) {
            $result[] = array(
                "name" => "Otros",
                "sales" => $otrosSales,
                "price" => $otrosAmount / $otrosSales,
                "amount" => $otrosAmount
            );
        }
        return new JsonResponse($result);
    }

    /**
     * @Route("/products/best-category", name="products_best_category", methods={"GET"})
     */
    public function bestCategoryAction(ShoppingRepository $shoppingCartRepository, Request $request)
    {
        $limit = $request->get('limit') ? $request->get('limit') : 4;
        $month = date('m');
        $year = date('Y');
        $categorys = $shoppingCartRepository->bestCategory($month, $year, $limit);

        $result = array();
        $i = 0;
        $amount = 0;
        $cant = 0;
        foreach ($categorys as $category) {
            if ($i++ < 4) {
                $result[] = array(
                    "name" => $category['name'],
                    "slug" => ($this->slugify)($category['name']),
                    "sales" => $category['sales'],
                    "amount" => $category['amount'],
                    "image" => ($category['image'] == "") ? "" : $this->baseUrl . '/' . $category['image'],
                );
            } else {
                $amount += $category['amount'];
                $cant += $category['sales'];
            }
        }
        if ($amount > 0) {
            $result[] = array(
                "name" => "Otros",
                "amount" => $amount,
                "sales" => $cant
            );
        }
        return new JsonResponse($result);
    }

    /**
     * @Route("/products/best-categories", name="products_best_categories", methods={"GET"})
     */
    public function bestCategoriesAction(ShoppingRepository $shoppingCartRepository, Request $request)
    {
        $limit = $request->get('limit') ? $request->get('limit') : 4;
        $cant = $request->get('quantity');
        $month = date('m');
        $year = date('Y');
        $categorys = $shoppingCartRepository->bestCategory($month, $year, $limit);

        $result = array();
        foreach ($categorys as $key => $category) {
            if (isset($cant)) {
                if ($key < intval($cant))
                    $result[] = array(
                        "id" => $category['id'],
                        "type" => 'shop',
                        "name" => $category['name'],
                        "slug" => ($this->slugify)($category['name']),
                        "path" => "",
                        "image" => ($category['image'] == "") ? "" : $this->baseUrl . '/' . $category['image'],
                        "items" => 0,
                        "customFields" => null,
                        "parents" => [],
                    );
            } else {
                $result[] = array(
                    "id" => $category['id'],
                    "type" => 'shop',
                    "name" => $category['name'],
                    "slug" => ($this->slugify)($category['name']),
                    "path" => "",
                    "image" => ($category['image'] == "") ? "" : $this->baseUrl . '/' . $category['image'],
                    "items" => 0,
                    "customFields" => null,
                    "parents" => [],
                );
            }
        }
        return new JsonResponse($result);
    }

    /**
     * @Route("/better-customer", name="better_customer", methods={"GET"})
     */
    public function betterCustomerAction(ShoppingRepository $shoppingCartRepository, UrlHelper $urlHelper)
    {
        $month = date('m');
        $year = date('Y');
        $products = $shoppingCartRepository->betterCustomer($month, $year);

        $result = array();
        $i = 0;
        foreach ($products as $product) {
            if ($i++ < 10) {
                $result[] = array(
                    "name" => $product['name'] . ' ' . $product['last_name'],
                    "image" => $this->baseUrl . '/' . $product['image'],
                    "email" => $product['email'],
                    "address" => $product['country'] . ' ' . $product['province'] . ' ' . $product['municipality'] . ' ' . $product['direction'],
                    "amount" => $product['amount'],
                    "cant" => $product['cant']
                );
            }
        }
        return new JsonResponse($result);
    }

    /**
     * @Route("/last-orders", name="last_orders", methods={"GET"})
     */
    public function lastOrderAction(OrderRepository $shoppingOrderRepository, UrlHelper $urlHelper)
    {
        $products = $shoppingOrderRepository->lastOrders();

        $result = array();
        $i = 0;
        foreach ($products as $product) {
            if ($i++ < 10) {
                $result[] = array(
                    "number" => $product['order_number'],
                    "name" => $product['name'] . ' ' . $product['last_name'],
                    "image" => $this->baseUrl . '/' . $product['image'],
                    "total" => $product['total'],
                    "state" => $product['state']
                );
            }
        }
        return new JsonResponse($result);
    }

    /**
     * @Route("/best-brand", name="best_brand", methods={"GET"})
     */
    public function bestBrandAction(ShoppingRepository $shoppingCartRepository, UrlHelper $urlHelper)
    {
        $month = date('m');
        $year = date('Y');
        $products = $shoppingCartRepository->bestBrand($month, $year);

        $result = array();
        $i = 0;
        foreach ($products as $product) {
            if ($i++ < 5) {
                $result[] = array(
                    "image" => $this->baseUrl . '/' . $product['image'],
                    "name" => $product['name'],
                    "sales" => $product['sales'],
                    "amount" => $product['amount']
                );
            } else {
                break;
            }
        }
        return new JsonResponse($result);
    }
}
