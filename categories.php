<?php
require 'Database.php';
session_start();
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$obj = new Database();
$table = "category_tbl";



// For Insert Data
if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['isSubmitted'])) {
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        header("Location: " . basename(__FILE__) . "?csrfError=1");
        exit;
    }

    $categoryName = trim(filter_var($_POST['category_name'], FILTER_SANITIZE_SPECIAL_CHARS));
    $categoryDescription = trim(filter_var($_POST['category_description'], FILTER_SANITIZE_SPECIAL_CHARS));
    $categorySlug = trim(filter_var($_POST['category_slug'], FILTER_SANITIZE_SPECIAL_CHARS));
    $categoryStatus = trim(filter_var($_POST['category_status'], FILTER_SANITIZE_SPECIAL_CHARS));

    $params = ["category_name" => $categoryName, 'category_description' => $categoryDescription, 'category_slug' => $categorySlug, 'category_status' => $categoryStatus];
    $redirect = basename(__FILE__) . "?success=1";

    $obj->insert($table, $params, $redirect);
}

// For Fetch All Data
$rows = "*";
$join = null;
$where = null;
$order = "id DESC";
$limit = null;

$categories = $obj->selectAll($table, $rows, $join, $where, $order, $limit);


// for Delete Data
if (isset($_GET['id'])) {
    $id = (int) $_GET['id']; // Numeric value
    $where = "id = :id";
    $params = [":id" => $id];
    $redirect = basename(__FILE__) . "?success=1";
    $obj->delete($table, $where, $params, $redirect);
}




?>
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

                <form method="POST" action="<?= basename(__FILE__) ?>">
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                    <div class="form-group">
                        <label for="category-name">Category Name *</label>
                        <input type="text" id="category-name" name="category_name">
                    </div>

                    <div class="form-group">
                        <label for="category-description">Description</label>
                        <textarea id="category-description" name="category_description"
                            placeholder="Describe this category"></textarea>
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
                <?php $sl = 1;
                if ($categories):

                ?>
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
                        <?php foreach ($categories as $category): ?>
                        <tr>
                            <td><?= $sl++ ?></td>
                            <td><?= $category['category_name'] ?></td>
                            <td><?= $category['category_slug'] ?></td>
                            <td>45</td>
                            <td><span class="status-active"><?= $category['category_status'] ?></span></td>
                            <td>
                                <a href="categories_edit.php?id=<?= $category['id'] ?>" class="btn btn-edit">Edit</a>
                                <a href="<?= basename(__FILE__) . "?id=" . $category['id'] ?>"
                                    onclick="return confirm('Are you sure?')" class="btn btn-danger">Delete</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php else: ?>
                <div>No Record Found!</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>

</html>