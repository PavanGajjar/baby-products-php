<!-- view/products.php -->

<!DOCTYPE html>
<html lang="en">
<?php include './layout/head.html'; ?>
<body>
    <?php include './layout/navbar.html'; ?>
    <div class="container">
        <h1 class="mt-5 mb-4">Baby Products</h1>
        <div class="row" id="products-container">
            <!-- Product data will be dynamically added here -->
        </div>
    </div>

    <script>
        // Function to fetch products data from the API

        const getProducts = async () => {
            await fetch('controller/products-api.php')
                .then(response => response.json())
                .then(products => {
                    const productsContainer = document.getElementById('products-container');
                    productsContainer.innerHTML = ''; // Clear existing products
                    products.forEach(product => {
                        const productCol = document.createElement('div');
                        productCol.classList.add('col-md-4');
                        productCol.innerHTML = `
                            <div class="card product">
                                <div class="card-body">
                                    <h5 class="card-title">${product.name}</h5>
                                    <p class="card-text">${product.description}</p>
                                    <p class="card-text">Price: $${product.price}</p>
                                    <p class="card-text">Stock Quantity: ${product.stock_quantity}</p>
                                    <button id="add-to-cart" class="btn btn-primary add-to-cart" data-product-id="${product.product_id}" ${product.stock_quantity === 0 ? 'disabled' : ''}>Add to Cart</button>
                                </div>
                            </div>
                        `;
                        productsContainer.appendChild(productCol);
                    });
                })
                .catch(error => console.error('Error fetching products:', error));
        }

        const addToCart = (event) => {
            // Function to handle add-to-cart button click
                const productId = event.target.getAttribute('data-product-id');
                fetch('controller/cart-api.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ productId })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Product added to cart successfully.');
                        getProducts();
                    } else {
                        alert('Failed to add product to cart. Please try again.');
                    }
                })
                .catch(error => console.error('Error adding product to cart:', error));
        }

        document.addEventListener('DOMContentLoaded', async function() {
            await getProducts(event);

            document.getElementById("add-to-cart").addEventListener('click', addToCart);
        });
    </script>
</body>
</html>
