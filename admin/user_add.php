<?php
session_start();
require '../config/config.php';

if (empty($_SESSION['user_id']) || empty($_SESSION['logged_in'])) {
    header('Location: login.php');
};

if ($_SESSION['role'] != 1) {
    header('Location: login.php');
};

if ($_POST) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    if (empty($_POST['role'])) {
        $role = 0;
    } else {
        $role = 1;
    };

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email=:email");

    $stmt->bindValue(':email', $email);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        echo "<script>alert('Email duplicated.')</script>";
    } else {
        $stmt = $pdo->prepare("INSERT INTO users(name, email, password, role) VALUES (:name, :email, :password, :role)");
        $result = $stmt->execute(
            array(':name' => $name, ':email' => $email, ':password' => $password, ':role' => $role)
        );
        if ($result) {
            echo "<script>alert('Successfully new user added.');window.location.href='user_list.php';</script>";
        }
    }
}
?>

<?php include('header.php') ?>

<!-- Main content -->
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form action="" method="post" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="">Name</label>
                                <input type="text" class="form-control" name="name" value="" required>
                            </div>
                            <div class="form-group">
                                <label for="">Email</label></br>
                                <input type="email" class="form-control" name="email" value="" required>
                            </div>
                            <div class="form-group">
                                <label for="">Password</label></br>
                                <input type="password" class="form-control" name="password" value="" required>
                            </div>
                            <div class="form-group">
                                <label for="">Admin</label></br>
                                <input type="checkbox" name="role" value="1">
                            </div>
                            <div class="form-group">
                                <input type="submit" class="btn btn-success mr-4" value="Submit">
                                <a href="user_list.php" type="button" class="btn btn-warning">Back</a>
                            </div>
                        </form>
                    </div>
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