<?php
// Headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: PUT");
header("Access-Control-Allow-Headers: 
        Access-Control-Allow-Headers,
        Access-Control-Allow-Origin,
        Content-Type,
        Access-Control-Allow-Methods,
        Authorization,
        X-Requested-With");

include_once "../../config/Database.php";
include_once "../../models/Product.php";

// Instantiate DB object and connect
$database = new Database();
$db = $database->connect();

// Instantiate product object
$product = new Product($db);

// Get raw posted data
$data = json_decode(file_get_contents("php://input"));

$product->id = $data->id;
$product->name = $data->name;
$product->description = $data->description;
$product->price = $data->price;
$product->category_id = $data->category_id;
$product->created = $data->created;
$product->modified = $data->modified;

// Update product
if($product->update()) {
    echo json_encode(
        array("message" => "Product updated")
    );
} else {
    echo json_encode(
        array("message" => "Product not updated")
    );
}