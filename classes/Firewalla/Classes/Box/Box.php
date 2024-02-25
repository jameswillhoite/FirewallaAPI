<?php

namespace Firewalla\Classes\Box;

use Firewalla\Classes\Base\Base;

class Box extends Base
{
    /**
     * This Firewalla box’s unique identifier
     * @var string
     */
    public string $gid;

    /**
     * Box display name
     * @var string
     */
    public string $name;

    /**
     * Box model
     * @var string
     */
    public string $model;

    /**
     * Box monitoring mode, possible values are **router** **bridge** **dhcp** **simple**
     * @var string
     */
    public string $mode;

    /**
     * Version of Firewalla software currently running on this box
     * @var string
     */
    public string $version;

    /**
     * Whether this box is online or offline
     * @var bool
     */
    public bool $online;

    /**
     * A Unix timestamp that shows the last time box was seen online. Only returned if box is currently offline
     * @var \DateTime
     */
    public \DateTime $lastSeen;

    /**
     * License code of this box
     * @var string
     */
    public string $license;

    /**
     * Public IP of this box, this might not be the WAN IP. If it's not, the box has double NAT
     * @var string
     */
    public string $publicIP;

    /**
     * Group ID if this box belongs to a box group. **null** if there's no group association
     * @var string
     */
    public string $group;

    /**
     * Geographical location of this box based on its public IP
     * @var string
     */
    public string $location;

    /**
     * Number of devices on this box
     * @var int
     */
    public int $deviceCount;

    /**
     * Number of rules on this box
     * @var int
     */
    public int $ruleCount;

    /**
     * Number of alarms on this box
     * @var int
     */
    public int $alarmCount;

    /**
     * @inheritDoc
     */
    public static function getParamMap(): array
    {
        return [
            "gid" => "string",
            "name" => "string",
            "model" => "string",
            "version" => "string",
            "online" => "bool",
            "lastSeen" => \DateTime::class,
            "license" => "string",
            "publicIP" => "string",
            "group" => "string",
            "location" => "string",
            "deviceCount" => "int",
            "ruleCount" => "int",
            "alarmCount" => "int"
        ];
    }
}