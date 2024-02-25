<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . 'Firewalla' . DS . 'Service.php';

try {
    $service = new \Firewalla\Service();

    //The ID of the Target List to retrieve
    $id = "";

    $GetRequest = new \Firewalla\Classes\Request\GetRequest();
    $GetRequest->id = $id;

    $response = $service->getTargetList($GetRequest);

    if($response->error)
    {
        throw new \Exception($response->error_msg);
    }

    /**
     * @var \Firewalla\Classes\TargetList\TargetList $List
     */
    $List = $response->record;

    //Add the New address to the List
    $List->targets[] = "some-new-address.com"; //Can be ip address, subnet, domain, etc ...

    $UpdateRequest = new \Firewalla\Classes\Request\UpdateRequest();
    $UpdateRequest->record = $List;

    $response = $service->updateTargetList($UpdateRequest);

    if($response->error)
    {
        throw new \Exception("Could not update the Target List: {$response->error_msg}");
    }




} catch (\Exception $e)
{
    echo "Error!: {$e->getMessage()}\r\n";
} finally {
    unset($service, $GetRequest, $UpdateRequest, $List, $response);
}