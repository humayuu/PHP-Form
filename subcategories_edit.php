
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
                <h2>Edit Sub Category</h2>
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

                    <button type="submit" class="btn btn-primary">Update Sub Category</button>
                    <a href="subcategories.php" class="btn">Back to Home</a>

                </form>
            </div>

        </div>
    </div>
</body>

</html>