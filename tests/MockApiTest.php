<?php

namespace Tests;

use App\Api\MockApi;
use App\Factories\LoggerFactory;
use GuzzleHttp\Exception\RequestException;
use PHPUnit\Framework\TestCase as PhpUnitTestCase;

class MockApiTest extends PhpUnitTestCase
{
    private $logger;

    public function setUp(): void
    {
        $this->logger = (new LoggerFactory())->create('testing-getting-products-and-information');
    }

    public function test_get_products()
    {
        $api = new MockApi($this->logger);

        $response = $api->getProducts();
        $contents = $response->getBody()->getContents();
        $data = json_decode($contents, true);

        $this->assertCount(7, $data['products']);
    }

    public function test_get_product_information()
    {
        $api = new MockApi($this->logger);

        $response = $api->getProducts();
        $contents = $response->getBody()->getContents();
        $products = json_decode($contents, true);
        $firstProductId = array_key_first(reset($products));

        $response = $api->getProductInfo($firstProductId);
        $contents = $response->getBody()->getContents();
        $productInfoResponse = json_decode($contents, true);

        $this->assertEquals(array_key_first($productInfoResponse), $firstProductId);
    }
}
