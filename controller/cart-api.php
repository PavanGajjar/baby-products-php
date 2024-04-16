<?php

include_once '../helper/db_connection.php';
include_once '../helper/session_check.php';
include_once '../business/cart_b.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $stmt = $conn->prepare("SELECT cart.cart_id, baby_products.product_id, baby_products.name, baby_products.description, baby_products.price, baby_products.image_url, cart.quantity
        FROM cart
        INNER JOIN baby_products ON cart.product_id = baby_products.product_id
        WHERE cart.user_id = ?");
    $stmt->bind_param("i", $_SESSION['user_id']);
    
    // Execute query
    $stmt->execute();
    
    // Get result set
    $result = $stmt->get_result();
    
    // Fetch cart items as an associative array
    $cartItems = [];
    while ($row = $result->fetch_assoc()) {
        $cartItems[] = $row;
    }
    
    // Close statement
    $stmt->close();
    echo json_encode($cartItems);
} else if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the product ID from the request body
    $data = json_decode(file_get_contents("php://input"), true);
    $productId = $data['productId'];

    // Validate product ID (You may need additional validation)
    if (!is_numeric($productId)) {
        http_response_code(400);
        echo json_encode(array("message" => "Invalid product ID"));
        exit;
    }

    // Begin a transaction to ensure data consistency
    mysqli_autocommit($conn, false);
    $success = true;

    // Check if the product is already in the cart
    $userId = $_SESSION['user_id'];
    $stmt = $conn->prepare("SELECT quantity FROM cart WHERE user_id = ? AND product_id = ?");
    $stmt->bind_param("ii", $userId, $productId);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        // If the product is already in the cart, update the quantity
        $stmt->bind_result($quantity);
        $stmt->fetch();
        $stmt->close();
        $stmt = $conn->prepare("UPDATE cart SET quantity = quantity + 1 WHERE user_id = ? AND product_id = ?");
        $stmt->bind_param("ii", $userId, $productId);
        if (!$stmt->execute()) {
            $success = false;
        }
        $updateProducts = $conn->prepare("UPDATE baby_products SET stock_quantity = stock_quantity - 1 WHERE product_id = ?");
        $updateProducts->bind_param("s", $productId);
        if (!$updateProducts->execute()) {
            $success = false;
        }
    } else {
        // If the product is not in the cart, insert it with quantity 1
        $stmt = $conn->prepare("INSERT INTO cart (user_id, product_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $userId, $productId);
        if (!$stmt->execute()) {
            $success = false;
        }
        $updateProducts = $conn->prepare("UPDATE baby_products SET stock_quantity = stock_quantity - 1 WHERE product_id = ?");
        $updateProducts->bind_param("s", $productId);
        if (!$updateProducts->execute()) {
            $success = false;
        }
    }

    if ($success) {
        // Commit the transaction if all operations succeed
        mysqli_commit($conn);
        http_response_code(201);
        echo json_encode(array("success" => true));
        
    } else {
        // Rollback the transaction if any operation fails
        mysqli_rollback($conn);
        http_response_code(500);
        echo json_encode(array("message" => "Failed to add product to cart"));
    }

    // Re-enable autocommit
    mysqli_autocommit($conn, true);
} else {
    // Method not allowed
    http_response_code(405);
    echo json_encode(array("message" => "Method Not Allowed"));
}
?>