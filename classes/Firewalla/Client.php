<?php

namespace Firewalla;

class Client
{
    protected $handler;
    protected string $token;

    public function __construct()
    {
        $this->token = $_ENV["FIREWALLA.MSP.TOKEN"];
    }


}