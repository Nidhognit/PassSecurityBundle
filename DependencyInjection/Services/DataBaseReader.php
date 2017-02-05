<?php

namespace Nidhognit\PassSecurityBundle\DependencyInjection\Services;

use Doctrine\ORM\EntityManager;
use Nidhognit\PassSecurityBundle\Entity\InterfacePassSecurityEntity;
use Nidhognit\PassSecurityBundle\Entity\PassSecurityBase;

class DataBaseReader implements InterfaceReader
{
    /** @var string */
    protected $defaultClass = PassSecurityBase::class;
    /** @var string */
    protected $defaultRepository = 'PassSecurityBundle:PassSecurityBase';
    /** @var  EntityManager */
    protected $em;
    /** @var  InterfacePassSecurityEntity */
    protected $entity;

    /**
     * DataBaseReader constructor.
     * @param EntityManager $em
     * @param array $options
     */
    public function __construct(EntityManager $em, $options = [])
    {
        $this->em = $em;
        $this->readOptions($options);
    }

    public function findByPassword($password, $limit = null)
    {
        $passSecurity = $this->em->getRepository('PassSecurityBundle:PassSecurityBase')->findOneBy(['password' => $password]);
        if ($passSecurity) {
            if (!$limit || $limit >= $passSecurity->getNumber()) {
                return $passSecurity->getNumber();
            }
        }

        return null;
    }

    private function readOptions($options)
    {

    }

    /**
     * @return string
     */
    public function getDefaultClass()
    {
        return $this->defaultClass;
    }
}