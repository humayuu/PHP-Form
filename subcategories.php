<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sub Categories Management</title>
    <link rel="stylesheet" href="./assets/subcategory.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <div>
                <h1>Sub Categories Management</h1>
                <p>Create detailed subcategories under main categories</p>
            </div>
            <a href="index.php" class="back-btn">← Back to Dashboard</a>
        </div>

        <div class="content-wrapper">
            <div class="form-section">
                <h2>Add New Sub Category</h2>
                <form>
                    <div class="form-group">
                        <label for="parent-category">Parent Category *</label>
                        <select id="parent-category" name="parent-category" required>
                            <option value="">Select Parent Category</option>
                            <option value="electronics">Electronics</option>
                            <option value="clothing">Clothing</option>
                            <option value="home-garden">Home & Garden</option>
                            <option value="sports">Sports</option>
                            <option value="books">Books</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="subcategory-name">Sub Category Name *</label>
                        <input type="text" id="subcategory-name" name="subcategory-name" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="subcategory-description">Description</label>
                        <textarea id="subcategory-description" name="subcategory-description" placeholder="Describe this subcategory"></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="subcategory-slug">URL Slug</label>
                        <input type="text" id="subcategory-slug" name="subcategory-slug" placeholder="subcategory-name">
                    </div>
                    
                    <div class="form-group">
                        <label for="subcategory-status">Status</label>
                        <select id="subcategory-status" name="subcategory-status">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Add Sub Category</button>
                </form>
            </div>

            <div class="table-section">
                <h2>Existing Sub Categories</h2>
                <input type="text" class="search-box" placeholder="Search subcategories...">
                
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Sub Category</th>
                            <th>Parent Category</th>
                            <th>Products</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>001</td>
                            <td>Smartphones</td>
                            <td><span class="category-tag">Electronics</span></td>
                            <td>25</td>
                            <td><span class="status-active">Active</span></td>
                            <td>
                                <a href="#" class="btn btn-edit">Edit</a>
                                <a href="#" class="btn btn-danger">Delete</a>
                            </td>
                        </tr>
                        <tr>
                            <td>002</td>
                            <td>Laptops</td>
                            <td><span class="category-tag">Electronics</span></td>
                            <td>18</td>
                            <td><span class="status-active">Active</span></td>
                            <td>
                                <a href="#" class="btn btn-edit">Edit</a>
                                <a href="#" class="btn btn-danger">Delete</a>
                            </td>
                        </tr>
                        <tr>
                            <td>003</td>
                            <td>Men's Shirts</td>
                            <td><span class="category-tag">Clothing</span></td>
                            <td>42</td>
                            <td><span class="status-active">Active</span></td>
                            <td>
                                <a href="#" class="btn btn-edit">Edit</a>
                                <a href="#" class="btn btn-danger">Delete</a>
                            </td>
                        </tr>
                        <tr>
                            <td>004</td>
                            <td>Women's Dresses</td>
                            <td><span class="category-tag">Clothing</span></td>
                            <td>36</td>
                            <td><span class="status-active">Active</span></td>
                            <td>
                                <a href="#" class="btn btn-edit">Edit</a>
                                <a href="#" class="btn btn-danger">Delete</a>
                            </td>
                        </tr>
                        <tr>
                            <td>005</td>
                            <td>Kitchen Appliances</td>
                            <td><span class="category-tag">Home & Garden</span></td>
                            <td>15</td>
                            <td><span class="status-inactive">Inactive</span></td>
                            <td>
                                <a href="#" class="btn btn-edit">Edit</a>
                                <a href="#" class="btn btn-danger">Delete</a>
                            </td>
                        </tr>
                        <tr>
                            <td>006</td>
                            <td>Fiction</td>
                            <td><span class="category-tag">Books</span></td>
                            <td>34</td>
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