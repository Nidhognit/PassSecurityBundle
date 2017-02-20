<?php

namespace Nidhognit\PassSecurityBundle\DependencyInjection\Services;

use Doctrine\ORM\EntityManager;
use Nidhognit\PassSecurityBundle\Entity\InterfacePassSecurityEntity;

class DataBaseReader implements InterfaceReader
{
    /** @var string */
    protected $class;
    /** @var string */
    protected $repository;
    /** @var  EntityManager */
    protected $em;
    /** @var  InterfacePassSecurityEntity */
    protected $entity;

    /**
     * DataBaseReader constructor.
     * @param EntityManager $em
     * @param array         $options
     */
    public function __construct(EntityManager $em, $options)
    {
        $this->em = $em;
        $this->repository = $options['repository'];
        $this->class = $options['class'];
    }

    /**
     * @param      $password
     * @param null|int $limit
     * @return null|int
     */
    public function findByPassword($password, $limit = null)
    {
        $passSecurity = $this->em->getRepository($this->repository)->findOneBy(['password' => $password]);
        if ($passSecurity) {
            if (!$limit || $limit >= $passSecurity->getNumber()) {
                return $passSecurity->getNumber();
            }
        }

        return null;
    }

    /**
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * @return string
     */
    public function getRepository()
    {
        return $this->repository;
    }
}