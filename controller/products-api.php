<?php
// Include your database connection file
include_once '../helper/db_connection.php';
include_once '../helper/session_check.php';

// Function to get all baby products
function getBabyProducts() {
    global $conn;
    $stmt = $conn->query("SELECT * FROM baby_products");
    $products = array();
    while ($row = $stmt->fetch_assoc()) {
        $products[] = $row;
    }
    return $products;
}

// Handle API requests
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // Get all baby products
    $babyProducts = getBabyProducts();
    echo json_encode($babyProducts);
} else {
    // Method not allowed
    http_response_code(405);
    echo json_encode(array("message" => "Method Not Allowed"));
}
?>
