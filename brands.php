<?php
require 'Database.php';
session_start();

// Ensure CSRF token exists
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$obj = new Database();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    // Ensure token exists in POST and compare safely
    if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        header('Location: ' . basename(__FILE__) . '?csrfError=1');
        exit;
    }

    $brandName = trim(filter_var($_POST['brand_name'] ?? '', FILTER_SANITIZE_SPECIAL_CHARS));
    $brandDescription = trim(filter_var($_POST['brand_description'] ?? '', FILTER_SANITIZE_SPECIAL_CHARS));
    // Cast/validate status (defaults to 0)
    $brandStatus = isset($_POST['brand_status']) ? (int) $_POST['brand_status'] : 0;

    if ($brandName === '' || $brandDescription === '') {
        header('Location: ' . basename(__FILE__) . '?inputError=1');
        exit;
    }

    $table = 'brand_tbl';
    $params = [
        'brand_name' => $brandName,
        'brand_description' => $brandDescription,
        'brand_status' => $brandStatus
    ];

    $redirect = basename(__FILE__) . '?success=1';
    $obj->insert($table, $params, $redirect);
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

            <div class="table-section">
                <h2>Existing Brands</h2>
                <input type="text" class="search-box" placeholder="Search brands...">
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
                        <tr>
                            <td>1</td>
                            <td>Sample Brand</td>
                            <td class="status-active">active</td>
                            <td>
                                <a href="#" class="btn btn-edit">Edit</a>
                                <a href="#" onclick="return confirm('Are you sure?')" class="btn btn-danger">Delete</a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</body>

</html>