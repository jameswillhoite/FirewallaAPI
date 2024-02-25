<?php

namespace Firewalla\Classes\Request;

use Firewalla\Classes\Request\GetListRequest;

class GetListFlowsRequest extends GetListRequest
{
    /**
     * The search
     * @link https://<msp_domain>/api/docs/api-reference/search/#basic-query-syntax
     * @var string
     */
    public string $query;

    /**
     * @var string[]
     */
    public array $groupBy;

    /**
     * A list sot sort by
     * @example "ts:desc"
     * @var string[]
     */
    public array $sortBy;

    /**
     * Limit the result set
     * @default 200
     * @var int
     */
    public int $limit = 200;

    /**
     * Used in pagination
     * @var string
     */
    public string $endCursor;
}