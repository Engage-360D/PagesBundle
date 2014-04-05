<?php

/**
 * This file is part of the Engage360d package bundles.
 *
 */

namespace Engage360d\Bundle\PagesBundle\Entity\Category;

use Doctrine\ORM\EntityManager;
use Doctrine\Common\Persistence\ObjectManager;
use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Model\UserManager as BaseUserManager;
use FOS\UserBundle\Util\CanonicalizerInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface as SecurityUserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use FOS\OAuthServerBundle\Model\ClientManagerInterface;
use FOS\OAuthServerBundle\Model\ClientInterface;

/**
 * Category manager.
 *
 */
class CategoryManager
{
    protected $objectManager;
    protected $class;
    protected $repository;

    /**
     * Constructor.
     *
     * @param ObjectManager           $om
     * @param string                  $class
     */
    public function __construct(ObjectManager $om, $class)
    {
        $this->objectManager = $om;
        $this->repository = $om->getRepository($class);

        $metadata = $om->getClassMetadata($class);
        $this->class = $metadata->getName();
    }

    public function create()
    {
        $class = $this->getClass();
        $object = new $class;

        return $object;
    }

    public function delete($object)
    {
        $this->objectManager->remove($object);
        $this->objectManager->flush();
    }

    public function getClass()
    {
        return $this->class;
    }

    public function findById($id)
    {
        return $this->repository->findOneBy(array('id' => $id));
    }

    public function findByUrl($url)
    {
        return $this->repository->findOneBy(array('url' => $url));
    }

    public function findByUrlPrecondition($precondition)
    {
        return $this->repository->createQueryBuilder('u')
              ->select('u')
              ->where('u.url LIKE :url')
              ->setParameter('url', '%'.$precondition.'%')
              ->getQuery()
              ->getResult();
    }

    public function getPage($page = 1, $limit = 25, $select = 'u')
    {
        return $this->repository->findAll();
    }

    public function reload($object)
    {
        $this->objectManager->refresh($object);
    }

    public function update($object, $andFlush = true)
    {
        $this->objectManager->persist($object);
        if ($andFlush) {
            $this->objectManager->flush();
        }
    }
}