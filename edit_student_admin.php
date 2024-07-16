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
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['edit_stu_by_ic'])) {
        $edit_name = $_POST['edit_name'];
        $edit_email = $_POST['edit_email'];
        $edit_mobile = $_POST['edit_mobile'];
        $edit_dob = $_POST['edit_dob'];
        $ic_number=$_SESSION['ic_number'];
        $edit_batch_code = $_POST['edit_batch_code'];
        
        $query = "UPDATE users SET 
          user_name = '$edit_name', 
          user_email = '$edit_email', 
          mobile = '$edit_mobile', 
          user_dob = '$edit_dob',  
          batch_code = '$edit_batch_code' 
          WHERE ic_number = '$ic_number'";
    
        if (mysqli_query($con, $query)) {
          $_SESSION['message'] = "Student details edited successfully.";
          header("Location: edit_students.php");
          exit();
        } else {
            echo "Error: " . mysqli_error($con);
          $_SESSION['message'] = "Error: " . mysqli_error($con);
        //   header("Location: edit_students.php");
          exit();
        }
      }
      if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['delete'])) {
          $ic_number = $_POST['ic_number'];
          $query = "DELETE FROM users WHERE ic_number = '$ic_number'";
          
          if (mysqli_query($con, $query)) {
            $_SESSION['message'] = "Student Data Deleted successfully.";
          } else {
            $_SESSION['message'] = "Error: " . mysqli_error($con);
          }
          
          header("Location: edit_students.php");
          exit();
        }
      }
      
}   
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    
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
            position: relative;
            height: 20vh; /* This will make the div vertically centered on the viewport */
        }

        .alert-dismissible {
            position: absolute;
            top: 0;
            width: 100%;
            z-index: 9999; /* Ensure the alert box is above all other elements */
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
<?php 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(isset($_POST['ic_number'])){
    $ic_number=$_POST['ic_number'];
    $_SESSION['ic_number']=$ic_number;
    $select_query="select * from users where ic_number='$ic_number'";
    $result=mysqli_query($con,$select_query);
    if(mysqli_num_rows($result)>0){
        $row=mysqli_fetch_assoc($result);
        ?>
        <div class="main-section">
    <form action="edit_student_admin.php" method="post">
        <div class="container">
          <div class="row justify-content-center">
               <div class="col-md-8">
                <div class="card my-2 p-3">
                  <div class="card-body">
                      <div class="form-check">
                      <h5 class="card-title py-2">Edit Student details</h5>
                      <label for="name">Edit Name</label><br>
                       <input type="text" class="form-check-input" name="edit_name" id="edit_name" placeholder="<?php echo $row['user_name']; ?>" required value="<?php echo $row['user_name']; ?>"><br><br>
                       <label for="option">Edit Email</label><br>
                       <input type="text" class="form-check-input" name="edit_email"  placeholder="<?php echo $row['user_email']; ?>" value="<?php echo $row['user_email']; ?>"><br><br>
                       <label for="option">edit mobile</label><br>
                       <input type="text" class="form-check-input" name="edit_mobile"  placeholder="<?php echo $row['mobile']; ?>" required value="<?php echo $row['mobile']; ?>"><br><br>
                       <label for="option">edit date of birth</label><br>
                       <input type="date" class="form-check-input" name="edit_dob"  placeholder="<?php echo $row['user_dob']; ?>" required value="<?php echo $row['user_dob']; ?>"><br><br>
                       <label for="batch_code">Edit batch Code</label><br>
                       <input type="text" class="form-check-input" name="edit_batch_code" id="edit_batch_code" placeholder="<?php echo $row['batch_code']; ?>" required value="<?php echo $row['batch_code']; ?>"><br><br>
                       
                      </div>
                  </div>
                </div>
              </div>
            <div class="col-md-8 mb-5">
              <button type="submit" class="btn btn-success" name="edit_stu_by_ic">Edit Student</button>
            </div>
          </div>
        </div>
    </form>
    </div> <?php 
    }else{
        $_SESSION['message']="no quizes available";
        header("Location : editquiz.php");

    }
    
?>
    
  <?php 
    }
    } 
     ?>

  </div>
    
  <?php
    if (isset($_SESSION['message']) && !empty($_SESSION['message'])) {
        $message = $_SESSION['message'];
        $_SESSION['message'] = ""; // Clear the message after displaying it
    ?>
        <div class="center-div">    
            <div id="alertBox" class="alert alert-danger alert-dismissible">
                <a href="#" class="close" data-dismiss="alert" aria-label="close" onclick="closeAlert()">&times;</a>
                <?php echo $message; ?>
            </div>
        </div>
    <?php } ?>
    
      </div>

  <script>
        function closeAlert() {
            const alertBox = document.getElementById('alertBox');
            alertBox.parentElement.style.display = 'none';
        }
    </script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

  </body>
</html>
