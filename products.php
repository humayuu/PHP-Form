<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products Management</title>
    <link rel="stylesheet" href="./assets/product.css">
</head>

<body>
    <div class="container">
        <div class="header">
            <div>
                <h1>Products Management</h1>
                <p>Add, edit, and manage your product catalog</p>
            </div>
            <a href="index.php" class="back-btn">← Back to Dashboard</a>
        </div>

        <div class="content-wrapper">
            <div class="form-section">
                <h2>Add New Product</h2>

                <form method="POST" action="#">
                    <input type="hidden" name="csrf_token" value="">
                    <div class="form-group">
                        <label for="product-name">Product Name *</label>
                        <input type="text" id="product-name" name="product_name">
                    </div>

                    <div class="form-group">
                        <label for="product-description">Description</label>
                        <textarea id="product-description" name="product_description" placeholder="Product description"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="product-brand">Brand</label>
                        <select id="product-brand" name="brand">
                            <option value="" disabled selected>Select Brand</option>
                            <option value="1">Nike</option>
                            <option value="2">Adidas</option>
                            <option value="3">Puma</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="product-category">Category *</label>
                        <select id="product-category" name="category">
                            <option value="" selected disabled>Select Category</option>
                            <option value="1">Electronics</option>
                            <option value="2">Clothing</option>
                            <option value="3">Books</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="subcategory">Sub Category</label>
                        <select id="subcategory" name="subcategory">
                            <option value="" selected disabled>Select Sub Category</option>
                            <option value="1">Smartphones</option>
                            <option value="2">Laptops</option>
                            <option value="3">Headphones</option>
                        </select>
                    </div>

                    <div class="price-group">
                        <div class="form-group">
                            <label for="product-price">Price *</label>
                            <input type="number" id="product-price" name="product_price" step="0.01">
                        </div>

                        <div class="form-group">
                            <label for="product-sale-price">Discount Price</label>
                            <input type="number" id="product-sale-price" name="discount_price" step="0.01">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="product-stock">Stock Quantity *</label>
                        <input type="number" id="product-stock" name="product_stock">
                    </div>

                    <div class="form-group">
                        <label for="product-status">Status</label>
                        <select id="product-status" name="product_status">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                            <option value="draft">Draft</option>
                        </select>
                    </div>

                    <button type="submit" name="submit" class="btn btn-primary">Add Product</button>
                </form>

            </div>

            <div class="table-section">
                <h2>Existing Products</h2>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Product Name</th>
                            <th>Category</th>
                            <th>Brand</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>iPhone 13 Pro</td>
                            <td><span class="category-tag">Electronics</span></td>
                            <td>Apple</td>
                            <td class="price">$999.00</td>
                            <td class="stock-good">25</td>
                            <td>
                                <span class="status-active">active</span>
                            </td>
                            <td>
                                <a href="#" class="btn btn-edit">Edit</a>
                                <a href="#" onclick="return confirm('Are you sure?')" class="btn btn-danger">Delete</a>
                            </td>
                        </tr>
                        <tr>
                            <td>Nike Air Jordan</td>
                            <td><span class="category-tag">Clothing</span></td>
                            <td>Nike</td>
                            <td class="price">$150.00</td>
                            <td class="stock-good">12</td>
                            <td>
                                <span class="status-active">active</span>
                            </td>
                            <td>
                                <a href="#" class="btn btn-edit">Edit</a>
                                <a href="#" onclick="return confirm('Are you sure?')" class="btn btn-danger">Delete</a>
                            </td>
                        </tr>
                        <tr>
                            <td>MacBook Pro</td>
                            <td><span class="category-tag">Electronics</span></td>
                            <td>Apple</td>
                            <td class="price">$1299.00</td>
                            <td class="stock-bad">0</td>
                            <td>
                                <span class="status-inactive">inactive</span>
                            </td>
                            <td>
                                <a href="#" class="btn btn-edit">Edit</a>
                                <a href="#" onclick="return confirm('Are you sure?')" class="btn btn-danger">Delete</a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</body>

</html>