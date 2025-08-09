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


    $cId = trim(filter_var($_POST['cid'], FILTER_VALIDATE_INT));
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

        $stmt = $conn->prepare("UPDATE category_tbl 
                                       SET category_name = :cname,
                                           category_description = :cdescp, 
                                           category_slug = :cslug, 
                                           category_status = :cstatus 
                                        WHERE id = :cid");


        $stmt->bindParam(":cid", $cId);
        $stmt->bindParam(":cname", $categoryName);
        $stmt->bindParam(":cdescp", $categoryDescription);
        $stmt->bindParam(":cslug", $categorySlug);
        $stmt->bindParam(":cstatus", $categoryStatus);
        $result = $stmt->execute();

        if ($result) {
            $conn->commit();
            header("Location: categories.php?update=1");
            exit;
        }
    } catch (PDOException $e) {
        $conn->rollBack();
        error_log("Add Category error in " . __FILE__ . "on" . __LINE__ . $e->getMessage());
    }
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
        <?php
        // Fetch  data for category table to show
        try {
            $id = trim(filter_var($_GET['id'], FILTER_VALIDATE_INT));
            if ($id === false || $id === null) {
                header('Location: categories.php?editError=invalid_id');
                exit;
            }
            $sql = $conn->prepare("SELECT * FROM category_tbl WHERE id = :id");
            $sql->bindParam(":id", $id);
            $sql->execute();
            $category = $sql->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Category Fetch Error in " . __FILE__ . "on" . __LINE__ . $e->getMessage());
        }

        ?>
        <div class="content-wrapper">
            <div class="form-section">
                <h2>Edit Category</h2>
                <?php
                if (isset($_GET['csrfError']) && $_GET['csrfError'] == 1) {
                    echo '<div class="error">CSRF Token Error.</div>';
                } elseif (isset($_GET['inputError']) && $_GET['inputError'] == 1) {
                    echo '<div class="error">All Fields are Required.</div>';
                }
                ?>

                <form method="post" action="<?= basename(__FILE__) ?>">
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                    <input type="hidden" name="cid" value="<?= $category['id'] ?>">
                    <div class="form-group">
                        <label for="category-name">Category Name *</label>
                        <input type="text" id="category-name" name="category_name" value="<?= htmlspecialchars($category['category_name']) ?>">
                    </div>

                    <div class="form-group">
                        <label for="category-description">Description</label>
                        <textarea id="category-description" name="category_description" placeholder="Describe this category"><?= htmlspecialchars($category['category_description']) ?></textarea>
                    </div>

                    <div class="form-group">
                        <label for="category-slug">URL Slug</label>
                        <input type="text" id="category-slug" name="category_slug" placeholder="category-name" value="<?= htmlspecialchars($category['category_slug']) ?>">
                    </div>

                    <div class="form-group">
                        <label for="category-status">Status</label>
                        <select id="category-status" name="category_status">
                            <option value="" selected disabled>Select Category Status</option>
                            <option value="active" <?= ($category['category_status'] == 'active') ? 'selected' : null ?>>Active</option>
                            <option value="inactive" <?= ($category['category_status'] == 'inactive') ? 'selected' : null ?>>Inactive</option>
                        </select>
                    </div>

                    <button type="submit" name="isSubmitted" class="btn btn-primary">Update Category</button>
                    <a href="categories.php" class="btn">Back to Home</a>

                </form>

            </div>

        </div>
    </div>
</body>

</html>
<?php $conn = null; ?>