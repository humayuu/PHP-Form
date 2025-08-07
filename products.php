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
                <form>
                    <div class="form-group">
                        <label for="product-name">Product Name *</label>
                        <input type="text" id="product-name" name="product-name" required>
                    </div>

                    <div class="form-group">
                        <label for="product-description">Description</label>
                        <textarea id="product-description" name="product-description" placeholder="Product description"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="product-brand">Brand</label>
                        <select id="product-brand" name="product-brand">
                            <option value="">Select Brand</option>
                            <option value="nike">Nike</option>
                            <option value="adidas">Adidas</option>
                            <option value="samsung">Samsung</option>
                            <option value="apple">Apple</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="product-category">Category *</label>
                        <select id="product-category" name="product-category" required>
                            <option value="">Select Category</option>
                            <option value="electronics">Electronics</option>
                            <option value="clothing">Clothing</option>
                            <option value="home-garden">Home & Garden</option>
                            <option value="sports">Sports</option>
                            <option value="books">Books</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="product-subcategory">Sub Category</label>
                        <select id="product-subcategory" name="product-subcategory">
                            <option value="">Select Sub Category</option>
                            <option value="smartphones">Smartphones</option>
                            <option value="laptops">Laptops</option>
                            <option value="mens-shirts">Men's Shirts</option>
                            <option value="womens-dresses">Women's Dresses</option>
                        </select>
                    </div>

                    <div class="price-group">
                        <div class="form-group">
                            <label for="product-price">Price *</label>
                            <input type="number" id="product-price" name="product-price" step="0.01" required>
                        </div>

                        <div class="form-group">
                            <label for="product-sale-price">Discount Price</label>
                            <input type="number" id="product-sale-price" name="product-sale-price" step="0.01">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="product-stock">Stock Quantity *</label>
                        <input type="number" id="product-stock" name="product-stock" required>
                    </div>

                    <div class="form-group">
                        <label for="product-status">Status</label>
                        <select id="product-status" name="product-status">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                            <option value="draft">Draft</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">Add Product</button>
                </form>
            </div>

            <div class="table-section">
                <h2>Existing Products</h2>

                <div class="search-filters">
                    <input type="text" class="search-box" placeholder="Search products...">
                    <select class="filter-select">
                        <option value="">All Categories</option>
                        <option value="electronics">Electronics</option>
                        <option value="clothing">Clothing</option>
                        <option value="home-garden">Home & Garden</option>
                        <option value="sports">Sports</option>
                        <option value="books">Books</option>
                    </select>
                    <select class="filter-select">
                        <option value="">All Status</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                        <option value="draft">Draft</option>
                    </select>
                </div>

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
                            <td>iPhone 15 Pro</td>
                            <td><span class="category-tag">Electronics</span></td>
                            <td>Apple</td>
                            <td class="price">$999.00</td>
                            <td class="stock-good">45</td>
                            <td><span class="status-active">Active</span></td>
                            <td>
                                <a href="#" class="btn btn-edit">Edit</a>
                                <a href="#" class="btn btn-danger">Delete</a>
                            </td>
                        </tr>   
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>

</html>