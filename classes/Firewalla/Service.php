<?php

namespace Firewalla;

use Dotenv\Dotenv;
use Firewalla\Classes\Box\Box;
use Firewalla\Classes\Device\Device;
use Firewalla\Classes\Flow\Flow;
use Firewalla\Classes\Request\AddRequest;
use Firewalla\Classes\Request\GetListBoxesRequest;
use Firewalla\Classes\Request\GetListDevicesRequest;
use Firewalla\Classes\Request\GetListFlowsRequest;
use Firewalla\Classes\Request\GetListRequest;
use Firewalla\Classes\Request\GetListTrendsRequest;
use Firewalla\Classes\Request\GetRequest;
use Firewalla\Classes\Response\GetListResponse;
use Firewalla\Classes\Response\GetResponse;
use Firewalla\Classes\Response\Response;
use Firewalla\Classes\TargetList\TargetList;
use Firewalla\Classes\Trend\Trend;

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
                $dt = new \DateTime();
                $dt->setTimestamp($value)->setTimezone(new \DateTimeZone("UTC"));
                return $dt;
                return new \DateTime(strtotime($value), new \DateTimeZone("UTC"));

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
                    $this->buildObject($base->{$key}, $value);
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

    public function createTargetList(AddRequest $request): Response
    {
        $response = new Response();
        $endpoint = "target-lists";

        if(!isset($request->record) || !$request->record instanceof TargetList)
        {
            $response->error = true;
            $response->error_msg = "Missing or invalid Record. Must be instance of " . TargetList::class;

            return $response;
        }

        $record = $request->record;

        if(empty($record->name) || strlen($record->name ?? "") > 24)
        {
            $response->error = true;
            $response->error_msg = "Missing or invalid Name. Name cannot be longer than 24 characters";

            return $response;
        }

        if(empty($record->owner ?? ""))
        {
            $response->error = true;
            $response->error_msg = "Box owner is required for creation";
            return $response;
        }

        $count = count($record->targets);

        if($count === 0 || $count > 2000)
        {
            $response->error = true;
            $response->error_msg = "Invalid targets. Must not be greater than 2000";

            return $response;
        }

        $rsp = $this->client->makeRequest("POST", $endpoint, json_encode($record));

        if($rsp->error)
        {
            $response->error = true;
            $response->error_msg = $rsp->error_msg;

            return $response;
        }

        if($this->client->getLastHttpCode() === 400)
        {
            $response->error = true;
            $response->error_msg = "Bad Request";

            return $response;
        }

        $t = new TargetList();
        $this->buildObject($t, $rsp->record);

        $response->record = $t;

        return $response;
    }

    /**
     * Get a list of all boxes in the MSP
     * @param GetListBoxesRequest $request
     * @return GetListResponse
     */
    public function getBoxes(GetListBoxesRequest $request): GetListResponse
    {
        $endpoint = "boxes";

        if(isset($request->boxGroup))
        {
            $endpoint .= "?group={$request->boxGroup}";
        }

        $response = new GetListResponse();

        $rsp = $this->client->makeRequest("GET", $endpoint);

        if($rsp->error)
        {
            $response->error = true;
            $response->error_msg = $rsp->error_msg;

            return $response;
        }

        $response->record = [];

        if(!$rsp->record || count($rsp->record) === 0)
        {
            return $response;
        }

        foreach ($rsp->record as $record)
        {
            $r = new Box();
            $this->buildObject($r, $record);

            $response->record[] = $r;
        }

        return $response;

    }

    /**
     * Get a list of Devices
     * @param GetListDevicesRequest $request
     * @return GetListResponse
     */
    public function getDevices(GetListDevicesRequest $request): GetListResponse
    {
        $response = new GetListResponse();
        $endpoint = "devices";
        $params = [];

        if(isset($request->group))
        {
            $params[] = "group={$request->group}";
        }
        if(isset($request->box))
        {
            $params[] = "box={$request->box}";
        }

        if(count($params) > 0)
        {
            $endpoint .= "?" . implode('&', $params);
        }

        $rsp = $this->client->makeRequest("GET", $endpoint);

        if($rsp->error)
        {
            $response->error = true;
            $response->error_msg = $rsp->error_msg;

            return $response;
        }

        $response->record = [];

        if(count($rsp->record) === 0)
        {
            return $response;
        }

        foreach ($rsp->record as $record)
        {
            $d = new Device();
            $this->buildObject($d, $record);
            $response->record[] = $d;
        }

        return $response;
    }

    /**
     * @param GetListFlowsRequest $request
     * @return GetListResponse
     */
    public function getFlows(GetListFlowsRequest $request): GetListResponse
    {
        $response = new GetListResponse();
        $endpoint = "flows";
        $params = [];

        if(!isset($request->endCursor))
        {
            if(isset($request->limit))
            {
                if($request->limit <= 0)
                {
                    $response->error = true;
                    $response->error_msg = "Invalid Limit";
                    return $response;
                }

                if ($request->limit > 500)
                {
                    $response->error = true;
                    $response->error_msg = "Invalid Limit. Must be less than or equal to 500";
                    return $response;
                }

                $params[] = "limit={$request->limit}";
            }

            if(isset($request->groupBy))
            {
                $params[] = "groupBy=" . implode(',', $request->groupBy);
            }

            if(isset($request->sortBy))
            {
                $params[] = "sortBy=" . implode(',', $request->sortBy);
            }

            if(isset($request->query))
            {
                $params[] = "query={$request->query}";
            }
        }

        if(isset($request->endCursor))
        {
            $params[] = "cursor={$request->endCursor}";
        }

        if(count($params) > 0)
        {
            $endpoint .= "?" . implode('&', $params);
        }

        $rsp = $this->client->makeRequest("GET", $endpoint);

        if($rsp->error)
        {
            $response->error = true;
            $response->error_msg = $rsp->error_msg;

            return $response;
        }

        $response->endCursor = "";
        $response->hasNextPage = false;
        $response->record = [];

        //This response is a little different from the rest
        if(isset($rsp->record->next_cursor))
        {
            $response->endCursor = $rsp->record->next_cursor;
            $response->hasNextPage = true;
        }

        if(isset($rsp->record->count))
        {
            $response->totalRecords = (int)$rsp->record->count;
        }

        foreach ($rsp->record->results as $record)
        {
            $f = new Flow();
            $this->buildObject($f, $record);
            $response->record[] = $f;
        }

        return $response;


    }

    /**
     * @param GetListTrendsRequest $request
     * @return GetListResponse
     */
    public function getTrends(GetListTrendsRequest $request): GetListResponse
    {
        $response = new GetListResponse();
        $params = [];
        $endpoint = "trends/";

        switch(strtolower($request->type ?? ""))
        {
            case "flows":
                $endpoint .= "flows";
                break;
            case "alarms":
                $endpoint .= "alarms";
                break;
            case "rules":
                $endpoint .= "rules";
                break;
            default:
                $response->error = true;
                $response->error_msg = "Missing or invalid Request Type. Valid Types are: flows, alarms, rules";
                return $response;
        }

        if(isset($request->group))
        {
            $params[] = "group=" . $request->group;
        }

        if(count($params) > 0)
        {
            $endpoint .= "?" . implode('&', $params);
        }

        $rsp = $this->client->makeRequest("GET", $endpoint);

        $response->record = [];
        $response->endCursor = "";
        $response->hasNextPage = false;
        $response->totalRecords = 0;

        if($rsp->error)
        {
            $response->error = true;
            $response->error_msg = $rsp->error_msg;

            return $response;
        }

        foreach ($rsp->record as $record)
        {
            $t = new Trend();
            $this->buildObject($t, $record);
            $response->record[] = $t;
            $response->totalRecords++;
        }

        return $response;

    }

}
