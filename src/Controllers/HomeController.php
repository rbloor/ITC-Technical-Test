<?php

namespace App\Controllers;

use App\Api\MockApi;
use App\Helpers\Utilities;

class HomeController
{
    public function homeAction()
    {
        $api = new MockApi(null);

        // Get all products.
        $response = $api->getProducts();
        $contents = $response->getBody()->getContents();
        $products = json_decode($contents)->products;

        // Get all product information.
        $productDetails = [];
        foreach ($products as $id => $name) {
            $response = $api->getProductInfo($id);
            $contents = $response->getBody()->getContents();
            $productInfoResponse = json_decode($contents);
            $productInfo = $productInfoResponse->$id;

            $productSuppliers = property_exists($productInfo, 'suppliers') ? array_map('App\Helpers\Utilities::clean', $productInfo->suppliers) : [];

            $productDetails[] = [
                'id' => Utilities::clean($id),
                'name' => Utilities::clean($productInfo->name ?? ''),
                'description' => Utilities::clean($productInfo->description ?? ''),
                'type' => Utilities::clean($productInfo->type ?? ''),
                'suppliers' => $productSuppliers
            ];
        }

        include realpath(__DIR__) . '/../Views/homeView.php';
    }
}
