<?php

class CartBusiness {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getCartItems($userId) {
        $stmt = $this->conn->prepare("SELECT * FROM cart WHERE user_id = ?");
        $stmt->bind_param("i", $userId);
        
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
        
        return $cartItems;
    }

    public function adjustCartItemQuantity($cartId, $quantity, $product_id) {
        // Prepare the SQL statement
        $sql = "UPDATE cart SET quantity = quantity - ? WHERE cart_id = ?";
        $stmt = $this->conn->prepare($sql);

        // Bind parameters
        $stmt->bind_param("ii", $quantity, $cartId);

        // Execute the statement
        $result = $stmt->execute();

        // Close the statement
        $stmt->close();
        $result = $this->setBabyProductsQuantity($quantity);
        return $result;
    }

    // Method to remove a cart item
    public function removeCartItem($cartId, $product_id, $quantity) {
        // Prepare the SQL statement
        $sql = "DELETE FROM cart WHERE cart_id = ?";
        $stmt = $this->conn->prepare($sql);

        // Bind parameters
        $stmt->bind_param("i", $cartId);

        // Execute the statement
        $result = $stmt->execute();

        // Close the statement
        $stmt->close();

        $result = $this->setBabyProductsQuantity($product_id, $quantity);
        return $result;
    }

    public function setBabyProductsQuantity($product_id, $quantity)
    {
        $quary = "UPDATE baby_products SET stock_quantity = stock_quantity + ? WHERE product_id = ?";
        $quaryStmt = $this->conn->prepare($quary);

        // Bind parameters
        $quaryStmt->bind_param("ii", $quantity, $product_id);

        // Execute the statement
        $result = $quaryStmt->execute();

        // Close the statement
        $quaryStmt->close();
    }
}
?>