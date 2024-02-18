<?php

namespace Firewalla;

use Dotenv\Dotenv;
use Firewalla\Classes\Request\GetListRequest;
use Firewalla\Classes\Request\GetRequest;
use Firewalla\Classes\Response\GetListResponse;
use Firewalla\Classes\Response\GetResponse;
use Firewalla\Classes\TargetList\TargetList;

defined("DS") || define("DS", DIRECTORY_SEPARATOR);
defined("BASE") || define("BASE", realpath(__DIR__ . DS . '..' . DS . '..'));

require_once BASE . DS . 'vendor' . DS . 'autoload.php';

if(!class_exists(Client::class))
{
    require_once __DIR__ . DS . 'Client.php';
}

if(!class_exists(Autoloader::class))
{
    require_once __DIR__ . DS . 'Autoloader.php';
}

class Service
{
    protected Client $client;

    public function __construct()
    {
        Dotenv::createImmutable(BASE)->load();
        $this->client = new Client();
    }

    /**
     * Cast the value to the given type
     * @param string $type
     * @param mixed $value
     * @return mixed
     */
    protected function cast(string $type, $value)
    {
        switch (strtolower($type))
        {
            case "string":
                return (string)$value;

            case "int":
                return (int) $value;

            case "float":
                return (float) $value;

            case "datetime":
                return new \DateTime(strtotime($value));

            default:
                if(preg_match("|^Firewalla\\\|", $type) && is_object($value))
                {
                    $obj = new $type();
                    $this->buildObject($obj, $value);
                    return $obj;
                }

                return $value;

        }
    }

    /**
     * Build the Classes
     * @param object $base
     * @param object $data
     * @return void
     */
    protected function buildObject(object $base, object $data)
    {
        if(!method_exists($base, "getParamMap"))
        {
            return;
        }

        $map = $base::getParamMap();

        foreach ($data as $key => $value) {
            $mapVal = $map[$key] ?? "";

            //Check for array value
            if ((strpos($mapVal, "["))) {
                $mapVal = rtrim($mapVal, "[]");
                $base->{$key} = [];
                foreach ($value as $val) {
                    $base->{$key}[] = $this->cast($mapVal, $val);
                }
            }
            else
            {
                if(is_object($value))
                {
                    $base->{$key} = new $mapVal();
                    $this->buildObject($base, $value);
                }
                else
                {
                    $base->{$key} = $this->cast($mapVal, $value);
                }
            }
        }



    }

    /**
     * Get the given target list
     * @param GetRequest $request
     * @return GetResponse
     */
    public function getTargetList(GetRequest $request): GetResponse
    {
        $response = new GetResponse();

        if(empty($request->id))
        {
            $response->error = true;
            $response->error_msg = "No ID given";

            return $response;
        }

        $endpoint = "target-lists/{$request->id}";

        $rsp = $this->client->makeRequest("GET", $endpoint);

        if($rsp->error)
        {
            $response->error = true;
            $response->error_msg = $rsp->error_msg;

            return $response;
        }

        if(!isset($rsp->record))
        {
            return $response;
        }


        $list = new TargetList();
        $this->buildObject($list, $rsp->record);

        $response->record = $list;

        return $response;

    }

    /**
     * Get all Target lists
     * @param GetListRequest|null $request For now, there is nothing to send. Will get all target lists
     * @return GetListResponse
     */
    public function getListTargetList(GetListRequest $request = null): GetListResponse
    {
        $endpoint = "target-lists";

        $response = new GetListResponse();

        $rsp = $this->client->makeRequest("GET", $endpoint);

        if($rsp->error)
        {
            $response->error = true;
            $response->error_msg = $rsp->error_msg;

            return $response;
        }

        $response->record = [];

        if(empty($rsp->record))
        {
            return $response;
        }

        foreach ($rsp->record as $record)
        {
            $l = new TargetList();
            $this->buildObject($l, $record);
            $response->record[] = $l;
        }

        return $response;

    }
}