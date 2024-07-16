<?php
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
    session_start();
    include "conn.php";
    $_SESSION['message']="";
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $u_email=$_POST['u_email'];
        $u_pass=$_POST['u_pass'];
        $search_query="select * from `teachers` where `t_email`='$u_email' && `t_pass`='$u_pass'";
        $search_users=mysqli_query($con,$search_query);
        if(mysqli_num_rows($search_users) >= 1){
          $_SESSION['admin']=$u_email;
          $_SESSION['message']="";
          echo "<script>window.location.href = 'admin.php';</script>";
        //   header(" Location : admin.php ");
        }else if(mysqli_num_rows($search_users) == 0){
          $_SESSION['message']="admin not found";
          header("Location: admin.php");
          exit;
        }
    }
    
?>
<span style="font-family: verdana, geneva, sans-serif;"><!DOCTYPE html>
<html lang="en">
  <head>
    <title>Quiz App</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <style>
  .center-div {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 20vh; /* This will make the div vertically centered on the viewport */
  }
  .bs-example{
    	margin: 5px;
    }
    .container-fluid {
  background-image: url("bg.jpg");
  background-size: cover;
  background-position: center;
      height: 120vh;
}
.main-section {
  position: relative;
  top: 10%;
  left: 50%;
  transform: translateX(-50%);
  background-color: white;
  max-width: 800px;
  border: none;
  border-radius: 10px; /* Add your desired border radius */
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
        <a class="nav-link" href="admin_quiz.php">View Quiz</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="viewmarks.php">Student Marks</a>
      </li>
    </ul>
    
    <div class="bs-example">
    <div class="container">
        <div class="row">
            <div class="col-md-12 bg-light text-right">
            <?php 
        if(isset($_SESSION['admin'])){ 
          $user_email=$_SESSION['admin'];
          echo $user_email;  ?>
              <a href="logout.php"><button type="button" class="btn btn-primary">Log-out</button></a>
          <?php }else{ ?>        
                <a href="admin_login.php"><button type="button" class="btn btn-primary">Login</button></a>
                <?php } ?>    
              </div>
        </div>
    </div>
</div>
  
  </div>
</nav>
   
    <div class="center-div">
    <?php if (isset($_SESSION['message']) && !empty($_SESSION['message'])) {
        $message = $_SESSION['message'];
        $_SESSION['message'] = ""; // Clear the message after displaying it
        ?>
        <div class="alert alert-danger alert-dismissible">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            <?php echo $message; ?>
            <?php if ($message === "You are already logged in from another device.") {  ?>
                <a href="logout.php"><button>Logout That Device</button></a>
            <?php } ?>
        </div>
    <?php } ?>
</div>
              <!-- <div class="main-section">
    <h1>Admin Login</h1>
      <form action="admin_login.php" method="post">
        <label>Email</label><br>
        <input type="email" placeholder="Enter your email" required name="u_email"/><br>
        <label>Password</label><br>
        <input type="password" placeholder="Enter your password" required name="u_pass"/><br>
        <input type="submit" value="Login" />
      </form>
      </div> -->
      <div class="main-section">
    <form action="admin_login.php" method="post">
        <div class="container">
          <div class="row justify-content-center">
               <div class="col-md-8">
                <div class="card my-2 p-3">
                  <div class="card-body">
                    <h5 class="card-title py-2">Admin Login Here</h5>
                      <div class="form-check">
                      <label>Email</label><br>
                        <input type="email" placeholder="Enter your email" required name="u_email"/><br>
                        <label>Password</label><br>
                        <input type="password" placeholder="Enter your password" required name="u_pass"/><br><br>
                        <button type="submit" class="btn btn-success" name="answer-submit">Login</button>
                      </div>
                  </div>
                </div>
              </div>
          </div>
        </div>
    </form>
    </div>
  </div>   
    
  </body>
</html></span>