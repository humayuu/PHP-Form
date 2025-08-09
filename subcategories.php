<?php
require 'config.php';
session_start();
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['submit'])) {
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        header("Location: subcategories.php?csrfError");
        exit;
    }

    $category = trim(filter_var($_POST['category'], FILTER_SANITIZE_SPECIAL_CHARS));
    $subCategory = trim(filter_var($_POST['subcategory_name'], FILTER_SANITIZE_SPECIAL_CHARS));
    $subCategoryDescription = trim(filter_var($_POST['subcategory_description'], FILTER_SANITIZE_SPECIAL_CHARS));
    $subCategorySlug = trim(filter_var($_POST['subcategory_slug'], FILTER_SANITIZE_SPECIAL_CHARS));
    $subCategoryStatus = trim(filter_var($_POST['subcategory_status'], FILTER_SANITIZE_SPECIAL_CHARS));

    if ($category == 'Select Sub Category Status' || empty($subCategory) || empty($subCategorySlug)) {
        header("Location: subcategories.php?inputError=1");
        exit;
    }

    try {

        $conn->beginTransaction();

        $stmt = $conn->prepare("INSERT INTO sub_category_tbl (category_id, sub_category_name, sub_category_description, sub_category_slug, sub_category_status) VALUES (:cid, :subcname, :subdescp, :subslug, :substatus)");

        $stmt->bindParam(":cid", $category);
        $stmt->bindParam(":subcname", $subCategory);
        $stmt->bindParam(":subdescp", $subCategoryDescription);
        $stmt->bindParam(":subslug", $subCategorySlug);
        $stmt->bindParam(":substatus", $subCategoryStatus);
        $result = $stmt->execute();

        if ($result) {
            $conn->commit();
            header("Location: subcategories.php?success=1");
            exit;
        }
    } catch (PDOException $e) {
        if ($conn->inTransaction()) {
            $conn->rollBack();
        }
        error_log("Sub Category Add Failed in " . __FILE__ . " on line " . __LINE__ . ": " . $e->getMessage());
    }
}

// For Delete Record
if (isset($_GET['id'])) {
    $id = trim($_GET['id']);
    $id = filter_var($id, FILTER_VALIDATE_INT);
    
    if ($id === false || $id === null || $id <= 0) {
        header('Location: subcategories.php?deleteError=invalid_id');
        exit;
    }
    
    try {
        $conn->beginTransaction();
        
        // Use correct primary key column name
        $query = $conn->prepare("DELETE FROM sub_category_tbl WHERE id = :id");
        $query->bindParam(":id", $id, PDO::PARAM_INT);
        $result = $query->execute();
        
        if ($result && $query->rowCount() > 0) {
            $conn->commit();
            header("Location: subcategories.php?deleteSuccess=1");
            exit;
        } else {
            $conn->rollBack();
            header("Location: subcategories.php?deleteError=record_not_found");
            exit;
        }
        
    } catch (PDOException $e) {
        if ($conn->inTransaction()) {
            $conn->rollBack();
        }
        error_log("SubCategory Delete error in " . __FILE__ . " on line " . __LINE__ . ": " . $e->getMessage());
        header("Location: subcategories.php?deleteError=database_error");
        exit;
    }
}

// Fetch all Sub Category data with proper JOIN
$subCategories = []; // Initialize empty array
try {
    $query = $conn->prepare("SELECT 
        sub_category_tbl.category_id,
        sub_category_tbl.id,
        sub_category_tbl.sub_category_name, 
        sub_category_tbl.sub_category_status,
        category_tbl.category_name 
        FROM sub_category_tbl 
        INNER JOIN category_tbl ON sub_category_tbl.category_id = category_tbl.id 
        ORDER BY sub_category_tbl.sub_category_name");
    $query->execute();
    $subCategories = $query->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Sub category fetch Error in " . __FILE__ . " on line " . __LINE__ . ": " . $e->getMessage());
    $subCategories = []; // Set empty array on error
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

                <form method="post" action="<?= basename(__FILE__) ?>">
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                    <div class="form-group">
                        <?php
                        $query = $conn->prepare("SELECT * FROM category_tbl ORDER BY category_name");
                        $query->execute();
                        $categories = $query->fetchAll(PDO::FETCH_ASSOC);
                        ?>
                        <label for="parent-category">Parent Category *</label>
                        <select id="parent-category" name="category">
                            <option value="" selected disabled>Select Parent Category</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?= $category['id'] ?>"><?= $category['category_name'] ?></option>
                            <?php endforeach; ?>
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
                <?php if ($subCategories): ?>
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
                            <?php $sl = 1; foreach($subCategories as $sCategory): ?>
                            <tr>
                                <td><?= $sl++ ?></td>
                                <td><?= htmlspecialchars($sCategory['category_name']) ?></td>
                                <td><span class="category-tag"><?= htmlspecialchars($sCategory['sub_category_name']) ?></span></td>
                                <td>25</td>
                                <td>
                                    <span class="status-<?= $sCategory['sub_category_status'] ?>">
                                        <?= ucfirst($sCategory['sub_category_status']) ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="subcategories_edit.php?id=<?= $sCategory['id'] ?>" class="btn btn-edit">Edit</a>
                                    <a href="<?= basename(__FILE__) . "?id=" . $sCategory['id'] ?>" onclick="return confirm('Are You Sure')" class="btn btn-danger">Delete</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="info">No Sub Category Record Found!</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>

</html>
<?php $conn = null; ?>