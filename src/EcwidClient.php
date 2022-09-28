<?php

namespace subcom\Ecwid;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

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
        $this->version = config('ecwid.api_version');
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
        $api = $this->endpoint_base.$this->version.'/'.$endpoint;

        $this->writeLog($api.'?'.http_build_query($params['query']), false);

        try {
            $response = $client->request($method, $api, $params);
            $ownerid = $this->getContent($response);
          
            $data = "Status Code: " .$response->getStatusCode()."\n"."Body: " .$response->getBody()."\n";
            $this->writeLog($data, false);
            
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
                $data = "Status Code: " .$e->getResponse()->getStatusCode()."\n"."Body: " .$e->getResponse()->getBody()."\n";
                $this->writeLog($data, true);

                return $this->getContent($e->getResponse()->getBody(), true);
            }
        }
    }

    /**
     * @param $response
     * @return mixed
     */
    public function getContent($response, $error = false)
    {
        if ($error == false) {
            $xml_string = $response->getBody()->getContents();
        } else {
            $xml_string = $response;
        }

        $xml = simplexml_load_string($xml_string);
        $json = json_encode($xml);
        $array = json_decode($json, true);

        return $array;
    }
    

    public function writeLog($data, $error = false)
    {
        if (File::exists('storage/logs/ecwid.log') == false) {
            $channel = Log::build([
                'driver' => 'single',
                'path' => storage_path('logs/ecwid.log'),
            ]);
        } else {
            $channel = 'ecwid';
        }

        $type= $error == true ? 'error' : 'info';
      
        Log::stack(['slack', $channel])->$type($data);
    }
}
