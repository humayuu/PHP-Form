<?php
require 'Database.php';
session_start();
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$obj = new Database();

if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['submit'])) {
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        header("Location: " . basename(__FILE__) . "?csrfError=1");
        exit;
    }

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

    $redirect = basename(__FILE__) . "?success=1";

    $obj->insert($table, $params, $redirect);
}

//For Fetch all data
$table = "sub_category_tbl";

$rows = "sub_category_tbl.id,
         sub_category_tbl.category_id,
         sub_category_tbl.sub_category_name,
         sub_category_tbl.sub_category_description,
         sub_category_tbl.sub_category_slug,
         sub_category_tbl.sub_category_status,
         category_tbl.id AS category_id,
         category_tbl.category_name";

$join = "LEFT JOIN category_tbl ON sub_category_tbl.category_id = category_tbl.id";

$where = null;
$order = "sub_category_tbl.sub_category_name";
$limit = null;

$subCategories = $obj->selectAll($table, $rows, $join, $where, $order, $limit);


// FOr Delete
if (isset($_GET['id'])) {
    $id = (int)$_GET['id']; // ensure numeric
    $where = "id = :id";
    $params = ['id' => $id];
    $table = "sub_category_tbl";
    $redirect = basename(__FILE__) . "?success=1";

    $obj->delete($table, $where, $params, $redirect);
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
                <h2>Add New Sub Category</h2>

                <form method="POST" action="<?= basename(__FILE__) ?>">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
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
                            <option value="<?= htmlspecialchars($category['id']) ?>">
                                <?= htmlspecialchars($category['category_name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="subcategory-name">Sub Category Name *</label>
                        <input type="text" id="subcategory-name" name="subcategory_name">
                    </div>

                    <div class="form-group">
                        <label for="subcategory-description">Description</label>
                        <textarea id="subcategory-description" name="subcategory_description"
                            placeholder="Describe this subcategory"></textarea>
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
                <?php $sl = 1;
                if ($subCategories): ?>
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
                        <?php foreach ($subCategories as $subCategory): ?>
                        <tr>
                            <td><?=
                                        $sl++ ?></td>
                            <td><?= $subCategory['category_name'] ?></td>
                            <td><span class="category-tag"><?= $subCategory['sub_category_name'] ?></span></td>
                            <td>25</td>
                            <td>
                                <span class="status-active">
                                    <?= $subCategory['sub_category_status'] ?>
                                </span>
                            </td>
                            <td>
                                <a href="subcategories_edit.php?id=<?= $subCategory['id'] ?>"
                                    class="btn btn-edit">Edit</a>
                                <a href="subcategories.php?id=<?= $subCategory['id'] ?>"
                                    onclick="return confirm('Are You Sure')" class="btn btn-danger">Delete</a>
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