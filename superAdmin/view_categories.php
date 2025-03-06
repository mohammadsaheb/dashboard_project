<?php
// Include your database connection
include('db.php');

// Start session
session_start();

// Check if admin is logged in
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .nav-link {
            color: white;
        }
        .nav-link:hover {
            color: #ffcc00;
        }
        body, html {
            height: 100%;
            margin: 0;
            font-family: Arial, sans-serif;
        }
        #wrapper {
            display: flex;
            height: 100%;
        }
        #sidebar-wrapper {
            width: 250px;
            flex-shrink: 0;
            height: 100%;
            overflow-y: auto;
            background-color: #343a40;
        }
        #sidebar-wrapper .sidebar-heading {
            padding: 20px;
            font-size: 1.2rem;
            color: white;
            text-align: center;
        }
        .list-group-item {
            border: none;
            padding: 15px 20px;
            color: white;
            background-color: #343a40;
        }
        .list-group-item:hover {
            background-color: #495057;
            color: #ffcc00;
        }
        #page-content-wrapper {
            flex-grow: 1;
            overflow-y: auto;
            background-color: #f8f9fa;
        }
        .navbar {
            padding: 15px;
            background-color: #fff;
            border-bottom: 1px solid #dee2e6;
        }
        .card {
            margin-bottom: 20px;
            border: none;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .card-header {
            background-color: #fff;
            border-bottom: 1px solid #dee2e6;
            padding: 15px;
        }
        .card-body {
            padding: 20px;
        }
        .table {
            width: 100%;
            margin-bottom: 0;
        }
        .table th, .table td {
            padding: 12px;
            vertical-align: middle;
        }
        .table th {
            background-color: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
        }
        .table tbody tr:hover {
            background-color: #f1f1f1;
        }
        .btn-sm {
            padding: 5px 10px;
            font-size: 0.875rem;
        }
        .modal-content {
            border: none;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
        }
        .modal-header {
            background-color: #343a40;
            color: white;
            border-bottom: none;
        }
        .modal-header .close {
            color: white;
        }
        .form-group {
            margin-bottom: 15px;
        }
    </style>
</head>
<body>

<!-- Sidebar -->
<div class="d-flex" id="wrapper">
    <div class="bg-dark border-right" id="sidebar-wrapper">
        <div class="sidebar-heading text-white"> Super Admin Dashboard</div>
        <div class="list-group list-group-flush">
            <a href="index.php" class="list-group-item list-group-item-action bg-dark nav-link"><i class="fas fa-home"></i> Dashboard</a>
            <a href="product.php" class="list-group-item list-group-item-action bg-dark nav-link"><i class="fas fa-box"></i> Products</a>
            <a href="users.php" class="list-group-item list-group-item-action bg-dark nav-link"><i class="fas fa-users"></i> Users</a>
            <a href="view_orders.php" class="list-group-item list-group-item-action bg-dark nav-link"><i class="fas fa-shopping-cart"></i> Orders</a>
            <a href="view_coupons.php" class="list-group-item list-group-item-action bg-dark nav-link"><i class="fas fa-tags"></i> Coupons</a>
            <a href="view_categories.php" class="list-group-item list-group-item-action bg-dark nav-link"><i class="fas fa-list"></i> Categories</a>
        </div>
    </div>

    <!-- Page Content -->
    <div id="page-content-wrapper">
        <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
            <h2>Welcome to the Super Admin Dashboard</h2>
        </nav>

        <div class="container-fluid mt-4">
            <div class="row">
                <!-- Categories Table -->
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Categories</h4>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Category Name</th>
                                        <th>Image</th>
                                        <th>Created At</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // Fetch categories data from the database
                                    $get_categories = "SELECT * FROM categories";
                                    $run_categories = mysqli_query($con, $get_categories);

                                    while ($row = mysqli_fetch_array($run_categories)) {
                                        $id = $row['id'];
                                        $name = $row['name'];
                                        $image = $row['image'];
                                        $created_at = $row['created_at'];
                                        echo "
                                            <tr>
                                                <td>$id</td>
                                                <td>$name</td>
                                                <td><img src='category_images/$image' width='60' height='50'></td>
                                                <td>$created_at</td>
                                                <td>
                                                    <button class='btn btn-warning btn-sm edit-category' data-id='$id' data-name='$name' data-image='$image'>Edit</button>
                                                    <a href='delete.php?id=$id&table=categories' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure you want to delete this category?\");'>Delete</a>
                                                </td>
                                            </tr>
                                        ";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Category Modal -->
<div class="modal fade" id="editCategoryModal" tabindex="-1" aria-labelledby="editCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editCategoryModalLabel">Edit Category</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editCategoryForm" action="update_category.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" id="editCategoryId" name="id">
                    <div class="form-group">
                        <label for="editCategoryName">Category Name</label>
                        <input type="text" class="form-control" id="editCategoryName" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="editCategoryImage">Category Image</label>
                        <input type="file" class="form-control" id="editCategoryImage" name="image">
                        <small class="form-text text-muted">Leave blank to keep the current image.</small>
                    </div>
                    <button type="submit" class="btn btn-primary">Update Category</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
$(document).ready(function() {
    // Show edit category modal
    $('.edit-category').on('click', function() {
        var categoryId = $(this).data('id');
        var categoryName = $(this).data('name');
        var categoryImage = $(this).data('image');

        $('#editCategoryId').val(categoryId);
        $('#editCategoryName').val(categoryName);
        $('#editCategoryModal').modal('show');
    });
});
</script>

</body>
</html>