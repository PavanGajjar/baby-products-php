<?php
class BabyProducts {
    private $conn;

    public function __construct($db_conn) {
        $this->conn = $db_conn;
    }

    function getBabyProducts() {
        $stmt = $this->conn->prepare("SELECT * FROM baby_products");
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    function createBabyProduct($name, $description, $price, $image_url, $stock_quantity) {
        $stmt = $this->$conn->prepare("INSERT INTO baby_products (name, description, price, image_url, stock_quantity) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([$name, $description, $price, $image_url, $stock_quantity]);
    }

    function updateBabyProduct($product_id, $name, $description, $price, $image_url, $stock_quantity) {
        $stmt = $this->$conn->prepare("UPDATE baby_products SET name = ?, description = ?, price = ?, image_url = ?, stock_quantity = ? WHERE product_id = ?");
        return $stmt->execute([$name, $description, $price, $image_url, $stock_quantity, $product_id]);
    }

    function deleteBabyProduct($product_id) {
        $stmt = $this->$conn->prepare("DELETE FROM baby_products WHERE product_id = ?");
        return $stmt->execute([$product_id]);
    }
}
?>