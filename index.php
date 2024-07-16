<?php
error_reporting(E_ALL);
session_start();
if (isset($_SESSION['message']) && $_SESSION['message'] === "You are already logged in from another device.") {
  
}else if(empty($_SESSION['user'])){
  $_SESSION['message']="Please Login First";
}
?>
<!DOCTYPE html>
<!-- Coding by CodingLab || www.codinglabweb.com -->
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Quiz App</title>
  <!-- <link rel="stylesheet" href="s_new.css" /> -->
  <!-- Latest compiled and minified CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
<style>
    .bs-example{
    	margin: 5px;
    }
    .container-fluid {
  background-image: url("bg.jpg");
  background-size: cover;
  background-position: center;
}
.center-div {
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      /* This will make the div vertically centered on the viewport */
    }
</style>
</head>

<body>

  <div class="container-fluid">
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
  <a class="navbar-brand" href="index.php">
      <img src="logo.png" alt="" width=50px > 
    </a>
    <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
      <li class="nav-item active">
        <a class="nav-link" href="index.php">Home<span class="sr-only">(current)</span></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="viewquiz.php">Quiz</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#">Contact</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="admin.php">Admin</a>
      </li>
    </ul>
    
    <div class="bs-example">
    <div class="container">
        <div class="row">
            <div class="col-md-12 bg-light text-right">
            <?php 
        if(isset($_SESSION['user'])){ 
          $user_email=$_SESSION['user'];
          echo $user_email;  ?>
              <a href="logout.php"><button type="button" class="btn btn-primary">Log-out</button></a>
          <?php }else{ ?>        
                <a href="login1.php"><button type="button" class="btn btn-primary">Login</button></a>
                <?php } ?>    
              </div>
        </div>
    </div>
</div>
  
  </div>
</nav>
   
<div class="center-div">
    <?php 
    if (isset($_SESSION['message']) && !empty($_SESSION['message'])) {
        $message = $_SESSION['message'];
        $_SESSION['message'] = ""; // Clear the message after displaying it
    ?>
        <div class="alert alert-danger alert-dismissible">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            <?php echo $message; ?>
            <?php 
            if ($message === "You are already logged in from another device.") {  
            ?>
                <a href="logout.php"><button>Logout That Device</button></a>
            <?php 
            } else if ($message === "you are logged out from another device. Please Login first") { 
                session_unset();
                session_destroy();
            ?>
                <a href="login1.php"><button>Login</button></a>
            <?php 
            } 
            ?>
        </div>
    <?php 
    } 
    ?>
</div>

  </div>

</body>

</html>