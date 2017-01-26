<?php

namespace Nidhognit\PassSecurityBundle\DependencyInjection\Services;

use Doctrine\ORM\EntityManager;
use Nidhognit\PassSecurityBundle\Entity\InterfacePassSecurityEntity;

class DataBaseReader implements InterfaceReader
{
    /** @var string */
    protected $defaultClass = 'PassSecurityBundle\Entity\PassSecurityBase';
    /** @var  EntityManager */
    protected $em;
    /** @var  InterfacePassSecurityEntity */
    protected $entity;

    /**
     * DataBaseReader constructor.
     * @param EntityManager $em
     * @param array         $options
     */
    public function __construct(EntityManager $em, $options = [])
    {
        $this->em = $em;
    }

    public function findByPassword($password, $limit = null)
    {

    }

    private function readOptions()
    {

    }

}