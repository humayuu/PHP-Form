<?php
require 'config.php';
session_start();

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// For Add Brand Data in Database
if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['submit'])) {
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        header("Location: " . basename(__FILE__) . "?csrfError=1");
        exit;
    }

    $name = filter_var($_POST['brand_name'], FILTER_SANITIZE_SPECIAL_CHARS);
    $description = filter_var($_POST['brand_description'], FILTER_SANITIZE_SPECIAL_CHARS);
    $status = filter_var($_POST['brand_status'], FILTER_SANITIZE_SPECIAL_CHARS);

    if (empty($name) || empty($description) || $status === 'Select Status') {
        header("Location: " . basename(__FILE__) . "?inputError=1");
        exit;
    }


    try {

        $conn->beginTransaction();

        $stmt = $conn->prepare("INSERT INTO brand_tbl (brand_name, brand_description, brand_status) VALUES (:bname, :bdescp, :bstatus)");
        $stmt->bindParam(":bname", $name);
        $stmt->bindParam(":bdescp", $description);
        $stmt->bindParam(":bstatus", $status);
        $result = $stmt->execute();

        if ($result) {
            $conn->commit();
            header("Location: " . basename(__FILE__) . "?success=1");
            exit;
        } else {
            error_log("DB insert failed: " . implode(" | ", $stmt->errorInfo()));
            header("Location: add_brand.php?error=database");
            exit;
        }
    } catch (PDOException $e) {
        $conn->rollBack();
        error_log("Add Brand error in " . __FILE__ . "on" . __LINE__ . $e->getMessage());
    }
}


// For Delete Brand Data
if (isset($_GET['id'])) {

    $id = htmlspecialchars($_GET['id']);

    try {

        $conn->beginTransaction();
        $delete = $conn->prepare("DELETE FROM brand_tbl WHERE id = :id");
        $delete->bindParam(":id", $id);
        $result =  $delete->execute();

        if ($result) {
            $conn->commit();
            header("Location: " . basename(__FILE__) . "?deleteSuccess=1");
            exit;
        }
    } catch (PDOException $e) {
        if ($conn->inTransaction()) {
            $conn->rollBack();
        }
        error_log("Brand Delete error in " . __FILE__ . "on" . __LINE__ . $e->getMessage());
    }
}


// For Fetch Record 
try {
    $sql = $conn->prepare("SELECT * FROM brand_tbl ORDER BY brand_name");
    $sql->execute();
    $brands = $sql->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Brand Fetch error in " . __FILE__ . "on" . __LINE__ . $e->getMessage());
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products Management</title>
    <link rel="stylesheet" href="./assets/brand.css">
</head>

<body>
    <div class="container">
        <div class="header">
            <div>
                <h1>Products Management</h1>
                <p>Add, edit, and manage your product catalog</p>
            </div>
            <a href="index.php" class="back-btn">← Back to Dashboard</a>
        </div>

        <div class="content-wrapper">
            <div class="form-section">
                <h2>Add New Brand</h2>
                <!-- <div class="info">Info message</div>
                <div class="success">Successful operation message</div>
                <div class="warning">Warning message</div>
                <div class="error">Error message</div>
                <div class="validation">Validation message 1<br>Validation message 2</div> -->
                <?php
                if (isset($_GET['csrfError']) && $_GET['csrfError'] == 1) {
                    echo '<div class="error">CSRF Token Error.</div>';
                } elseif (isset($_GET['inputError']) && $_GET['inputError'] == 1) {
                    echo '<div class="error">All Fields are Required.</div>';
                } elseif (isset($_GET['deleteSuccess']) && $_GET['deleteSuccess'] == 1) {
                    echo '<div class="success">Brand Deleted Successfully.</div>';
                } elseif (isset($_GET['update']) && $_GET['update'] == 1) {
                    echo '<div class="warning">Brand Updated Successfully.</div>';
                } elseif (isset($_GET['success']) && $_GET['success'] == 1) {
                    echo '<div class="success">Brand Add Successfully.</div>';
                }
                ?>
                <form method="post" action="<?= basename(__FILE__) ?>">
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                    <div class="form-group">
                        <label for="brand-name">Brand Name *</label>
                        <input type="text" id="brand-name" name="brand_name">
                    </div>

                    <div class="form-group">
                        <label for="brand-description">Description</label>
                        <textarea id="brand-description" name="brand_description" placeholder="Brief description of the brand"></textarea>
                    </div>
                    <div class="form-group">
                        <select name="brand_status" class="filter-select">
                            <option value="" disabled selected>Select Status</option>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                            <option value="draft">Draft</option>
                        </select>
                    </div>
                    <button type="submit" name="submit" class="btn btn-primary">Add Brand</button>
                </form>
            </div>

            <?php


            ?>
            <div class="table-section">
                <h2>Existing Brands</h2>
                <input type="text" class="search-box" placeholder="Search brands...">
                <?php if ($brands): ?>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Brand Name</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sl = 1;
                            foreach ($brands as $brand):
                            ?>
                                <tr>
                                    <td><?= $sl++ ?></td>
                                    <td><?= $brand['brand_name']; ?></td>
                                    <td class="status-active"><?= $brand['brand_status']; ?></td>
                                    <td>
                                        <a href="edit_brand.php?id=<?= $brand['id'] ?>" class="btn btn-edit">Edit</a>
                                        <a href="<?= basename(__FILE__) . "?id=" . $brand['id'] ?>" onclick="return confirm('Are you sure?')" class="btn btn-danger">Delete</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="info">No Brand Record Found!</div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Delete Brand Record -->

</html>
<?php $conn = null; ?>