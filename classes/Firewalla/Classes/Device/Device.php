<?php

namespace Firewalla\Classes\Device;

use Firewalla\Classes\Base\Base;
use Firewalla\Classes\Group\Group;
use Firewalla\Classes\Network\Network;

class Device extends Base
{
    /**
     * This device's unique identifier
     * @var string
     */
    public string $id;

    /**
     * ID of the Firewalla box that this device is connecting to
     * @var string
     */
    public string $gid;

    /**
     * Device display name
     * @var string
     */
    public string $name;

    /**
     * Device IP address
     * @var string
     */
    public string $ip;

    /**
     * Vendor registered to the MAC address. Returned only if this device has a valid MAC address
     * @var string
     */
    public string $macVendor;

    /**
     * Whether this device is online or offline
     * @var bool
     */
    public bool $online;

    /**
     * A Unix timestamp that shows the last time device was seen online. Returned only if device is currently offline
     * @var \DateTime
     * @readonly
     */
    public \DateTime $lastSeen;

    /**
     * Whether the IP of this device is reserved on the box
     * @var bool
     */
    public bool $ipReserved;

    /**
     * Network that this device's flows were captured on
     * @var Network
     */
    public Network $network;

    /**
     * Group that this device belongs to
     * @var Group
     */
    public Group $group;

    /**
     * Total downloads in bytes for last 24 hours
     * @var int
     */
    public int $totalDownload;

    /**
     * Total uploads in bytes for last 24 hours
     * @var int
     */
    public int $totalUpload;

    /**
     * @var string
     */
    public string $deviceType;

    /**
     * @inheritDoc
     */
    public static function getParamMap(): array
    {
        return [
            "id" => "string",
            "gid" => "string",
            "name" => "string",
            "ip" => "string",
            "macVendor" => "string",
            "online" => "bool",
            "lastSeen" => \DateTime::class,
            "ipReserved" => "bool",
            "network" => Network::class,
            "group" => Group::class,
            "totalDownload" => "float",
            "totalUpload" => "float",
            "deviceType" => "string"
        ];
    }
}