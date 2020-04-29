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

// Query to read all products
$result = $product->read();
// Get row count
$num = $result->rowCount();

// Check if any products
if($num > 0) {
    // Products array of arrays to hold data for all products
    $products_arr = array();
    $products_arr["data"] = array();

    // Loop through results of query
    while($row = $result->fetch(PDO::FETCH_ASSOC)) {
        // Prevents having to do $row["name"] in favor of $name public member
        extract($row);

        // Create array that represents a singular product
        $product_item = array(
            "id" => $id,
            "name" => $name,
            "description" => $description,
            "price" => $price,
            "category_id" => $category_id,
            "category_name" => $category_name,
            "created" => $created,
            "modified" => $modified
        );

        // Push to "data"
        array_push($products_arr["data"], $product_item);
    }

    // Turn data array to json and output
    echo json_encode($products_arr);
} else {
    // No posts
    echo json_encode(
        array("message" => "No Posts Found")
    );
}
