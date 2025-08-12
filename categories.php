<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categories Management</title>
    <link rel="stylesheet" href="./assets/category.css">
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

                <form method="post" action="#">
                    <input type="hidden" name="csrf_token" value="">
                    <div class="form-group">
                        <label for="category-name">Category Name *</label>
                        <input type="text" id="category-name" name="category_name">
                    </div>

                    <div class="form-group">
                        <label for="category-description">Description</label>
                        <textarea id="category-description" name="category_description" placeholder="Describe this category"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="category-slug">URL Slug</label>
                        <input type="text" id="category-slug" name="category_slug" placeholder="category-name">
                    </div>

                    <div class="form-group">
                        <label for="category-status">Status</label>
                        <select id="category-status" name="category_status">
                            <option value="" selected disabled>Select Category Status</option>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>

                    <button type="submit" name="isSubmitted" class="btn btn-primary">Add Category</button>
                </form>

            </div>

            <div class="table-section">
                <h2>Existing Categories</h2>
                <input type="text" class="search-box" placeholder="Search categories...">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Category Name</th>
                            <th>Slug</th>
                            <th>Products Count</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>Electronics</td>
                            <td>electronics</td>
                            <td>45</td>
                            <td><span class="status-active">active</span></td>
                            <td>
                                <a href="#" class="btn btn-edit">Edit</a>
                                <a href="#" onclick="return confirm('Are you sure?')" class="btn btn-danger">Delete</a>
                            </td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Clothing</td>
                            <td>clothing</td>
                            <td>32</td>
                            <td><span class="status-active">active</span></td>
                            <td>
                                <a href="#" class="btn btn-edit">Edit</a>
                                <a href="#" onclick="return confirm('Are you sure?')" class="btn btn-danger">Delete</a>
                            </td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>Books</td>
                            <td>books</td>
                            <td>28</td>
                            <td><span class="status-active">inactive</span></td>
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