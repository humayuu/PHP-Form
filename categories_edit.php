<?php
require 'Database.php';
session_start();
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$obj = new Database();
$table = "category_tbl";

if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['isSubmitted'])) {
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        header("Location: " . basename(__FILE__));
    }

    $id = (int) $_POST['id'];
    $categoryName = trim(filter_var($_POST['category_name'], FILTER_SANITIZE_SPECIAL_CHARS));
    $categoryDescription = trim(filter_var($_POST['category_description'], FILTER_SANITIZE_SPECIAL_CHARS));
    $categorySlug = trim(filter_var($_POST['category_slug'], FILTER_SANITIZE_SPECIAL_CHARS));
    $categoryStatus = trim(filter_var($_POST['category_status'], FILTER_SANITIZE_SPECIAL_CHARS));

    $where = "id = $id";
    $params = ["category_name" => $categoryName, 'category_description' => $categoryDescription, 'category_slug' => $categorySlug, 'category_status' => $categoryStatus];

    $affected = $obj->update($table, $params, $where);

    if ($affected !== false) {
        header("Location: categories.php?success=1");
        exit;
    } else {
        echo "Update Error";
        print_r($obj->getErrors());
    }
}



// For Fetch Specific id Data
if (isset($_GET['id'])) {
    $id = (int) $_GET['id'];
    $rows = "*";
    $join = null;
    $where = "id = :id";
    $params = [":id" => $id];
    $order =  null;
    $limit = null;

    $category = $obj->selectOne($table, $rows, $join, $where, $params, $order, $limit);
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
                <h2>Edit Category</h2>

                <form method="POST" action="<?= basename(__FILE__) ?>">
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                    <input type="hidden" name="id" value="<?= $category['id'] ?>">
                    <div class="form-group">
                        <label for="category-name">Category Name *</label>
                        <input type="text" id="category-name" name="category_name"
                            value="<?= htmlspecialchars($category['category_name']) ?>">
                    </div>

                    <div class="form-group">
                        <label for="category-description">Description</label>
                        <textarea id="category-description" name="category_description"
                            placeholder="Describe this category"><?= htmlspecialchars($category['category_description']) ?></textarea>
                    </div>

                    <div class="form-group">
                        <label for="category-slug">URL Slug</label>
                        <input type="text" id="category-slug" name="category_slug" placeholder="category-name"
                            value="<?= htmlspecialchars($category['category_slug']) ?>">
                    </div>

                    <div class="form-group">
                        <label for="category-status">Status</label>
                        <select id="category-status" name="category_status">
                            <option value="" selected disabled>Select Category Status</option>
                            <option value="active"
                                <?= ($category['category_status'] == 'active') ? 'selected' : null ?>>Active</option>
                            <option value="inactive"
                                <?= ($category['category_status'] == 'inactive') ? 'selected' : null ?>>Inactive
                            </option>
                        </select>
                    </div>

                    <button type="submit" name="isSubmitted" class="btn btn-primary">Update Category</button>
                    <a href="categories.php"
                        style="text-decoration: none; margin-left: 20px; background-color: #eb3333ff; color: #ffff; padding:10px; border-radius:8px;">Back
                        to Home</a>
                </form>

            </div>
        </div>
    </div>
</body>

</html>