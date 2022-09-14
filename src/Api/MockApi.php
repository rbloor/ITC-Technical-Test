<?php

namespace App\Api;

class MockApi extends Api
{
    protected $baseUri = 'https://www.itccompliance.co.uk/recruitment-webservice/api/';

    public function getProducts()
    {
        $response = $this->client->get('list');
        $response->getBody()->rewind();

        return $response;
    }

    public function getProductInfo(String $id)
    {
        $response = $this->client->get('info?id='.$id);
        $response->getBody()->rewind();

        return $response;
    }
}
