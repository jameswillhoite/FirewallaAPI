<?php

namespace Firewalla\Classes\Info;

use Firewalla\Classes\Base\Base;

class PortInfo extends Base
{
    public string $protocol;

    public int $port;

    /**
     * @inheritDoc
     */
    public static function getParamMap(): array
    {
        return [
            "protocol" => "string",
            "port" => "int"
        ];
    }
}