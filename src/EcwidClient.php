<?php

namespace subcom\Ecwid;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class EcwidClient
{
    /**
     * @var string
     */
    protected $endpoint_base;
    /**
     * @var string
     */
    protected $version;

    /**
     * @param
     */
    public function __construct() {

        $this->endpoint_base =config('ecwid.endpoint_base');
        $this->version =config('ecwid.endpoint_version');

    }

    /**
     * @param $method
     * @param $endpoint
     * @param $params
     * @param $create
     * @return array|mixed|void
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function apiCall($method, $endpoint, $params, $create = false)
    {
        $client = new Client();
        $api = $this->endpoint_base.$this->version.$endpoint;

        try {

            $response = $client->request($method, $api, $params);

            if ($create) {

                $ownerid = $this->getContent($response);

                $create_response = ['status' =>200, 'ownerid'=>$ownerid[0]];

                return $create_response;
            }

            return $this->getContent($response);

        } catch (RequestException $e) {

            if ($e->hasResponse()) {

                return json_decode($e->getResponse()->getBody(), true);

            }

        }
    }

    /**
     * @param $response
     * @return mixed
     */
    public function getContent($response)
    {
        $xml_string = $response->getBody()->getContents();
        $xml = simplexml_load_string($xml_string);
        $json = json_encode($xml);
        $array = json_decode($json, true);

        return $array;
    }

}
