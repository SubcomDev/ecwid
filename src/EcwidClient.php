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
    public function __construct()
    {
        $this->endpoint_base = config('ecwid.endpoint_base');
        $this->version = config('ecwid.endpoint_version');
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

        if (! file_exists('logs/')) {
            mkdir('logs/', 0775, true);
        }

        $fp = fopen('logs/ecwid.log', 'wb+');
        fwrite($fp, $api.http_build_query($params) . PHP_EOL);

        try {
            $response = $client->request($method, $api, $params);
            $ownerid = $this->getContent($response);

            fwrite($fp, "Status Code: " . PHP_EOL . $response->getStatusCode()."\n");
            fwrite($fp, "Body: " . PHP_EOL . $response->getBody()."\n");
            fclose($fp);

            if ($create) {
                $create_response = ['status' => 200, 'ownerid' => $ownerid[0]];

                return $create_response;
            }

            $ecwid_response = [
                'status' => json_decode($response->getStatusCode(), true),
                'content' => $this->getContent($response),
            ];

            return $ecwid_response;
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                fwrite($fp, "Status Code: " . PHP_EOL . $e->getResponse()->getStatusCode()."\n");
                fwrite($fp, "Body: " . PHP_EOL . $e->getResponse()->getBody()."\n");
                fclose($fp);

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
