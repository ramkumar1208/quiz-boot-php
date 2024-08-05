<?php 

session_start();
if (!isset($_SESSION['admin'])) {
  if (isset($_SESSION['message']) && $_SESSION['message'] === "admin not found") {
      // Handle the case where the message is already set to "admin not found"
  } else {
      $_SESSION['message'] = "admin please login first";
  }
}

?> 
<!DOCTYPE html>
<!-- Coding by CodingLab || www.codinglabweb.com -->
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Page</title>
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
      <!-- <li class="nav-item">
        <a class="nav-link" href="department_management.php">Departments</a>
      </li> -->
      <li class="nav-item">
        <a class="nav-link" href="student_management.php">Students managemant</a>
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
        </div>
    <?php } ?>
</div>

  </div>  
  </div>
  </body>
</html>
