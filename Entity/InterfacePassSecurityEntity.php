<?php

namespace Nidhognit\PassSecurityBundle\Entity;

interface InterfacePassSecurityEntity
{
    public function setPassword($password);
    public function getPassword();
    public function setNumber($number);
    public function getNumber();
}