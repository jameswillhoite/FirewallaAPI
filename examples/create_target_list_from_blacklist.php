<?php

require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . 'Firewalla' . DS . 'Service.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . 'Parse' . DS . 'Blocklist.php';

$service = new \Firewalla\Service();

$blockList = new \Parse\Blocklist();
$blockList->setFile("https://raw.githubusercontent.com/StevenBlack/hosts/master/data/StevenBlack/hosts");

$list = $blockList->parseFile();

//MSP only allows 2000 entries
$limitedList = array_splice($list, 0, 2000);

$tl = new \Firewalla\Classes\TargetList\TargetList();
$tl->owner = "global";
$tl->name = "Limited SB List";
$tl->targets = $limitedList;
$tl->category = \Firewalla\Classes\Category\Category::ad;

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