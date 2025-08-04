<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categories Management</title>
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
            max-width: 1200px;
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
            grid-template-columns: 1fr 2fr;
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
            height: 80px;
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

        .search-box {
            width: 300px;
            padding: 10px;
            border: 2px solid #e0e0e0;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .search-box:focus {
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

        @media (max-width: 768px) {
            .content-wrapper {
                grid-template-columns: 1fr;
            }
            
            .header {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div>
                <h1>Categories Management</h1>
                <p>Organize products into main categories</p>
            </div>
            <a href="index.php" class="back-btn">← Back to Dashboard</a>
        </div>

        <div class="content-wrapper">
            <div class="form-section">
                <h2>Add New Category</h2>
                <form>
                    <div class="form-group">
                        <label for="category-name">Category Name *</label>
                        <input type="text" id="category-name" name="category-name" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="category-description">Description</label>
                        <textarea id="category-description" name="category-description" placeholder="Describe this category"></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="category-slug">URL Slug</label>
                        <input type="text" id="category-slug" name="category-slug" placeholder="category-name">
                    </div>
                    
                    <div class="form-group">
                        <label for="category-image">Category Image URL</label>
                        <input type="url" id="category-image" name="category-image" placeholder="https://example.com/image.jpg">
                    </div>
                    
                    <div class="form-group">
                        <label for="category-status">Status</label>
                        <select id="category-status" name="category-status">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Add Category</button>
                </form>
            </div>

            <div class="table-section">
                <h2>Existing Categories</h2>
                <input type="text" class="search-box" placeholder="Search categories...">
                
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Category Name</th>
                            <th>Slug</th>
                            <th>Products Count</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>001</td>
                            <td>Electronics</td>
                            <td>electronics</td>
                            <td>45</td>
                            <td><span class="status-active">Active</span></td>
                            <td>
                                <a href="#" class="btn btn-edit">Edit</a>
                                <a href="#" class="btn btn-danger">Delete</a>
                            </td>
                        </tr>
                        <tr>
                            <td>002</td>
                            <td>Clothing</td>
                            <td>clothing</td>
                            <td>78</td>
                            <td><span class="status-active">Active</span></td>
                            <td>
                                <a href="#" class="btn btn-edit">Edit</a>
                                <a href="#" class="btn btn-danger">Delete</a>
                            </td>
                        </tr>
                        <tr>
                            <td>003</td>
                            <td>Home & Garden</td>
                            <td>home-garden</td>
                            <td>32</td>
                            <td><span class="status-active">Active</span></td>
                            <td>
                                <a href="#" class="btn btn-edit">Edit</a>
                                <a href="#" class="btn btn-danger">Delete</a>
                            </td>
                        </tr>
                        <tr>
                            <td>004</td>
                            <td>Sports</td>
                            <td>sports</td>
                            <td>23</td>
                            <td><span class="status-inactive">Inactive</span></td>
                            <td>
                                <a href="#" class="btn btn-edit">Edit</a>
                                <a href="#" class="btn btn-danger">Delete</a>
                            </td>
                        </tr>
                        <tr>
                            <td>005</td>
                            <td>Books</td>
                            <td>books</td>
                            <td>67</td>
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