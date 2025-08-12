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

                <form method="post" action="#">
                    <input type="hidden" name="csrf_token" value="">
                    <div class="form-group">
                        <label for="parent-category">Parent Category *</label>
                        <select id="parent-category" name="category">
                            <option value="" selected disabled>Select Parent Category</option>
                            <option value="1">Electronics</option>
                            <option value="2">Clothing</option>
                            <option value="3">Books</option>
                            <option value="4">Home & Garden</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="subcategory-name">Sub Category Name *</label>
                        <input type="text" id="subcategory-name" name="subcategory_name">
                    </div>

                    <div class="form-group">
                        <label for="subcategory-description">Description</label>
                        <textarea id="subcategory-description" name="subcategory_description" placeholder="Describe this subcategory"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="subcategory-slug">URL Slug</label>
                        <input type="text" id="subcategory-slug" name="subcategory_slug" placeholder="subcategory-name">
                    </div>

                    <div class="form-group">
                        <label for="subcategory-status">Status</label>
                        <select id="subcategory-status" name="subcategory_status">
                            <option value="" disabled selected>Select Sub Category Status</option>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>

                    <button type="submit" name="submit" class="btn btn-primary">Add Sub Category</button>
                </form>

            </div>

            <div class="table-section">
                <h2>Existing Sub Categories</h2>
                <input type="text" class="search-box" placeholder="Search subcategories...">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Category</th>
                            <th>Sub Category</th>
                            <th>Products</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>Electronics</td>
                            <td><span class="category-tag">Smartphones</span></td>
                            <td>25</td>
                            <td>
                                <span class="status-active">
                                    Active
                                </span>
                            </td>
                            <td>
                                <a href="#" class="btn btn-edit">Edit</a>
                                <a href="#" onclick="return confirm('Are You Sure')" class="btn btn-danger">Delete</a>
                            </td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Electronics</td>
                            <td><span class="category-tag">Laptops</span></td>
                            <td>18</td>
                            <td>
                                <span class="status-active">
                                    Active
                                </span>
                            </td>
                            <td>
                                <a href="#" class="btn btn-edit">Edit</a>
                                <a href="#" onclick="return confirm('Are You Sure')" class="btn btn-danger">Delete</a>
                            </td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>Clothing</td>
                            <td><span class="category-tag">T-Shirts</span></td>
                            <td>32</td>
                            <td>
                                <span class="status-inactive">
                                    Inactive
                                </span>
                            </td>
                            <td>
                                <a href="#" class="btn btn-edit">Edit</a>
                                <a href="#" onclick="return confirm('Are You Sure')" class="btn btn-danger">Delete</a>
                            </td>
                        </tr>
                        <tr>
                            <td>4</td>
                            <td>Electronics</td>
                            <td><span class="category-tag">Headphones</span></td>
                            <td>12</td>
                            <td>
                                <span class="status-active">
                                    Active
                                </span>
                            </td>
                            <td>
                                <a href="#" class="btn btn-edit">Edit</a>
                                <a href="#" onclick="return confirm('Are You Sure')" class="btn btn-danger">Delete</a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>

</html>