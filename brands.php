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
                
                <form method="post" action="#">
                    <input type="hidden" name="csrf_token" value="">
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