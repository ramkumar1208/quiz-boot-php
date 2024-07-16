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
  if(isset($_POST['edit_quiz_by_id'])){
    echo "hi";
    $quiz_topic = $_POST['quiz_topic'];
    $batch_code = $_POST['batch_code'];
    $quiz_link = $_POST['quiz_Link'];
    $num_questions = $_POST['how_many_questions'];
    $total_marks = $_POST['total_marks'];
    $quiz_date = $_POST['quiz_date'];
    $quiz_time=$_POST['quiz_time'];
    $timings = $_POST['timings'];
    $id=$_POST['quiz_id'];

    $stmt = $con->prepare("UPDATE quiz_topics SET 
    quiz_topic = ?, 
    batch_code = ?, 
    quiz_link = ?, 
    num_questions = ?, 
    total_marks = ?, 
    quiz_date = ?, 
    total_time = ?, 
    quiz_time = ?
WHERE quiz_id = ?");

    $stmt->bind_param("sssiisssi", $quiz_topic,  $batch_code,   $quiz_link,   $num_questions,     $total_marks,  $quiz_date,       $timings, $quiz_time,  $id);

    if ($stmt->execute()) {
      $_SESSION['message'] = "Quiz Edited successfully.";
      header("Location: editquiz.php"); // Corrected redirection to viewquiz.php
      exit(); 
  } else {
      $_SESSION['message'] = "Error: " . $stmt->error;
      header("Location: editquiz.php"); // Redirect to editquiz.php in case of error
      exit(); 
  }

  }
  else if(isset($_POST['delete'])){
     $quiz_id = $_POST['quiz_id'];
     $stmt = $con->prepare("DELETE FROM quiz_topics WHERE quiz_id = ?");
     $stmt->bind_param("i", $quiz_id);
     if ($stmt->execute()) {
         $_SESSION['message'] = "Quiz Deleted successfully.";
     } else {
         $_SESSION['message'] = "Error: " . $stmt->error;
     }
     
     header("Location: editquiz.php");
     exit();
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
    if(isset($_POST['edit'])){
    $q_id=$_POST['quiz_id'];
    $select_query="select * from quiz_topics where quiz_id='$q_id'";
    $result=mysqli_query($con,$select_query);
    if(mysqli_num_rows($result)>0){
        $row=mysqli_fetch_assoc($result);
        ?>
        <div class="main-section">
    <form action="edit_quizes.php" method="post">
        <div class="container">
          <div class="row justify-content-center">
               <div class="col-md-8">
                <div class="card my-2 p-3">
                  <div class="card-body">
                      <div class="form-check">
                      <h5 class="card-title py-2">Edit Quiz</h5>
                      <textarea class="form-control" name="quiz_topic"><?php echo htmlspecialchars($row['quiz_topic']); ?></textarea><br>
                      <input type="hidden" name="quiz_id" value="<?php echo $row['quiz_id']; ?>">  
                      <label for="batch_code">Edit batch Code</label><br>
                       <input type="text" class="form-check-input" name="batch_code" id="batch_code" placeholder="<?php echo $row['batch_code']; ?>" required value="<?php echo $row['batch_code']; ?>"><br><br>
                       <label for="option">Edit Quiz Link</label><br>
                       <input type="text" class="form-check-input" name="quiz_Link"  placeholder="<?php echo $row['quiz_link']; ?>" value="<?php echo $row['quiz_link']; ?>"><br><br>
                       <label for="option">How many questions</label><br>
                       <input type="text" class="form-check-input" name="how_many_questions"  placeholder="<?php echo $row['num_questions']; ?>" required value="<?php echo $row['num_questions']; ?>"><br><br>
                       <label for="option">Total Marks</label><br>
                       <input type="text" class="form-check-input" name="total_marks"  placeholder="<?php echo $row['total_marks']; ?>" value="<?php echo $row['total_marks']; ?>"><br><br>
                       <label for="option">Quiz date</label><br>
                       <?php 
                       $date = new DateTime($row['quiz_date']);
                       $formattedDate = $date->format('Y-m-d');
                       ?>
                       <input type="date" class="form-check-input" name="quiz_date"  value="<?php echo $formattedDate; ?>"><br><br>
                       <label for="quiz_time">Quiz Time:</label><br>
                       <input type="time" id="quiz_time" name="quiz_time" value="13:15" placeholder="<?php echo $row['quiz_time']; ?>" value="<?php echo $row['quiz_time']; ?>"><br><br>
                       <label for="answer">Total Timings for the quiz(00:00:00)</label><br>
                       <input type="text" class="form-check-input" name="timings"  placeholder="<?php echo $row['total_time']; ?>" value="<?php echo $row['total_time']; ?>"><br><br>
                      </div>
                  </div>
                </div>
              </div>
            <div class="col-md-8 mb-5">
              <button type="submit" class="btn btn-success" name="edit_quiz_by_id">Edit Quiz</button>
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
