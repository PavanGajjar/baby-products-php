<?php

include_once '../helper/db_connection.php';
include_once '../helper/session_check.php';
require_once '../business/cart_b.php';

if (isset($_GET['action']) && isset($_GET['cartId'])) {
    $action = $_GET['action'];
    $cartId = $_GET['cartId'];
    $product_id = $_GET['product_id'];

    // Create an instance of the Cart class
    $cart = new CartBusiness($conn);

    // Perform the appropriate action based on the action parameter
    switch ($action) {
        case 'remove':
            // Call the method to remove the cart item
            if (isset($_GET['item_quantity'])) {
                // Get quantity from the query parameters
                $quantity = $_GET['item_quantity'];
                // Call the method to adjust the quantity of the cart item
                $result = $cart->removeCartItem($cartId, $product_id, $quantity);
            } else {
                // Return JSON response indicating missing quantity parameter
                echo json_encode(['error' => 'Missing quantity']);
                exit;
            }
            break;
        case 'adjust':
            // Check if quantity is provided
            if (isset($_GET['quantity'])) {
                // Get quantity from the query parameters
                $quantity = $_GET['quantity'];
                // Call the method to adjust the quantity of the cart item
                $result = $cart->adjustCartItemQuantity($cartId, $quantity, $product_id);
            } else {
                // Return JSON response indicating missing quantity parameter
                echo json_encode(['error' => 'Missing quantity']);
                exit;
            }
            break;
        default:
            // Return JSON response indicating invalid action
            echo json_encode(['error' => 'Invalid action']);
            exit;
    }

    echo json_encode(['success' => $result]);
} else {
    echo json_encode(['error' => 'Missing action or cartId']);
}
?>
