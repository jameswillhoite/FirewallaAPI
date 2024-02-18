<?php

namespace Firewalla\Classes\Response;

class Response
{
    /**
     * Is error
     * @var bool
     */
    public bool $error;

    /**
     * The Error Message
     * @var string
     */
    public string $error_msg;

    /**
     * The Record
     * @var
     */
    public $record;
}