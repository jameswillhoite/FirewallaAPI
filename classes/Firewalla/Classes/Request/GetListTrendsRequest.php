<?php

namespace Firewalla\Classes\Request;

use Firewalla\Classes\Request\GetListRequest;

class GetListTrendsRequest extends GetListRequest
{
    /**
     * Type of Trend to Retrieve
     * Valid Types are: flows, alarms, rules
     * @var string
     */
    public string $type;

    /**
     * Gets trends for a specific box group, must be supplied with the ID of a box group. The API returns global statistics by default
     * @var string
     */
    public string $group;
}