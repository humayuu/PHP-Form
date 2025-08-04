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
        <h2>Add New Brand</h2>
        <form>
            <div class="form-group">
                <label for="brand-name">Brand Name *</label>
                <input type="text" id="brand-name" name="brand-name" required>
            </div>

            <div class="form-group">
                <label for="brand-description">Description</label>
                <textarea id="brand-description" name="brand-description" placeholder="Brief description of the brand"></textarea>
            </div>

            <div class="form-group">
                <label for="brand-website">Website URL</label>
                <input type="url" id="brand-website" name="brand-website" placeholder="https://example.com">
            </div>

            <div class="form-group">
                <label for="brand-logo">Logo URL</label>
                <input type="url" id="brand-logo" name="brand-logo" placeholder="https://example.com/logo.jpg">
            </div>

            <button type="submit" class="btn btn-primary">Add Brand</button>
        </form>
    </div>

    <div class="table-section">
        <h2>Existing Brands</h2>
        <input type="text" class="search-box" placeholder="Search brands...">

        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Brand Name</th>
                    <th>Website</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>001</td>
                    <td>Nike</td>
                    <td>nike.com</td>
                    <td>Active</td>
                    <td>
                        <a href="#" class="btn btn-edit">Edit</a>
                        <a href="#" class="btn btn-danger">Delete</a>
                    </td>
                </tr>
                <tr>
                    <td>002</td>
                    <td>Adidas</td>
                    <td>adidas.com</td>
                    <td>Active</td>
                    <td>
                        <a href="#" class="btn btn-edit">Edit</a>
                        <a href="#" class="btn btn-danger">Delete</a>
                    </td>
                </tr>
                <tr>
                    <td>003</td>
                    <td>Samsung</td>
                    <td>samsung.com</td>
                    <td>Active</td>
                    <td>
                        <a href="#" class="btn btn-edit">Edit</a>
                        <a href="#" class="btn btn-danger">Delete</a>
                    </td>
                </tr>
                <tr>
                    <td>004</td>
                    <td>Apple</td>
                    <td>apple.com</td>
                    <td>Active</td>
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
</html>
