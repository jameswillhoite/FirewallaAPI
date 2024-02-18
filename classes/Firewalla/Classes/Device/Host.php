<?php

namespace Firewalla\Classes\Device;

use Firewalla\Classes\Base\Base;

class Host extends Base
{

    /**
     * If host is a local device, it is DeviceID, otherwise it is remote host domain or ip
     * @var string
     */
    public string $id;

    /**
     * IP address of the host.
     * @var string
     */
    public string $ip;

    /**
     * If host is a local device, it is device display name, otherwise it is remote host domain
     * @var string
     */
    public string $name;



    /**
     * @inheritDoc
     */
    public static function getParamMap(): array
    {
        return [
            "ip" => "string",
            "id" => "string",
            "name" => "string"
        ];
    }
}