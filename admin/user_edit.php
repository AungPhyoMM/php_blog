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
    if (empty($_POST['name']) || empty($_POST['email'])) {
        if (empty($_POST['name'])) {
            $nameError = 'Name cannot be null';
        }
        if (empty($_POST['email'])) {
            $emailError = 'Email cannot be null';
        }
    } elseif (!empty($_POST['password']) && strlen($_POST['password']) < 4) {
        $passwordError = 'Password should be 4 characters at least';
    } else {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        if (empty($_POST['role'])) {
            $role = 0;
        } else {
            $role = 1;
        };

        $stmt = $pdo->prepare("SELECT * FROM users WHERE email=:email AND id!=:id");
        $stmt->execute(array(':email' => $email, ':id' => $id));
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            echo "<script>alert('Email duplicated.')</script>";
        } else {
            if ($password != null) {
                $stmt = $pdo->prepare("UPDATE users SET name='$name', email='$email', password='$password', role='$role' WHERE id='$id'");
                $result = $stmt->execute();
            } else {
                $stmt = $pdo->prepare("UPDATE users SET name='$name', email='$email', role='$role' WHERE id='$id'");
                $result = $stmt->execute();
            }
            if ($result) {
                echo "<script>alert('Successfully new user updated.');window.location.href='user_list.php';</script>";
            }
        }
    }
}

$stmt = $pdo->prepare("SELECT * FROM users WHERE id=" . $_GET['id']);
$stmt->execute();
$result = $stmt->fetchAll();
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
                                <input type="hidden" name="id" value="<?= $result[0]['id'] ?>">
                                <label for="">Name</label>
                                <p style="color: red;"><?php echo empty($nameError) ? '' : '*' . $nameError; ?></p>
                                <input type="text" class="form-control" name="name" value="<?= $result[0]['name'] ?>">
                            </div>
                            <div class="form-group">
                                <label for="">Email</label>
                                <p style="color: red;"><?php echo empty($emailError) ? '' : '*' . $emailError; ?></p>
                                <input type="email" class="form-control" name="email" value="<?= $result[0]['email'] ?>">
                            </div>
                            <div class="form-group">
                                <label for="">Password</label>
                                <p style="color: red;"><?php echo empty($passwordError) ? '' : '*' . $passwordError; ?></p>
                                <input type="password" class="form-control" name="password" value="">
                                <span style="font-size: 10px">This user already has a password.</span>
                            </div>
                            <div class="form-group">
                                <label for="">Admin</label></br>
                                <input type="checkbox" name="role" value="1" <?php if ($result[0]['role'] == 1) {
                                                                                    echo "checked";
                                                                                } ?>>
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