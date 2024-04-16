
<!DOCTYPE html>
<html lang="en">
<?php include './layout/head.html'; 
include_once 'helper/db_connection.php';
include_once 'helper/session_check.php';
?>
<body>
    <?php include './layout/navbar.html'; ?>
    <div class="container">
        <h1 class="mt-5 mb-4">Cart Items</h1>
        <div class="row" id="cart-items-container">
            <!-- Cart data will be dynamically added here -->
        </div>
    </div>
    <script>
        function fetchCartItems() {
            fetch("controller/cart-api.php")
                .then(response => response.json())
                .then(cartItems => {
                    const cartItemsContainer = document.getElementById('cart-items-container');
                    cartItemsContainer.innerHTML = '';
                    cartItems.forEach(item => {
                        const cartItemDiv = document.createElement('div');
                        cartItemDiv.classList.add('card', 'mb-3');
                        cartItemDiv.innerHTML = `
                            <div class="card-body">
                                <button class="btn btn-danger mr-2 float-end" onclick="removeCartItem(${item.cart_id}, ${item.product_id}, ${item.quantity})">Remove</button>
                                <h5 class="card-title">${item.name}</h5>
                                <p class="card-text">Description: ${item.description}</p>
                                <p class="card-text">Price: $${item.price}</p>
                                <p class="card-text">Quantity: ${item.quantity}</p>
                                <input type="number" min="1" class="form-control d-inline-block" id="quantity-${item.cart_id}" value="${item.quantity}">
                                <button class="btn btn-primary" onclick="adjustCartItemQuantity(${item.cart_id}, ${item.quantity}, ${item.product_id})">Update Quantity</button>
                            </div>
                        `;
                        cartItemsContainer.appendChild(cartItemDiv);
                    });
                })
                .catch(error => console.error('Error fetching cart items:', error));
        }

        function removeCartItem(cartId, productId, itemQuantity) {
            fetch(`controller/cart-adjust-api.php?action=remove&cartId=${cartId}&product_id=${productId}&item_quantity=${itemQuantity}`)
                .then(response => response.json())
                .then(data => {
                    fetchCartItems();
                    alert("Item removed succesfully");
                })
            .catch(error => console.error('Error removing cart item:', error));
        }

        function adjustCartItemQuantity(cartId, availableQuantity, productId) {
            const newQuantity = document.getElementById(`quantity-${cartId}`).value;
            if(newQuantity > availableQuantity) {
                alert('new quantity must be less then cart quantity.');
            } else if(newQuantity == 1 && availableQuantity == 1) {
                removeCartItem(cartId);
            } else {
                fetch(`controller/cart-adjust-api.php?action=adjust&cartId=${cartId}&quantity=${newQuantity}&product_id=${productId}`)
                    .then(response => response.json())
                    .then(data => {
                        fetchCartItems();
                    })
                    .catch(error => console.error('Error adjusting cart item quantity:', error));
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            fetchCartItems();
        });
    </script>
</body>
</html>
