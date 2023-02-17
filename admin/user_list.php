<?php
session_start();
require '../config/config.php';

if (empty($_SESSION['user_id']) || empty($_SESSION['logged_in'])) {
    header('Location: login.php');
};

if ($_SESSION['role'] != 1) {
    header('Location: login.php');
};


if (!empty($_POST['search'])) {
    setcookie('search', $_POST['search'], time() + (86400 * 30), "/");
} else {
    if (empty($_GET['pageno'])) {
        unset($_COOKIE['search']);
        setcookie('search', null, -1, "/");
    }
};

?>

<?php include('header.php') ?>

<!-- Main content -->
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">User Listings</h3>
                    </div>
                    <?php

                    if (!empty($_GET['pageno'])) {
                        $pageno = $_GET['pageno'];
                    } else {
                        $pageno = 1;
                    };

                    $numOfrecs = 5;

                    $offset = ($pageno - 1) * $numOfrecs;

                    if (empty($_POST['search']) && empty($_COOKIE['search'])) {
                        $stmt = $pdo->prepare("SELECT * FROM users ORDER BY id DESC");
                        $stmt->execute();
                        $rawResult = $stmt->fetchAll();
                        $total_pages = ceil(count($rawResult) / $numOfrecs);

                        $stmt = $pdo->prepare("SELECT * FROM users ORDER BY id DESC LIMIT $offset, $numOfrecs");
                        $stmt->execute();
                        $result = $stmt->fetchAll();
                    } else {
                        $searchKey = !empty($_POST['search']) ? $_POST['search'] : $_COOKIE['search'];
                        $stmt = $pdo->prepare("SELECT * FROM users WHERE name LIKE '%$searchKey%' ORDER BY id DESC");
                        $stmt->execute();
                        $rawResult = $stmt->fetchAll();
                        $total_pages = ceil(count($rawResult) / $numOfrecs);

                        $stmt = $pdo->prepare("SELECT * FROM users WHERE name LIKE '%$searchKey%' ORDER BY id DESC LIMIT $offset, $numOfrecs");
                        $stmt->execute();
                        $result = $stmt->fetchAll();
                    }
                    ?>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <a href="user_add.php" type="button" class="btn btn-success mb-3">Create User</a>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th style="width: 10px">No.</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th style="width: 40px">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($result) {
                                    $i = 1;
                                    foreach ($result as $value) { ?>
                                        <tr>
                                            <td><?= $i ?></td>
                                            <td><?= $value['name'] ?></td>
                                            <td><?= $value['email'] ?></td>
                                            <td><?php if ($value['role'] == 1) {
                                                    echo "Admin";
                                                } else {
                                                    echo "Normal user";
                                                } ?></td>
                                            <td>
                                                <div class="btn-group">
                                                    <div class="container">
                                                        <a href="user_edit.php?id=<?= $value['id'] ?>" type="button" class="btn btn-warning">Edit</a>
                                                    </div>
                                                    <div class="container">
                                                        <a href="user_delete.php?id=<?= $value['id'] ?>" type="button" class="btn btn-danger" onclick="return confirm('Are you want to delete this user?');">Delete</a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                <?php $i++;
                                    }
                                } ?>
                            </tbody>
                        </table></br>
                        <nav aria-label="Page navigation example" style="float: right">
                            <ul class="pagination">
                                <li class="page-item">
                                    <a class="page-link" href="?pageno=1">First</a>
                                </li>
                                <li class="page-item <?php if ($pageno <= 1) {
                                                            echo 'disabled';
                                                        } ?>">
                                    <a class="page-link" href="<?php if ($pageno <= 1) {
                                                                    echo "#";
                                                                } else {
                                                                    echo "?pageno=" . ($pageno - 1);
                                                                } ?>">Previous</a>
                                </li>
                                <li class="page-item">
                                    <a class="page-link" href="#"><?= $pageno; ?></a>
                                </li>
                                <li class="page-item <?php if ($pageno >= $total_pages) {
                                                            echo 'disabled';
                                                        } ?>">
                                    <a class="page-link" href="<?php if ($pageno >= $total_pages) {
                                                                    echo "#";
                                                                } else {
                                                                    echo "?pageno=" . ($pageno + 1);
                                                                } ?>">Next</a>
                                </li>
                                <li class="page-item">
                                    <a class="page-link" href="?pageno=<?= $total_pages ?>">Last</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                    <!-- /.card-body -->
                </div>
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
</div>
<!-- /.content -->

<?php include('footer.php') ?>