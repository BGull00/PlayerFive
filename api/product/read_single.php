<?php
// Headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

include_once "../../config/Database.php";
include_once "../../models/Product.php";

// Instantiate DB object and connect
$database = new Database();
$db = $database->connect();

// Instantiate product object
$product = new Product($db);

// Get ID
$product->id = isset($_GET["id"]) ? $_GET["id"] : die();

// Get product
$product->read_single();

// Create array
$product_arr = array(
    "id" => $product->id,
    "name" => $product->name,
    "description" => $product->description,
    "price" => $product->price,
    "category_id" => $product->category_id,
    "category_name" => $product->category_name,
    "created" => $product->created,
    "modified" => $product->modified
);

print_r(json_encode($product_arr));