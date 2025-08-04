<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            color: #333;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            background: #2c3e50;
            color: white;
            padding: 20px;
            text-align: center;
            margin-bottom: 30px;
            border-radius: 8px;
        }

        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .card {
            background: white;
            border-radius: 8px;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            text-align: center;
            transition: transform 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .card-icon {
            width: 60px;
            height: 60px;
            background: #3498db;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            font-size: 24px;
            color: white;
        }

        .card h3 {
            margin-bottom: 10px;
            color: #2c3e50;
        }

        .card p {
            color: #7f8c8d;
            margin-bottom: 20px;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            background: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background 0.3s ease;
        }

        .btn:hover {
            background: #2980b9;
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-top: 30px;
        }

        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            text-align: center;
        }

        .stat-number {
            font-size: 2em;
            font-weight: bold;
            color: #3498db;
            margin-bottom: 5px;
        }

        .stat-label {
            color: #7f8c8d;
            font-size: 0.9em;
        }
    </style>
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
                <div class="stat-number">45</div>
                <div class="stat-label">Total Brands</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">12</div>
                <div class="stat-label">Categories</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">38</div>
                <div class="stat-label">Sub Categories</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">247</div>
                <div class="stat-label">Products</div>
            </div>
        </div>
    </div>
</body>
</html>