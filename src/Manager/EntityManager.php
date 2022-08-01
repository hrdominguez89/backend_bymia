<?php

namespace App\Manager;

use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class EntityManager
 * @package App\Manager
 * @author Yunior Pantoja Guerrero <ypguerrero123@gmail.com>
 */
class EntityManager
{
    /** @var ObjectManager */
    private $em;
    /** @var TokenStorageInterface */
    private $tokenStorage;

    /**
     * EntityManager constructor.
     * @param ManagerRegistry $registry
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(ManagerRegistry $registry, TokenStorageInterface $tokenStorage)
    {
        $this->em = $registry->getManager();
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @return ObjectManager
     */
    public function getEm(): ObjectManager
    {
        return $this->em;
    }

    /**
     * @param $object
     * @return $this
     */
    public function persist($object): EntityManager
    {
        $this->em->persist($object);

        return $this;
    }

    /**
     * @return $this
     */
    public function flush(): EntityManager
    {
        $this->em->flush();

        return $this;
    }

    /**
     * @param $object
     * @return $this
     */
    public function remove($object): EntityManager
    {
        $this->em->remove($object);
        $this->em->flush();

        return $this;
    }

    /**
     * @param $object
     * @return $this
     */
    public function save($object): EntityManager
    {
        $this->em->persist($object);
        $this->em->flush();

        return $this;
    }

    /**
     * @return $this
     */
    public function cascade(): EntityManager
    {
        $this->em->flush();

        return $this;
    }


}
