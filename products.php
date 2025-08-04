<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products Management</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            color: #333;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            background: #2c3e50;
            color: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .back-btn {
            color: white;
            text-decoration: none;
            padding: 8px 16px;
            background: rgba(255,255,255,0.2);
            border-radius: 5px;
            transition: background 0.3s;
        }

        .back-btn:hover {
            background: rgba(255,255,255,0.3);
        }

        .content-wrapper {
            display: grid;
            grid-template-columns: 400px 1fr;
            gap: 30px;
        }

        .form-section {
            background: white;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            height: fit-content;
        }

        .form-section h2 {
            margin-bottom: 20px;
            color: #2c3e50;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #555;
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 5px;
            font-size: 14px;
            transition: border-color 0.3s;
        }

        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            outline: none;
            border-color: #3498db;
        }

        .form-group textarea {
            resize: vertical;
            height: 100px;
        }

        .price-group {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }

        .btn-primary {
            background: #3498db;
            color: white;
        }

        .btn-primary:hover {
            background: #2980b9;
        }

        .btn-danger {
            background: #e74c3c;
            color: white;
            padding: 6px 12px;
            font-size: 12px;
        }

        .btn-danger:hover {
            background: #c0392b;
        }

        .btn-edit {
            background: #f39c12;
            color: white;
            padding: 6px 12px;
            font-size: 12px;
            margin-right: 8px;
        }

        .btn-edit:hover {
            background: #e67e22;
        }

        .table-section {
            background: white;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .table-section h2 {
            margin-bottom: 20px;
            color: #2c3e50;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .table th,
        .table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #e0e0e0;
        }

        .table th {
            background: #f8f9fa;
            font-weight: 600;
            color: #555;
        }

        .table tr:hover {
            background: #f8f9fa;
        }

        .search-filters {
            display: grid;
            grid-template-columns: 1fr 200px 200px;
            gap: 15px;
            margin-bottom: 20px;
        }

        .search-box,
        .filter-select {
            padding: 10px;
            border: 2px solid #e0e0e0;
            border-radius: 5px;
        }

        .search-box:focus,
        .filter-select:focus {
            outline: none;
            border-color: #3498db;
        }

        .status-active {
            color: #27ae60;
            font-weight: 500;
        }

        .status-inactive {
            color: #e74c3c;
            font-weight: 500;
        }

        .status-draft {
            color: #f39c12;
            font-weight: 500;
        }

        .product-image {
            width: 40px;
            height: 40px;
            border-radius: 4px;
            object-fit: cover;
        }

        .price {
            font-weight: 600;
            color: #27ae60;
        }

        .category-tag {
            background: #ecf0f1;
            color: #2c3e50;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 500;
        }

        .stock-low {
            color: #e74c3c;
            font-weight: 500;
        }

        .stock-good {
            color: #27ae60;
            font-weight: 500;
        }

        @media (max-width: 1200px) {
            .content-wrapper {
                grid-template-columns: 1fr;
            }
        }
        
        @media (max-width: 768px) {
            .header {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }
            
            .search-filters {
                grid-template-columns: 1fr;
            }
            
            .price-group {
                grid-template-columns: 1fr;
            }
        }
    </style>
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
                            <label for="product-sale-price">Sale Price</label>
                            <input type="number" id="product-sale-price" name="product-sale-price" step="0.01">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="product-stock">Stock Quantity *</label>
                        <input type="number" id="product-stock" name="product-stock" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="product-sku">SKU</label>
                        <input type="text" id="product-sku" name="product-sku" placeholder="Product SKU">
                    </div>
                    
                    <div class="form-group">
                        <label for="product-image">Product Image URL</label>
                        <input type="url" id="product-image" name="product-image" placeholder="https://example.com/image.jpg">
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
                            <th>Image</th>
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
                            <td><img src="https://via.placeholder.com/40" alt="Product" class="product-image"></td>
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
                        <tr>
                            <td><img src="https://via.placeholder.com/40" alt="Product" class="product-image"></td>
                            <td>Galaxy S24 Ultra</td>
                            <td><span class="category-tag">Electronics</span></td>
                            <td>Samsung</td>
                            <td class="price">$1199.00</td>
                            <td class="stock-low">5</td>
                            <td><span class="status-active">Active</span></td>
                            <td>
                                <a href="#" class="btn btn-edit">Edit</a>
                                <a href="#" class="btn btn-danger">Delete</a>
                            </td>
                        </tr>
                        <tr>
                            <td><img src="https://via.placeholder.com/40" alt="Product" class="product-image"></td>
                            <td>Nike Air Max 270</td>
                            <td><span class="category-tag">Sports</span></td>
                            <td>Nike</td>
                            <td class="price">$150.00</td>
                            <td class="stock-good">23</td>
                            <td><span class="status-active">Active</span></td>
                            <td>
                                <a href="#" class="btn btn-edit">Edit</a>
                                <a href="#" class="btn btn-danger">Delete</a>
                            </td>
                        </tr>
                        <tr>
                            <td><img src="https://via.placeholder.com/40" alt="Product" class="product-image"></td>
                            <td>MacBook Pro 16"</td>
                            <td><span class="category-tag">Electronics</span></td>
                            <td>Apple</td>
                            <td class="price">$2399.00</td>
                            <td class="stock-good">12</td>
                            <td><span class="status-draft">Draft</span></td>
                            <td>
                                <a href="#" class="btn btn-edit">Edit</a>
                                <a href="#" class="btn btn-danger">Delete</a>
                            </td>
                        </tr>
                        <tr>
                            <td><img src="https://via.placeholder.com/40" alt="Product" class="product-image"></td>
                            <td>Cotton T-Shirt</td>
                            <td><span class="category-tag">Clothing</span></td>
                            <td>-</td>
                            <td class="price">$25.00</td>
                            <td class="stock-good">89</td>
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