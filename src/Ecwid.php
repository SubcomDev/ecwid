<?php

namespace subcom\Ecwid;

class Ecwid
{
    /**
     * @var string
     */
    protected $api_key;

    /**
     * @param
     */
    public function __construct()
    {
        $this->client = new EcwidClient();

        $this->api_key = config('ecwid.api_key');
    }

    /**
     * @param $store
     * @param $create
     * @return mixed
     */
    public function updateOrCreateStore($store, $create = false)
    {
        $method = 'POST';

        if ($create == true) {
            $endpoint = 'register?';
        } else {
            $endpoint = 'subscribe';
        }

        $store['key'] = $this->api_key;

        $params = [
            'query' => $store,
        ];

        return $this->client->apiCall($method, $endpoint, $params, $create);
    }

    /**
     * @param $store
     * @return mixed
     */
    public function deleteStore($store)
    {
        $method = 'POST';

        $endpoint = 'delete';

        $store['key'] = $this->api_key;

        $params = [
            'query' => $store,
        ];

        return $this->client->apiCall($method, $endpoint, $params);
    }

    /**
     * @param $store
     * @return mixed
     */
    public function suspendStore($store)
    {
        $method = 'GET';

        $endpoint = 'suspend';

        $store['key'] = $this->api_key;

        $params = [
            'query' => $store,
        ];

        return $this->client->apiCall($method, $endpoint, $params);
    }

    /**
     * @param $store
     * @return mixed
     */
    public function resumeStore($store)
    {
        $method = 'GET';

        $endpoint = 'resume';

        $store['key'] = $this->api_key;

        $params = [
            'query' => $store,
        ];

        return $this->client->apiCall($method, $endpoint, $params);
    }

    /**
     * @param $store
     * @return mixed
     */
    public function infoStore($store)
    {
        $method = 'GET';

        $endpoint = 'stores';

        $store['key'] = $this->api_key;

        $params = [
            'query' => $store,
        ];

        return $this->client->apiCall($method, $endpoint, $params);
    }

    /**
     * @param $store
     * @return mixed
     */
    public function statusStore($store)
    {
        $method = 'POST';

        $endpoint = 'status';

        $store['key'] = $this->api_key;

        $params = [
            'query' => $store,
        ];

        return $this->client->apiCall($method, $endpoint, $params);
    }

    /**
     * @param $store
     * @return mixed
     */
    public function list()
    {
        $method = 'POST';

        $endpoint = 'stores';

        $store['key'] = $this->api_key;

        $params = [
            'query' => $store,
        ];

        return $this->client->apiCall($method, $endpoint, $params);
    }
}
