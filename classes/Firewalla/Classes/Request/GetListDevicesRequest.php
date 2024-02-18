<?php

namespace Firewalla\Classes\Request;

use Firewalla\Classes\Request\GetListRequest;

class GetListDevicesRequest extends GetListRequest
{
    /**
     * Gets devices under a specific Firewalla box group, must be supplied with the ID of a box group
     * @var string
     */
    public string $group;

    /**
     * Gets devices under a specific Firewalla box, must be supplied with the ID of a box
     * @var string
     */
    public string $box;
}