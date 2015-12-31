<?php

namespace models;


class UserClient
{
    private $remoteAddr;
    private $userAgent;

    public function __construct($remote, $userAgent)
    {
        $this->remoteAddr = $remote;
        $this->userAgent = $userAgent;
    }

    public function isSame($other)
    {
        return $other->remote === $this->remoteAddr && $other->userAgent === $this->userAgent;
    }
}