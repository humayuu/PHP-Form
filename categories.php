<?php
require 'config.php';
session_start();

// Generate CSRF Token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}


// For new Record Insert in database
if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['isSubmitted'])) {
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        header("Location: " . basename(__FILE__) . "?csrfError=1");
        exit;
    }

    $categoryName = trim(filter_var($_POST['category_name'], FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    $categoryDescription = trim(filter_var($_POST['category_description'], FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    $categorySlug = trim(filter_var($_POST['category_slug'], FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    $categoryStatus = trim(filter_var($_POST['category_status'], FILTER_SANITIZE_FULL_SPECIAL_CHARS));

    if (empty($categoryName) || empty($categoryDescription) || $categoryStatus === 'Select Category Status') {
        header("Location: categories.php?inputError=1");
        exit;
    }

    try {

        $conn->beginTransaction();

        $stmt = $conn->prepare("INSERT INTO category_tbl (category_name, category_description, category_slug, category_status) VALUES (:cname, :cdescp, :cslug, :cstatus)");
        $stmt->bindParam(":cname", $categoryName);
        $stmt->bindParam(":cdescp", $categoryDescription);
        $stmt->bindParam(":cslug", $categorySlug);
        $stmt->bindParam(":cstatus", $categoryStatus);
        $result = $stmt->execute();

        if ($result) {
            $conn->commit();
            header("Location: categories.php?success=1");
            exit;
        }
    } catch (PDOException $e) {
        $conn->rollBack();
        error_log("Add Category error in " . __FILE__ . "on" . __LINE__ . $e->getMessage());
    }
}


// For Delete Record 
if (isset($_GET['id'])) {
    $id = trim(filter_var($_GET['id'], FILTER_VALIDATE_INT));
    if ($id === false || $id === null) {
        header('Location: categories.php?deleteError=invalid_id');
        exit;
    }
    try {
        $conn->beginTransaction();

        $query = $conn->prepare("DELETE FROM category_tbl WHERE id = :id");
        $query->bindParam(":id", $id);
        $result = $query->execute();

        if ($result) {
            $conn->commit();
            header("Location: categories.php?deleteSuccess=1");
            exit;
        }
    } catch (PDOException $e) {
        if ($conn->inTransaction()) {
            $conn->rollBack();
        }
        error_log("Category Delete error in " . __FILE__ . "on" . __LINE__ . $e->getMessage());
    }
}


// Fetch all data for category table to show
try {

    $sql = $conn->prepare("SELECT * FROM category_tbl ORDER BY category_name");
    $sql->execute();
    $categories = $sql->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Category Fetch Error in " . __FILE__ . "on" . __LINE__ . $e->getMessage());
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
                <?php
                if (isset($_GET['csrfError']) && $_GET['csrfError'] == 1) {
                    echo '<div class="error">CSRF Token Error.</div>';
                } elseif (isset($_GET['inputError']) && $_GET['inputError'] == 1) {
                    echo '<div class="error">All Fields are Required.</div>';
                } elseif (isset($_GET['deleteSuccess']) && $_GET['deleteSuccess'] == 1) {
                    echo '<div class="success">Category Deleted Successfully.</div>';
                } elseif (isset($_GET['update']) && $_GET['update'] == 1) {
                    echo '<div class="warning">Category Updated Successfully.</div>';
                } elseif (isset($_GET['success']) && $_GET['success'] == 1) {
                    echo '<div class="success">Category Add Successfully.</div>';
                }
                ?>

                <form method="post" action="<?= basename(__FILE__) ?>">
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
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
                <?php if ($categories): ?>
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
                            <?php
                            $sl = 1;
                            foreach ($categories as $category): ?>
                                <tr>
                                    <td><?= $sl++ ?></td>
                                    <td><?= $category['category_name'] ?></td>
                                    <td><?= $category['category_slug'] ?></td>
                                    <td>45</td>
                                    <td><span class="status-active"><?= $category['category_status'] ?></span></td>
                                    <td>
                                        <a href="category_edit.php?id=<?= $category['id'] ?>" class="btn btn-edit">Edit</a>
                                        <a href="<?= basename(__FILE__) . "?id=" . $category['id'] ?>" onclick="return confirm('Are you sure?')" class="btn btn-danger">Delete</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            <?php ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="info">No Brand Record Found!</div>

                <?php endif; ?>
            </div>
        </div>
    </div>
</body>

</html>
<?php $conn = null; ?>