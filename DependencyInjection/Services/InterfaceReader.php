<?php

namespace Nidhognit\PassSecurityBundle\DependencyInjection\Services;

interface InterfaceReader
{
    /**
     * @param      $password
     * @param null $limit
     * @return int|null
     */
    public function findByPassword($password, $limit = null);
}