<?php
require 'Database.php';
$obj = new Database();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="./assets/dashboard.css">
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>Admin Dashboard</h1>
            <p>Manage your e-commerce inventory</p>
        </div>

        <div class="dashboard-grid">
            <div class="card">
                <div class="card-icon">🏷️</div>
                <h3>Brands</h3>
                <p>Manage brand information and details</p>
                <a href="brands.php" class="btn">Manage Brands</a>
            </div>

            <div class="card">
                <div class="card-icon">📂</div>
                <h3>Categories</h3>
                <p>Organize products into categories</p>
                <a href="categories.php" class="btn">Manage Categories</a>
            </div>

            <div class="card">
                <div class="card-icon">🗂️</div>
                <h3>Sub Categories</h3>
                <p>Create detailed subcategories</p>
                <a href="subcategories.php" class="btn">Manage Sub Categories</a>
            </div>

            <div class="card">
                <div class="card-icon">📦</div>
                <h3>Products</h3>
                <p>Add and manage product catalog</p>
                <a href="products.php" class="btn">Manage Products</a>
            </div>
        </div>

        <div class="stats">
            <div class="stat-card">
                <?php
                $table = 'brand_tbl';
                $rows = "*";
                $join = null;
                $where = null;
                $order = null;
                $limit = null;
                $brands = $obj->selectAll($table, $rows, $join, $where, $order, $limit);

                ?>
                <div class="stat-number"><?= count($brands) ?></div>
                <div class="stat-label">Total Brands</div>
            </div>
            <div class="stat-card">
                <?php
                $table = 'category_tbl';
                $rows = "*";
                $join = null;
                $where = null;
                $order = null;
                $limit = null;
                $category = $obj->selectAll($table, $rows, $join, $where, $order, $limit);

                ?>
                <div class="stat-number"><?= count($category) ?></div>
                <div class="stat-label">Categories</div>
            </div>
            <div class="stat-card">
                <?php
                $table = 'sub_category_tbl';
                $rows = "*";
                $join = null;
                $where = null;
                $order = null;
                $limit = null;
                $subCategory = $obj->selectAll($table, $rows, $join, $where, $order, $limit);

                ?>
                <div class="stat-number"><?= count($subCategory) ?></div>
                <div class="stat-label">Sub Categories</div>
            </div>
            <div class="stat-card">
                <?php
                $table = 'product_tbl';
                $rows = "*";
                $join = null;
                $where = null;
                $order = null;
                $limit = null;
                $products = $obj->selectAll($table, $rows, $join, $where, $order, $limit);

                ?>
                <div class="stat-number"><?= count($products) ?></div>
                <div class="stat-label">Products</div>
            </div>
        </div>
    </div>
</body>

</html>