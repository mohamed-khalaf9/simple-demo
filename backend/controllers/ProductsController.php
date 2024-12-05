<?php

include_once 'db.php';
include_once 'pdos/ProductsPdo.php';

class ProductsController{
    private $productPdo;

    function __construct()
    {
        $db = new Database();
        $pdo = $db->getConnection();
        $this->productPdo = new ProductsPdo($pdo);
    }

    function processRequest($method,$userId,$resource,$id,$data)
    {
        if($method=="GET")
        {
            $this->getProductsOfUser($userId);


        }
        else if($method=="POST")
        {
            $this->createProduct($data,$userId);

        }
        else if($method=="PUT")
        {
            $this->updateProduct($data,$userId);

        }
        else if($method=="DELETE")
        {
            $this->deleteProducts($userId);

        }
    }

    function getProductsOfUser($userId){
        if (!$userId) {
            HttpResponse::send(400, null, ['message' => 'User ID is missing']);
            return;
        }

        $products = $this->productPdo->getProductsByUserId($userId);

        if (empty($products)) {
            HttpResponse::send(404, null, ['message' => 'No products found for this user']);
        } else {
            HttpResponse::send(200, null, $products);
        }


    }
    function createProduct($data,$userId){

        if (!$userId) {
            HttpResponse::send(400, null, ['message' => 'User ID is missing']);
            return;
        }

        $name = $data['name'] ?? null;
        $price = $data['price'] ?? null;

        if (!$name || !$price) {
            HttpResponse::send(400, null, ['message' => 'Name and price are required']);
            return;
        }

        $isCreated = $this->productPdo->createProduct($name, $price, $userId);

        if ($isCreated) {
            HttpResponse::send(201, null, ['message' => 'Product created successfully']);
        } else {
            HttpResponse::send(500, null, ['message' => 'Failed to create product']);
        }

    }

    function updateProduct($data,$userId){
        if (!$userId) {
            HttpResponse::send(400, null, ['message' => 'User ID is missing']);
            return;
        }

        $idOfProduct = $data['id'] ?? null;
        $name = $data['name'] ?? null;
        $price = $data['price'] ?? null;

        if (!$idOfProduct || !$name || !$price) {
            HttpResponse::send(400, null, ['message' => 'Product ID, name, and price are required']);
            return;
        }

        $isUpdated = $this->productPdo->updateProductByUserId($idOfProduct, $name, $price, $userId);

        if ($isUpdated) {
            HttpResponse::send(200, null, ['message' => 'Product updated successfully']);
        } else {
            HttpResponse::send(500, null, ['message' => 'Failed to update product']);
        }


    }
    function deleteProducts($userId)
    {
        if (!$userId) {
            HttpResponse::send(400, null, ['message' => 'User ID is missing']);
            return;
        }

        $isDeleted = $this->productPdo->deleteProductsByUserId($userId);

        if ($isDeleted) {
            HttpResponse::send(200, null, ['message' => 'Products deleted successfully']);
        } else {
            HttpResponse::send(500, null, ['message' => 'Failed to delete products']);
        }

    }




























}



















?>