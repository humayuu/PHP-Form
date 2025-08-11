<?php
require 'config.php';
session_start();
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['submit'])) {
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        header("Location: " . basename(__FILE__) . "?csrfError=1");
        exit;
    }


    $productName        = trim(filter_var($_POST['product_name'] ?? '', FILTER_SANITIZE_SPECIAL_CHARS));
    $productDescription = trim(filter_var($_POST['product_description'] ?? '', FILTER_SANITIZE_SPECIAL_CHARS));
    $brandVal           = (int) ($_POST['brand'] ?? 0);
    $categoryVal        = (int) ($_POST['category'] ?? 0);
    $subCategoryVal     = (int) ($_POST['subcategory'] ?? 0);
    $price              = (float) ($_POST['product_price'] ?? 0);
    $discountPrice      = (float) ($_POST['discount_price'] ?? 0);
    $stock              = (int) ($_POST['product_stock'] ?? 0);
    $productStatus      = trim(filter_var($_POST['product_status'] ?? '', FILTER_SANITIZE_SPECIAL_CHARS));

    if (empty($productName) || empty($productDescription)) {
        header("Location: " . basename(__FILE__) . "?inputError=1");
        exit;
    }

    if ($price <= 0) {
        header("Location: " . basename(__FILE__) . "?priceError=1");
        exit;
    }

    if ($discountPrice > 0 && $discountPrice >= $price) {
        header("Location: " . basename(__FILE__) . "?discountError=1");
        exit;
    }



    try {
        $conn->beginTransaction();

        $stmt = $conn->prepare("INSERT INTO product_tbl (product_name, product_description, brand_id, category_id, sub_category_id, product_price, discount_price, product_stock, product_status) 
                                                        VALUES (:pname, :pdescp, :bid, :cid, :subcatId, :pprice, :dprice, :pstock, :pstatus)");
        $stmt->bindParam(":pname", $productName);
        $stmt->bindParam(":pdescp", $productDescription);
        $stmt->bindParam(":bid", $brandVal);
        $stmt->bindParam(":cid", $categoryVal);
        $stmt->bindParam(":subcatId", $subCategoryVal);
        $stmt->bindParam(":pprice", $price);
        $stmt->bindParam(":dprice", $discountPrice);
        $stmt->bindParam(":pstock", $stock);
        $stmt->bindParam(":pstatus", $productStatus);
        $result = $stmt->execute();

        if ($result) {
            $conn->commit();

            // Redirect to Home
            header("Location: products.php?success=1");
            exit;
        }
    } catch (PDOException $e) {
        if ($conn->inTransaction()) {
            $conn->rollBack();
        }
        error_log("Product Insert Error in " . __FILE__ . " on line " . __LINE__ . ": " . $e->getMessage());
    }
}


// ---------- AJAX handler: must run BEFORE any HTML output ----------
if (isset($_GET['action']) && $_GET['action'] === 'get_subcategories') {
    header('Content-Type: application/json; charset=utf-8');

    $category_id = isset($_GET['category_id']) ? intval($_GET['category_id']) : 0;
    if ($category_id <= 0) {
        echo json_encode([]);
        exit;
    }

    $stmt = $conn->prepare("SELECT id, sub_category_name FROM sub_category_tbl WHERE category_id = ? ORDER BY sub_category_name");
    $stmt->execute([$category_id]);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($rows);
    exit; // Important: stop further output

}

