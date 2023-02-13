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
    $file = 'images/' . ($_FILES['image']['name']);
    $imageType = pathinfo($file, PATHINFO_EXTENSION);

    if ($imageType != 'png' && $imageType != 'jpg' && $imageType != 'jpeg') {
        echo "<script>alert('Image must be png or jpg or jpeg.')</script>";
    } else {
        $title = $_POST['title'];
        $content = $_POST['content'];
        $image = $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], $file);

        $stmt = $pdo->prepare("INSERT INTO posts(title, content, image, author_id) VALUES (:title, :content, :image, :author_id)");
        $result = $stmt->execute(
            array(':title' => $title, ':content' => $content, ':image' => $image, 'author_id' => $_SESSION['user_id'])
        );
        if ($result) {
            echo "<script>alert('Successfully added.');window.location.href='index.php';</script>";
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
                        <form action="add.php" method="post" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="">Title</label>
                                <input type="text" class="form-control" name="title" value="" required>
                            </div>
                            <div class="form-group">
                                <label for="">Content</label></br>
                                <textarea class="form-control" name="content" cols="80" rows="8" required></textarea>
                            </div>
                            <div class="form-group">
                                <label for="">Image</label></br>
                                <input type="file" name="image" value="" required>
                            </div>
                            <div class="form-group">
                                <input type="submit" class="btn btn-success mr-4" value="Submit">
                                <a href="index.php" type="button" class="btn btn-warning">Back</a>
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