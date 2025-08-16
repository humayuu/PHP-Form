<?php
require 'Database.php';
session_start();
if (empty($_SESSION['csrf_token'])) $_SESSION['csrf_token'] = bin2hex(random_bytes(32));


$obj = new Database();

if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['submit'])) {
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        header("Location: " . basename(__FILE__) . "?csrfError=1");
        exit;
    }

    $id = (int) $_POST['id'];
    $category = trim(filter_var($_POST['category'], FILTER_SANITIZE_SPECIAL_CHARS));
    $subCategory = trim(filter_var($_POST['subcategory_name'], FILTER_SANITIZE_SPECIAL_CHARS));
    $subCategoryDescription = trim(filter_var($_POST['subcategory_description'], FILTER_SANITIZE_SPECIAL_CHARS));
    $subCategorySlug = trim(filter_var($_POST['subcategory_slug'], FILTER_SANITIZE_SPECIAL_CHARS));
    $subCategoryStatus = trim(filter_var($_POST['subcategory_status'], FILTER_SANITIZE_SPECIAL_CHARS));

    if (empty($subCategory) || empty($subCategoryDescription)) {
        header("Location: " . basename(__FILE__) . "?inputError=1");
        exit;
    }

    $table = "sub_category_tbl";
    $params = [
        'category_id' => $category,
        'sub_category_name' => $subCategory,
        'sub_category_description' => $subCategoryDescription,
        'sub_category_slug' => $subCategorySlug,
        'sub_category_status' => $subCategoryStatus
    ];

    $where = "id = $id";

    $obj->update($table, $params, $where);
    $affected = $obj->update($table, $params, $where);
    if ($affected !== false) {
        header('Location: subCategories.php?success=1');
        exit;
    } else {
        echo "Update failed!";
        print_r($obj->getErrors());
    }
}

if (isset($_GET['id'])) {
    $id = (int) $_GET['id'];
    $table = "sub_category_tbl";
    $rows = "*";
    $join = null;
    $where = "id = :id";
    $params = ["id" => $id];
    $order = null;
    $limit = null;

    $subCategory = $obj->selectId($table, $rows, $join, $where, $params, $order, $limit);
}
?>
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

                <form method="POST" action="<?= basename(__FILE__) ?>">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
                    <input type="hidden" name="id" value="<?= htmlspecialchars($subCategory['id']) ?>">
                    <div class="form-group">
                        <?php
                        $table = "category_tbl";
                        $rows = "*";
                        $join = null;
                        $where = null;
                        $order = "category_name";
                        $limit = null;
                        $categories = $obj->selectAll($table, $rows, $join, $where, $order, $limit);
                        ?>
                        <label for="parent-category">Parent Category *</label>
                        <select id="parent-category" name="category">
                            <option value="" selected disabled>Select Parent Category</option>
                            <?php foreach ($categories as $category): ?>
                            <option value="<?= htmlspecialchars($category['id']) ?>"
                                <?= ($category['id'] == $subCategory['category_id']) ? 'selected' : null ?>>
                                <?= htmlspecialchars($category['category_name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="subcategory-name">Sub Category Name *</label>
                        <input type="text" id="subcategory-name" name="subcategory_name"
                            value="<?= $subCategory['sub_category_name'] ?>">
                    </div>

                    <div class="form-group">
                        <label for="subcategory-description">Description</label>
                        <textarea id="subcategory-description" name="subcategory_description"
                            placeholder="Describe this subcategory"><?= $subCategory['sub_category_description'] ?></textarea>
                    </div>

                    <div class="form-group">
                        <label for="subcategory-slug">URL Slug</label>
                        <input type="text" id="subcategory-slug" name="subcategory_slug" placeholder="subcategory-name"
                            value="<?= $subCategory['sub_category_slug'] ?>">
                    </div>

                    <div class="form-group">
                        <select name="subcategory_status" class="filter-select" required>
                            <option value="" disabled selected>Select Status</option>
                            <option value="active"
                                <?= ($subCategory['sub_category_status'] === 'active') ? 'selected' : null ?>>
                                Active</option>
                            <option value="inactive"
                                <?= ($subCategory['sub_category_status'] === 'inactive') ? 'selected' : null ?>>
                                Inactive</option>
                        </select>
                    </div>

                    <button type="submit" name="submit" class="btn btn-primary">Update Sub Category</button>
                </form>

            </div>


        </div>
    </div>
</body>

</html>