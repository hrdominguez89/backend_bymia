<?php


namespace App\Controller\Secure;


use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UpdateProductStatusController extends AbstractController
{
    //obtener los productos nuevos
    public function getNews(EntityManagerInterface $em,ProductRepository $productRepository){
        $date = date('d-m-Y', strtotime('-15 day', strtotime(date('d-m-Y'))));
        $productos = $productRepository->findLast($date);
        foreach ($productos as $item){
            $item->setBadges('new');
            $em->persist($item);
        }
        $em->flush();
        return $this->json(['success'=>true]);
    }

    //obtener los 50 mas vendidos
    public function getTopSale(EntityManagerInterface $em,ProductRepository $productRepository){
        $productos =  $productRepository->findMostSale();
        foreach ($productos as $item){
            $item->setBadges('hot');
            $em->persist($item);
        }
        $em->flush();
        return $this->json(['success'=>true]);
    }

    //obtener los que estan en oferta
    public function getOfert(EntityManagerInterface $em,ProductRepository $productRepository){
        $productos =  $productRepository->findProductsInOffert();
        foreach ($productos as $item){
            $item->setBadges('sale');
            $em->persist($item);
        }
        $em->flush();
        return $this->json(['success'=>true]);
    }
}