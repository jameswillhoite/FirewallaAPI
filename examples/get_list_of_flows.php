<?php

require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . 'Firewalla' . DS . 'Service.php';

$service = new \Firewalla\Service();

$request = new \Firewalla\Classes\Request\GetListFlowsRequest();
$request->query = "ts:>1704067200";

do {

    $response = $service->getFlows($request);
    $request->endCursor = $response->endCursor;

    /**
     * @var \Firewalla\Classes\Flow\Flow[]
     */
    $records = $response->record;

} while($response->hasNextPage);


if($response->error)
{
    echo "ERROR: " . $response->error_msg . "\r\n";
}