<?php

namespace Firewalla\Classes\Flow;

use Firewalla\Classes\Base\Base;
use Firewalla\Classes\Device\Device;
use Firewalla\Classes\Device\Host;
use Firewalla\Classes\Network\Network;

class Flow extends Base
{
    /**
     * A Unix timestamp that shows the time the flow ended
     * @var \DateTime
     */
    public \DateTime $ts;

    /**
     * Unique identifier of the Firewalla box that captured this flow
     * @var string
     */
    public string $gid;

    /**
     * Transport protocol of this flow, either **tcp** or **udp**
     * @var string
     */
    public string $protocol;

    /**
     * Direction of the flow, **inbound** for inbound, **outbound** for outbound, or **local** for local traffic
     * @var string
     */
    public string $direction;

    /**
     * Whether this is a blocked flow or regular flow. true for blocked flow
     * @var bool
     */
    public bool $block;

    /**
     * Type of blocked flow, either ip or dns, only returned on blocked flows
     * @var string
     */
    public string $blockType;

    /**
     * Number of bytes downloaded, only returned on regular flows
     * @var int
     */
    public int $download;

    /**
     * Number of bytes uploaded, only returned on regular flows
     * @var int
     */
    public int $upload;

    /**
     * Duration of this flow in seconds, only return on regular flows
     * @var int
     */
    public int $duration;

    /**
     * Number of TCP connections or UDP sessions for flow, or block count for blocked flow
     * @var int
     */
    public int $count;

    /**
     * Detailed information of the monitoring device involved in this flow
     * @var Device
     */
    public Device $device;

    /**
     * Detailed information of the source host involved in this flow
     * @var Host
     */
    public Host $source;

    /**
     * Detailed information of the destination host involved in this flow
     * @var Host
     */
    public Host $destination;

    /**
     * Region of the remote IP, a 2-letter <a href="https://en.wikipedia.org/wiki/ISO_3166-1_alpha-2">ISO 3166 code</a>
     * @var string
     */
    public string $region;

    /**
     * Remote host category, based on Firewalla cloud intel
     * @var string
     */
    public string $category;

    /**
     * Network that this flow was captured on
     * @var Network
     */
    public Network $network;


    /**
     * @inheritDoc
     */
    public static function getParamMap(): array
    {
        return [
            "ts" => \DateTime::class,
            "gid" => "string",
            "protocol" => "string",
            "direction" => "string",
            "block" => "bool",
            "blockType" => "string",
            "download" => "int",
            "upload" => "int",
            "duration" => "int",
            "count" => "int",
            "device" => Device::class,
            "source" => Host::class,
            "destination" => Host::class,
            "region" => "string",
            "category" => "string",
            "network" => Network::class
        ];
    }
}