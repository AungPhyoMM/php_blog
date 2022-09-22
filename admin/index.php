<?php

session_start();
require '../config/config.php';

if(empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])){
  header('Location: login.php');
}

?>

<?php include('header.php'); ?>

        <!-- Main content -->
        <div class="content">
          <div class="container-fluid">
            <div class="row">
              <div class="col-md-12">
                <div class="card">
                  <div class="card-header">
                    <h3 class="card-title">Blog Listings</h3>
                  </div>
<?php

if(!empty($_GET['pageno'])) {
  $pageno = $_GET['pageno'];
} else {
  $pageno = 1;
};

$numOfRecs = 1;
$offset = ($pageno - 1) * $numOfRecs;

if(empty($_POST['search'])) {
  $pdostatement = $pdo->prepare('SELECT * FROM posts ORDER BY id DESC');
  $pdostatement->execute();
  $rawResult = $pdostatement->fetchAll();

  $total_pages = ceil(count($rawResult) / $numOfRecs);

  $pdostatement = $pdo->prepare("SELECT * FROM posts ORDER BY id DESC LIMIT $offset,$numOfRecs");
  $pdostatement->execute();
  $result = $pdostatement->fetchAll();
} else {
  $searchKey = $_POST['search'];
  $pdostatement = $pdo->prepare("SELECT * FROM posts WHERE title LIKE '%$searchKey%' ORDER BY id DESC");
  $pdostatement->execute();
  $rawResult = $pdostatement->fetchAll();

  $total_pages = ceil(count($rawResult) / $numOfRecs);

  $pdostatement = $pdo->prepare("SELECT * FROM posts WHERE title LIKE '%$searchKey%' ORDER BY id DESC LIMIT $offset,$numOfRecs");
  $pdostatement->execute();
  $result = $pdostatement->fetchAll();
}

?>
                  <!-- /.card-header -->
                  <div class="card-body">
                    <a href="add.php" type="button" class="btn btn-success mb-3"
                      >New Blog Post</a
                    >
                    <table class="table table-bordered">
                      <thead>
                        <tr>
                          <th style="width: 10px">ID</th>
                          <th>Title</th>
                          <th>Content</th>
                          <th style="width: 40px">Actions</th>
                        </tr>
                      </thead>
                      <tbody>
                      <?php
                      if($result){
                        $i = 1;
                        foreach($result as $value){
                      ?>
                      <tr>
                          <td><?= $i ?></td>
                          <td><?= $value['title'] ?></td>
                          <td><?= substr($value['content'], 0, 96) ?></td>
                          <td>
                            <div class="btn btn-group">
                              <div class="container">
                                <a
                                  href="edit.php?id=<?= $value['id'] ?>"
                                  type="button"
                                  class="btn btn-warning"
                                  >Edit</a
                                >
                              </div>
                              <div class="container">
                                <a href="delete.php?id=<?= $value['id'] ?>" 
                               onclick="return confirm('Are you sure you want to delete this item?')"
                                type="button" class="btn btn-danger"
                                  >Delete</a
                                >
                              </div>
                            </div>
                          </td>
                        </tr>
                      <?php  
                        $i++;}
                      }
                      ?>
                      </tbody>
                    </table>
                  <br>
<nav aria-label="Page navigation example" style="float: right">
  <ul class="pagination">
    <li class="page-item">
      <a class="page-link" href="?pageno=1">First</a>
    </li>
    <li class="page-item <?php if($pageno <=1) { echo 'disabled';} ?>">
      <a class="page-link" href="<?php if($pageno <=1) { echo '#';} else { echo "?pageno=".($pageno-1); } ?>">Previous</a>
    </li>
    <li class="page-item">
      <a class="page-link" href="#"><?php echo $pageno; ?></a>
    </li>
    <li class="page-item <?php if($pageno >= $total_pages) { echo 'disabled';} ?>">
      <a class="page-link" href="<?php if($pageno >= $total_pages) { echo '#';} else { echo "?pageno=".($pageno+1); } ?>">Next</a>
    </li>
    <li class="page-item">
      <a class="page-link" href="?pageno=<?= $total_pages ?>">Last</a>
    </li>
  </ul>
</nav>
                  </div>
                </div>
                <!-- /.card -->
              </div>
              <!-- /.col -->
            </div>
            <!-- /.row -->
          </div>
          <!-- /.container-fluid -->
        </div>

<?php include('footer.php'); ?>
