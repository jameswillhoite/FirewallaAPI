<?php

namespace Firewalla;

use Firewalla\Classes\Response\Response;

class Client
{

    protected string $token;

    protected string $url;
    protected string $version;
    protected int $last_http_code;

    public function __construct()
    {
        $this->url = $_ENV["FIREWALLA.MSP.DOMAIN"];
        $this->token = $_ENV["FIREWALLA.MSP.TOKEN"];
        $this->version = $_ENV["FIREWALLA.MSP.VERSION"];
    }

    /**
     * Get the last HTTP Code retrieved from the call
     * @return int
     */
    public function getLastHttpCode(): int
    {
        return $this->last_http_code ?? 0;
    }

    /**
     * @param string $type The type of request to preform (GET, POST, PATCH, DELETE)
     * @param string $endpoint The endpoint to call
     * @param string|null $json_body The body to post
     * @return Response
     */
    public function makeRequest(string $type, string $endpoint, string $json_body = null): Response
    {
        $response = new Response();
        if(!in_array($type, ["GET", "POST", "PATCH", "DELETE"]))
        {
            $response->error = true;
            $response->error_msg = "Invalid Type: {$type}";

            return $response;
        }

        if($json_body && !is_string($json_body))
        {
            $response->error = true;
            $response->error_msg = "json_body must be a string";

            return $response;
        }

        $url = $this->url . "/" . $this->version . "/";
        $url .= ltrim($endpoint, "/");

        $c = curl_init($url);
        if($type === "GET")
        {
            curl_setopt($c, CURLOPT_HTTPGET, true);
        }
        elseif($type === "POST")
        {
            curl_setopt($c, CURLOPT_POST, true);
            curl_setopt($c, CURLOPT_POSTFIELDS, $json_body);
        }
        elseif($type === "PUT")
        {
            curl_setopt($c, CURLOPT_PUT, true);
        }
        else
        {
            curl_setopt($c, CURLOPT_CUSTOMREQUEST, $type);
        }

        curl_setopt($c, CURLINFO_HEADER_OUT, true);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($c, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($c, CURLOPT_HTTPHEADER, array(
            'Accept: application/json',
            'Authorization: Token ' . $this->token
        ));

        $data = json_decode(curl_exec($c));
        $curlInfo = curl_getinfo($c);
        $errorNo = curl_errno($c);
        $errorMsg = curl_error($c);
        curl_close($c);

        $this->last_http_code = (int)$curlInfo['http_code'];

        if($errorNo)
        {
            $response->error = true;
            $response->error_msg = $errorMsg;

            return $response;
        }

        if($this->last_http_code === 401)
        {
            $response->error = true;
            $response->error_msg = "Not Authorized!";

            return $response;
        }

        $response->record = $data;


        return $response;

    }
}