// For Delete Record 
if (isset($_GET['id'])) {
    $id = trim(filter_var($_GET['id'], FILTER_VALIDATE_INT));
    if ($id === false || $id === null) {
        header('Location: products.php?deleteError=invalid_id');
        exit;
    }
    try {
        $conn->beginTransaction();

        $query = $conn->prepare("DELETE FROM product_tbl WHERE id = :id");
        $query->bindParam(":id", $id);
        $result = $query->execute();

        if ($result) {
            $conn->commit();
            header("Location: products.php?deleteSuccess=1");
            exit;
        }
    } catch (PDOException $e) {
        if ($conn->inTransaction()) {
            $conn->rollBack();
        }
        error_log("Product Delete error in " . __FILE__ . "on" . __LINE__ . $e->getMessage());
    }
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products Management</title>
    <link rel="stylesheet" href="./assets/product.css">
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
                <h2>Add New Product</h2>
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
                    echo '<div class="success">Product   Add Successfully.</div>';
                } elseif (isset($_GET['priceError']) && $_GET['priceError'] == 1) {
                    echo '<div class="success">All Fields are Required.</div>';
                } elseif (isset($_GET['discountError']) && $_GET['discountError'] == 1) {
                    echo '<div class="success">All Fields are Required.</div>';
                }
                ?>

                <form method="POST" action="<?= basename(__FILE__) ?>">
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                    <div class="form-group">
                        <label for="product-name">Product Name *</label>
                        <input type="text" id="product-name" name="product_name">
                    </div>

                    <div class="form-group">
                        <label for="product-description">Description</label>
                        <textarea id="product-description" name="product_description" placeholder="Product description"></textarea>
                    </div>

                    <div class="form-group">
                        <?php
                        $brandValue = $conn->prepare("SELECT * FROM brand_tbl ORDER BY brand_name");
                        $brandValue->execute();
                        $brands = $brandValue->fetchAll(PDO::FETCH_ASSOC);

                        ?>
                        <label for="product-brand">Brand</label>
                        <select id="product-brand" name="brand">
                            <option value="" disabled selected>Select Brand</option>
                            <?php foreach ($brands as $brand): ?>
                                <option value="<?= $brand['id'] ?>"><?= $brand['brand_name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <?php
                        $categoryValue = $conn->prepare("SELECT * FROM category_tbl ORDER BY category_name");
                        $categoryValue->execute();
                        $categories = $categoryValue->fetchAll(PDO::FETCH_ASSOC);

                        ?>
                        <label for="product-category">Category *</label>
                        <select id="product-category" name="category">
                            <option value="" selected disabled>Select Category</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?= $category['id'] ?>"><?= $category['category_name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="subcategory">Sub Category</label>
                        <select id="subcategory" name="subcategory">
                            <option value="" selected disabled>Select Sub Category</option>
                        </select>
                    </div>

                    <div class="price-group">
                        <div class="form-group">
                            <label for="product-price">Price *</label>
                            <input type="number" id="product-price" name="product_price" step="0.01">
                        </div>

                        <div class="form-group">
                            <label for="product-sale-price">Discount Price</label>
                            <input type="number" id="product-sale-price" name="discount_price" step="0.01">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="product-stock">Stock Quantity *</label>
                        <input type="number" id="product-stock" name="product_stock">
                    </div>

                    <div class="form-group">
                        <label for="product-status">Status</label>
                        <select id="product-status" name="product_status">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                            <option value="draft">Draft</option>
                        </select>
                    </div>

                    <button type="submit" name="submit" class="btn btn-primary">Update
                         Product</button>
                </form>

            </div>
        </div>
    </div>
    <script>
        document.getElementById('product-category').addEventListener('change', function() {
            const id = this.value;
            const sub = document.getElementById('subcategory');
            sub.innerHTML = '<option value="" selected disabled>Select Sub Category</option>';
            if (!id) return;
            fetch('?action=get_subcategories&category_id=' + encodeURIComponent(id))
                .then(r => {
                    if (!r.ok) throw new Error(r.status);
                    return r.json();
                })
                .then(list => {
                    if (!Array.isArray(list) || list.length === 0) {
                        const o = document.createElement('option');
                        o.textContent = 'No subcategories';
                        o.disabled = true;
                        sub.appendChild(o);
                        return;
                    }
                    list.forEach(s => {
                        const o = document.createElement('option');
                        o.value = s.id;
                        o.textContent = s.sub_category_name;
                        sub.appendChild(o);
                    });
                })
                .catch(() => {
                    const o = document.createElement('option');
                    o.textContent = 'Error loading';
                    o.disabled = true;
                    sub.appendChild(o);
                });
        });
    </script>

</body>

</html>
<?php $conn = null; ?>