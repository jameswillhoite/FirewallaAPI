<?php

namespace Firewalla\Classes\Device;

use Firewalla\Classes\Base\Base;
use Firewalla\Classes\Info\PortInfo;

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

    public PortInfo $portInfo;

    public string $type;


    /**
     * @inheritDoc
     */
    public static function getParamMap(): array
    {
        return [
            "ip" => "string",
            "id" => "string",
            "name" => "string",
            "portInfo" => PortInfo::class,
            "type" => "string"
        ];
    }
}