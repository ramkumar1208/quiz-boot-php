<?php
include "conn.php";
error_reporting(E_ALL);
// require_once("function.php");
session_start();

if (!isset($_SESSION['admin'])) {
  $_SESSION['message']="please login first";
  header("Location: admin.php");
  exit();
}
$admin=$_SESSION['admin'];

?>
<!DOCTYPE html>
<!-- Coding by CodingLab || www.codinglabweb.com -->
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Quiz App</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <style>
          .center-div {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh; /* This will make the div vertically centered on the viewport */
  }
  .bs-example{
    	margin: 5px;
    }
    .container-bg {
  background-image: url("bg.jpg");
  background-size: cover;
  background-position: center;
      height: 120vh;
}
        table {
            width: 100%;
            border-collapse: collapse;
            border: none;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .view-quizzes {
  position: absolute;
  top: 30%;
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
      <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script> -->
      <div class="container-bg">
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
      <li class="nav-item">
        <a class="nav-link" href="student_management.php">Students managemant</a>
      </li>
      
    </ul>
    
    <div class="bs-example">
    <div class="container">
        <div class="row">
            <div class="col-md-12 bg-light text-right">
            <?php 
        if($_SESSION['admin']){ 
          $admin_email=$_SESSION['admin'];
          echo $admin_email;  ?>
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
<div class="view-quizzes">
    <h2>Edit students</h2>
    <form action="edit_students.php" method="post">
    <label for="batch_code">search by Batch Code</label>&nbsp;&nbsp;<input type="text" name="batch_code" id="batch_code">&nbsp;&nbsp;<br>
    <label for="batch_code">search by IC Number</label>&nbsp;&nbsp;<input type="text" name="ic_number" id="ic_number">&nbsp;&nbsp;<input type="submit" name="search" value="Search">
    
</form>
    <?php 
    
        if(isset($_POST['batch_code'])){
            $batch_code = $_POST['batch_code'];
            $view_quiz = "SELECT * FROM users where batch_code='$batch_code'";        
          }
          else if(isset($_POST['ic_number'])){
            $ic_number = $_POST['ic_number'];
            $view_quiz = "SELECT * FROM users where ic_number='$ic_number'";
          }
          else{
            $view_quiz = "SELECT * FROM users";
          }
    
      
        $view_query = mysqli_query($con, $view_quiz);
        if($view_query && mysqli_num_rows($view_query) > 0) {
    ?>
    <table>
        <thead>
            <tr>
                <th>Batch Code</th>
                <th>IC Number</th>
                <th>Name</th>
                <th>Mobile</th>
                <th>Email id</th>
                <th>Date of Birth</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php  
                while($row = mysqli_fetch_assoc($view_query)) {
                    // $quiz_link = $row['quiz_link'];
                    $ic_number=$row['ic_number'];
            ?>     
            <tr>
                <td><?php echo $row['batch_code']; ?></td>
                <td><?php echo $row['ic_number']; ?></td>
                <td><?php echo $row['user_name']; ?></td>
                <td><?php echo $row['mobile']; ?></td> 
                <td><?php echo $row['user_email']; ?></td>
                <td><?php echo $row['user_dob']; ?></td>
                
                <td>
                <form action="edit_student_admin.php" method="post" >
                <input type="hidden" name="ic_number" value="<?php echo $ic_number; ?>">
                <input type="submit" name="edit" value="Edit">
                <input type="submit" name="delete" value="Delete" onclick="return confirmDelete();">
                </form>
                </td>


            </tr>
            <?php 
                }
            ?>
        </tbody>
    </table>
    <?php 
        } else {
            $_SESSION['message'] = "No students available from database";
        }
    ?>
</div>


  </div>
    
        <?php if (isset($_SESSION['message']) && !empty($_SESSION['message'])) {
            $message = $_SESSION['message'];
            $_SESSION['message'] = ""; // Clear the message after displaying it
            ?>
    <div class="center-div">
    <div class="alert alert-danger alert-dismissible">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <?php echo $message; ?>
                <?php if ($message === "You are already logged in from another device.") {  ?>
                    <a href="logout.php"><button>Logout That Device</button></a>
                <?php } ?>
            </div>
            </div>
        <?php } ?>
    
      </div>
      <script>
    function confirmDelete() {
        return confirm('Are you sure you want to delete this student from database?');
    }
</script>

  </body>
</html>
