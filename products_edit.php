<?php
require 'Database.php';
session_start();
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$obj = new Database();

if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['submit'])) {
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        header("Location: " .  basename(__FILE__)) . "?csrfError=1";
        exit;
    }

    $id = (int) $_POST['id'];
    $productName = trim(filter_var($_POST['product_name'], FILTER_SANITIZE_SPECIAL_CHARS));
    $productDescription = trim(filter_var($_POST['product_description'], FILTER_SANITIZE_SPECIAL_CHARS));
    $brand = trim(filter_var($_POST['brand'], FILTER_SANITIZE_SPECIAL_CHARS));
    $category = trim(filter_var($_POST['category'], FILTER_SANITIZE_SPECIAL_CHARS));
    $subCategory = trim(filter_var($_POST['subcategory'], FILTER_SANITIZE_SPECIAL_CHARS));
    $price = trim(filter_var($_POST['product_price'], FILTER_SANITIZE_SPECIAL_CHARS));
    $discount = trim(filter_var($_POST['discount_price'], FILTER_SANITIZE_SPECIAL_CHARS));
    $stock = trim(filter_var($_POST['product_stock'], FILTER_SANITIZE_SPECIAL_CHARS));
    $status = trim(filter_var($_POST['product_status'], FILTER_SANITIZE_SPECIAL_CHARS));

    if (empty($productName) || empty($productDescription) || empty($price)) {
        header("Location: " .  basename(__FILE__)) . "?inputError=1";
        exit;
    }

    $table = "product_tbl";
    $params = [
        'product_name' => $productName,
        'product_description' => $productDescription,
        'brand_id' => $brand,
        'category_id' => $category,
        'sub_category_id' => $subCategory,
        'product_price' => $price,
        'discount_price' => $discount,
        'product_stock' => $stock,
        'product_status' => $status,

    ];
    $redirect = 'products.php?success=1';
    $where = "id = $id";
    $affected = $obj->update($table, $params, $where);
    if ($affected !== false) {
        header('Location: products.php?success=1');
        exit;
    } else {
        echo "Update failed!";
        print_r($obj->getErrors());
    }
}

if (isset($_GET['id'])) {

    //For Fetch Single data
    $id = (int) $_GET['id'];
    $table = "product_tbl";
    $rows = "*";
    $join = null;
    $where = "id = :id";
    $params = ["id" => $id];
    $order = null;
    $limit = null;

    $product = $obj->selectOne($table, $rows, $join, $where, $params, $order, $limit);
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

                <form method="POST" action="<?= basename(__FILE__) ?>">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
                    <input type="hidden" name="id" value="<?= htmlspecialchars($product['id']) ?>">

                    <div class="form-group">
                        <label for="product-name">Product Name *</label>
                        <input type="text" id="product-name" name="product_name"
                            value="<?= htmlspecialchars($product['product_name']) ?>">
                    </div>

                    <div class="form-group">
                        <label for="product-description">Description</label>
                        <textarea id="product-description" name="product_description"
                            placeholder="Product description"><?= htmlspecialchars($product['product_description']) ?></textarea>
                    </div>

                    <div class="form-group">
                        <?php
                        $table = 'brand_tbl';
                        $rows = "*";
                        $join = null;
                        $where = null;
                        $order = "brand_name";
                        $limit = null;
                        $brands = $obj->selectAll($table, $rows, $join, $where, $order, $limit);

                        ?>
                        <label for="product-brand">Brand</label>
                        <select id="product-brand" name="brand">
                            <option value="" disabled selected>Select Brand</option>
                            <?php foreach ($brands as $brandValue): ?>
                                <option value="<?= htmlspecialchars($brandValue['id']) ?>"
                                    <?= ($brandValue['id'] == $product['brand_id']) ? 'selected' : null ?>>
                                    <?= htmlspecialchars($brandValue['brand_name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <?php
                        $table = 'category_tbl';
                        $rows = "*";
                        $join = null;
                        $where = null;
                        $order = "category_name";
                        $limit = null;
                        $categories = $obj->selectAll($table, $rows, $join, $where, $order, $limit);

                        ?>
                        <label for="product-category">Category *</label>
                        <select id="product-brand" name="category">
                            <option value="" disabled selected>Select Category</option>
                            <?php foreach ($categories as $categoryValue): ?>
                                <option value="<?= htmlspecialchars($categoryValue['id']) ?>"
                                    <?= ($categoryValue['id'] == $product['category_id']) ? 'selected' : null ?>>
                                    <?= htmlspecialchars($categoryValue['category_name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <?php
                        $table = 'sub_category_tbl';
                        $rows = "*";
                        $join = null;
                        $where = null;
                        $order = "sub_category_name";
                        $limit = null;
                        $subCategories = $obj->selectAll($table, $rows, $join, $where, $order, $limit);

                        ?>
                        <label for="subcategory">Sub Category</label>
                        <select id="product-brand" name="sub_category">
                            <option value="" disabled selected>Select SubCategory</option>
                            <?php foreach ($subCategories as $SubcategoryValue): ?>
                                <option value="<?= htmlspecialchars($SubcategoryValue['id']) ?>"
                                    <?= ($SubcategoryValue['id'] == $product['sub_category_id']) ? 'selected' : null ?>>
                                    <?= htmlspecialchars($SubcategoryValue['sub_category_name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="price-group">
                        <div class="form-group">
                            <label for="product-price">Price *</label>
                            <input type="number" id="product-price" name="product_price" step="0.01"
                                value="<?= htmlspecialchars($product['product_price']) ?>">
                        </div>

                        <div class="form-group">
                            <label for="product-sale-price">Discount Price</label>
                            <input type="number" id="product-sale-price" name="discount_price" step="0.01"
                                value="<?= htmlspecialchars($product['discount_price']) ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="product-stock">Stock Quantity *</label>
                        <input type="number" id="product-stock" name="product_stock"
                            value="<?= htmlspecialchars($product['product_stock']) ?>">
                    </div>

                    <div class="form-group">
                        <label for="product-status">Status</label>
                        <select id="product-status" name="product_status">
                            <option value="active" <?= ($product['product_status'] == 'active') ? 'active' : null ?>>
                                Active</option>
                            <option value="inactive"
                                <?= ($product['product_status'] == 'inactive') ? 'active' : null ?>>Inactive</option>
                        </select>
                    </div>

                    <button type="submit" name="submit" class="btn btn-primary">Update Product</button>
                </form>

            </div>
        </div>
    </div>

</body>

</html>