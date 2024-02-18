<?php

namespace Firewalla\Classes\Response;

class GetListResponse extends Response
{
    /**
     * @var array
     */
    public $record;

    /**
     * The ending cursor for pagination
     * @var string
     */
    public string $endCursor;

    /**
     * Total records found
     * @var int
     */
    public int $totalRecords;

    /**
     * If there are more pages to return
     * @var bool
     */
    public bool $hasNextPage;
}