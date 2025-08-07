<?php
require 'config.php';
session_start();

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// For Add Brand Data in Database
if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['isSubmit'])) {
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        header("Location: " . basename(__FILE__) . "?csrfError=1");
        exit;
    }


    try {
        $pid = filter_var($_POST['pid'], FILTER_VALIDATE_INT);
        $name = filter_var($_POST['brand_name'], FILTER_SANITIZE_SPECIAL_CHARS);
        $description = filter_var($_POST['brand_description'], FILTER_SANITIZE_SPECIAL_CHARS);
        $status = filter_var($_POST['brand_status'], FILTER_SANITIZE_SPECIAL_CHARS);

        if (empty($name) || empty($description) || $status === 'Select Status') {
            header("Location: " . basename(__FILE__) . "?inputError=1");
            exit;
        }

        $stmt = $conn->prepare("UPDATE brand_tbl 
                                      SET brand_name        = :bname,
                                          brand_description = :bdescp,
                                          brand_status      = :bstatus
                                    WHERE id                = :pid");

        $stmt->bindParam(":pid", $pid);
        $stmt->bindParam(":bname", $name);
        $stmt->bindParam(":bdescp", $description);
        $stmt->bindParam(":bstatus", $status);
        $result = $stmt->execute();

        if ($result) {
            header("Location: brands.php?update=1");
            exit;
        } else {
            error_log("DB Update failed: " . implode(" | ", $stmt->errorInfo()));
            header("Location: edit_brand.php?error=database");
            exit;
        }
    } catch (PDOException $e) {
        error_log("Update Brand error in " . __FILE__ . "on" . __LINE__ . $e->getMessage());
    }
}



// For Fetch Record 
try {
    $id = htmlspecialchars($_GET['id']);
    $sql = $conn->prepare("SELECT * FROM brand_tbl WHERE id = :id");
    $sql->bindParam(":id", $id);
    $sql->execute();
    $brand = $sql->fetch(PDO::FETCH_ASSOC);
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
                <h2>Edit Brand</h2>
                <?php
                if (isset($_GET['csrfError']) && $_GET['csrfError'] == 1) {
                    echo '<div class="error">CSRF Token Error.</div>';
                } elseif (isset($_GET['inputError']) && $_GET['inputError'] == 1) {
                    echo '<div class="error">All Fields are Required.</div>';
                }
                ?>
                <form method="post" action="<?= basename(__FILE__) ?>">
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                    <input type="hidden" name="pid" value="<?= $brand['id'] ?>">
                    <div class="form-group">
                        <label for="brand-name">Brand Name *</label>
                        <input type="text" id="brand-name" name="brand_name" value="<?= htmlspecialchars($brand['brand_name']) ?>">
                    </div>

                    <div class="form-group">
                        <label for="brand-description">Description</label>
                        <textarea id="brand-description" name="brand_description" placeholder="Brief description of the brand"><?= htmlspecialchars($brand['brand_description']) ?></textarea>
                    </div>
                    <div class="form-group">
                        <select name="brand_status" class="filter-select">
                            <option value="" disabled selected>Select Status</option>
                            <option <?= ($brand['brand_status'] == "active") ? 'selected' : null ?> value="active">Active</option>
                            <option <?= ($brand['brand_status'] == "inactive") ? 'selected' : null ?> value="inactive">Inactive</option>
                        </select>
                    </div>
                    <button type="submit" name="isSubmit" class="btn btn-primary">Update Brand</button>
                    <a href="brands.php" class="btn">Back to Home</a>
                </form>
            </div>

            <?php


            ?>

        </div>
    </div>

    <!-- Delete Brand Record -->

</html>
<?php $conn = null; ?>