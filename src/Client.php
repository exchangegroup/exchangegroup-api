<?php

namespace ExchangeGroup;

class Client
{
    const URL = 'www.bikeexchange.com.au';

    private $user;
    private $password;
    public $client;

    public function __construct($username, $password, $client=null)
    {
        $this->user = $username;
        $this->password = $password;
        $this->client = ($client) ? $client : new \GuzzleHttp\Client();
    }

    /**
     * @return  Array    All adverts for the account
     */
    public function getAdverts()
    {
        $data = $this->get('/api/v1/client/adverts');
        return isset($data['adverts'])
            ? $this->collectionToArray($data['adverts'])
            : array();
    }

    /**
     * @return  Object  Single advert data
     */
    public function getAdvert($id)
    {
        return (array) $this->get('/api/v1/client/adverts/' . $id);
    }

    public function updateAdvert($id, $data)
    {
        return (array) $this->put('/api/v1/client/adverts/' . $id, $data);
    }

    /**
     * @return  Array    All variants for the account
     */
    public function getVariants()
    {
        $data = $this->get('/api/v1/client/variants');

        return isset($data['variants'])
            ? $this->collectionToArray($data['variants'])
            : array();
    }

    /**
     * @return  Object  Single variation data
     */
    public function getVariant($id)
    {
        return (array) $this->get('/api/v1/client/variants/' . $id);
    }

    public function updateVariant($id, $data)
    {
        return (array) $this->put('/api/v1/client/variants/' . $id, $data);
    }

    public function collectionToArray($objCollection)
    {
        $arr = [];
        foreach($objCollection as $key=>$obj) {
            $obj['id'] = $key;
            $arr []= $obj;
        }
        return $arr;
    }

    public function get($path)
    {
        $json = $this->client->get($this->url($path))->json();
        if (isset($json->errors)) {
            throw new ClientException($json->errors);
        }

        return $json;
    }

    public function put($path, $data)
    {
        $json = $this->client->put($this->url($path), ['json' => $data])->json();
        if (isset($json->errors)) {
            throw new ClientException($json->errors);
        }

        return $json;
    }

    private function url($path)
    {
        return 'https://' . $this->user . ':' . $this->password . '@' . self::URL . $path;
    }
}
