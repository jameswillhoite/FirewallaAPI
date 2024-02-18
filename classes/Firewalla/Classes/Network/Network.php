<?php

namespace Firewalla\Classes\Network;

use Firewalla\Classes\Base\Base;

class Network extends Base
{
    /**
     * The network’s unique identifier
     * @var string
     */
    public string $id;

    /**
     * Name of the network
     * @var string
     */
    public string $name;

    /**
     * @inheritDoc
     */
    public static function getParamMap(): array
    {
        return [
            "id" => "string",
            "name" => "string"
        ];
    }
}