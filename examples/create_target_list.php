<?php

require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . 'Firewalla' . DS . 'Service.php';

$service = new \Firewalla\Service();

$tl = new \Firewalla\Classes\TargetList\TargetList();
$tl->owner = "global";
$tl->name = "This is a Test";
$tl->targets = [
    "netflix.com",
    "54.237.226.164"
];

$request = new \Firewalla\Classes\Request\AddRequest();
$request->record = $tl;

$response = $service->createTargetList($request);

if($response->error)
{
    echo $response->error_msg;
    exit();
}

/**
 * @var \Firewalla\Classes\TargetList\TargetList $record
 */
$record = $response->record;
