<?php
require 'config.php';
session_start();

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['issSubmitted'])) {
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        header("Location: " . basename(__FILE__) . "?csrfError=1");
        exit;
    }

    $cId = trim(filter_var($_POST['cid'], FILTER_VALIDATE_INT));
    $categoryVal = trim(filter_var($_POST['category'], FILTER_SANITIZE_SPECIAL_CHARS));
    $subCategoryName = trim(filter_var($_POST['subcategory_name'], FILTER_SANITIZE_SPECIAL_CHARS));
    $subCategoryDescp = trim(filter_var($_POST['subcategory_description'], FILTER_SANITIZE_SPECIAL_CHARS));
    $subCategorySlug = trim(filter_var($_POST['subcategory_slug'], FILTER_SANITIZE_SPECIAL_CHARS));
    $subCategoryStatus = trim(filter_var($_POST['subcategory_status'], FILTER_SANITIZE_SPECIAL_CHARS));

    if (empty($subCategoryName) || empty($subCategoryDescp) || empty($subCategorySlug)) {
        header("Location: " . basename(__FILE__) . "?inputError=1");
        exit;
    }

    try {
        $conn->beginTransaction();

        $stmt = $conn->prepare("UPDATE sub_category_tbl 
                                          SET category_id = :ccid,
                                              sub_category_name = :subname,
                                              sub_category_description = :subdescp,
                                              sub_category_slug = :subslug,
                                              sub_category_status = :substatus
                                         WHERE id = :cid");

        $stmt->bindParam(":ccid", $categoryVal);
        $stmt->bindParam(":subname", $subCategoryName);
        $stmt->bindParam(":subdescp", $subCategoryDescp);
        $stmt->bindParam(":subslug", $subCategorySlug);
        $stmt->bindParam(":substatus", $subCategoryStatus);
        $stmt->bindParam(":cid", $cId);
        $result = $stmt->execute();

        if ($result) {
            $conn->commit();

            // Redirect to Home Page
            header("Location: subcategories.php?update=1");
            exit;
        }
    } catch (PDOException $e) {
        if ($conn->inTransaction()) {
            $conn->rollBack();
        }
        error_log("SubCategory Update Error in " . __FILE__ . "on" . __LINE__ . $e->getMessage());
    }
}






try {
    $id = trim(filter_var($_GET['id'], FILTER_VALIDATE_INT));
    $sql = $conn->prepare("SELECT * FROM sub_category_tbl WHERE id = :id");
    $sql->bindParam(":id", $id);
    $sql->execute();
    $subCategory = $sql->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("SubCategory Fetch Error in " . __FILE__ . "on" . __LINE__ . $e->getMessage());
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
                <?php
                if (isset($_GET['csrfError']) && $_GET['csrfError'] == 1) {
                    echo '<div class="error">CSRF Token Error.</div>';
                } elseif (isset($_GET['inputError']) && $_GET['inputError'] == 1) {
                    echo '<div class="error">All Fields are Required.</div>';
                } elseif (isset($_GET['deleteSuccess']) && $_GET['deleteSuccess'] == 1) {
                    echo '<div class="success">Sub Category Deleted Successfully.</div>';
                } elseif (isset($_GET['deleteError'])) {
                    echo '<div class="error">Delete Error: ' . htmlspecialchars($_GET['deleteError']) . '</div>';
                } elseif (isset($_GET['update']) && $_GET['update'] == 1) {
                    echo '<div class="warning">Category Updated Successfully.</div>';
                } elseif (isset($_GET['success']) && $_GET['success'] == 1) {
                    echo '<div class="success">Category Add Successfully.</div>';
                }
                ?>
                <form method="POST" action="<?= basename(__FILE__) ?>">
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                    <input type="hidden" name="cid" value="<?= htmlspecialchars($subCategory['id']) ?>">
                    <div class="form-group">
                        <?php
                        $categoryValue = $conn->prepare("SELECT * FROM category_tbl ORDER BY category_name");
                        $categoryValue->execute();
                        $categories =  $categoryValue->fetchAll(PDO::FETCH_ASSOC);


                        ?>
                        <label for="parent-category">Parent Category *</label>
                        <select id="parent-category" name="category">
                            <option value="" selected disabled>Select Category</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?= $category['id'] ?>" <?= ($category['id'] === $subCategory['category_id']) ? 'selected' : null ?>><?= htmlspecialchars($category['category_name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="subcategory-name">Sub Category Name *</label>
                        <input type="text" id="subcategory-name" name="subcategory_name" value="<?= htmlspecialchars($subCategory['sub_category_name']) ?>">
                    </div>

                    <div class="form-group">
                        <label for="subcategory-description">Description</label>
                        <textarea id="subcategory-description" name="subcategory_description" placeholder="Describe this subcategory"><?= htmlspecialchars($subCategory['sub_category_description']) ?></textarea>
                    </div>

                    <div class="form-group">
                        <label for="subcategory-slug">URL Slug</label>
                        <input type="text" id="subcategory-slug" name="subcategory_slug" placeholder="subcategory-name" value="<?= htmlspecialchars($subCategory['sub_category_slug']) ?>">
                    </div>

                    <div class="form-group">
                        <label for="subcategory-status">Status</label>
                        <select id="subcategory-status" name="subcategory_status">
                            <option <?= ($subCategory['sub_category_status'] === 'active') ? 'selected' : null ?> value="active">Active</option>
                            <option <?= ($subCategory['sub_category_status'] === 'inactive') ? 'selected' : null ?> value="inactive">Inactive</option>
                        </select>
                    </div>

                    <button type="submit" name="issSubmitted" class="btn btn-primary">Update Sub Category</button>
                    <a href="subcategories.php" class="btn">Back to Home</a>

                </form>

            </div>

        </div>
    </div>
</body>

</html>
<?php $conn = null; ?>