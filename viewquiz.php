<?php
include "conn.php";
error_reporting(E_ALL);
// require_once("function.php");
session_start();

if (!isset($_SESSION['user'])) {
  $_SESSION['message']="please login first";
  header("Location: index.php");
  exit();
}
$user=$_SESSION['user'];
$session_db=mysqli_query($con,"select * from login_sessions where ic_number='$user'");
$row=mysqli_fetch_array($session_db);
$session_from_db=$row['session_id'];
$session_id=session_id();
if($session_id!=$session_from_db)
{
  $_SESSION['message']= "you are logged out from another device. Please Login first";
  header("Location: index.php");
  exit();
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
        <a class="nav-link" href="viewquiz.php">Quiz</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#">Contact</a>
      </li>
    </ul>
    
    <div class="bs-example">
    <div class="container">
        <div class="row">
            <div class="col-md-12 bg-light text-right">
            <?php 
        if($_SESSION['user']){ 
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
<div class="view-quizzes">
    <h2>Quiz Schedule</h2>
    <?php 
        $batch_code = $_SESSION['batch'];
        $view_quiz = "SELECT * FROM quiz_topics WHERE batch_code = '$batch_code'";
        $view_query = mysqli_query($con, $view_quiz);
        if($view_query && mysqli_num_rows($view_query) > 0) {
    ?>
    <table>
        <thead>
            <tr>
                <th>Quiz</th>
                <th>Quiz Date</th>
                <th>Time</th>
                <th>Total Time</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php  

                while($row = mysqli_fetch_assoc($view_query)) {
                    $quiz_link = $row['quiz_link'];
                   
                    $quiz_id=$row['quiz_id'];
            ?>     
            <tr>
                <td><?php echo $row['quiz_topic']; ?></td>
                <td><?php echo $row['quiz_date']; ?></td>
                <td><?php echo $row['quiz_time']; ?></td>
                <td><?php 
                  $total_time = $row['total_time'];
                  list($hours, $minutes, $seconds) = explode(':', $total_time);
                  $total_minutes = $hours * 60 + $minutes;
                  if ($total_minutes >= 60) {
                      $display_time = floor($total_minutes / 60) . " hour " . ($total_minutes % 60) . " minutes";
                  } else {
                      $display_time = $total_minutes . " minutes";
                  }
                  echo $display_time;
                ?></td>
           <td>
    <?php 
    date_default_timezone_set('Asia/Singapore'); // Set the correct timezone
    $current_time = date("Y-m-d H:i:s");
    $current_date = date("Y-m-d");
    $current_time_only = date("H:i:s"); // Current time for comparison

    $quiz_date_from_db = $row['quiz_date'];
    $quiz_time_from_db = $row['quiz_time']; // Assuming this is in 'H:i:s' format
    
    $duration = $row['total_time'];
    $quiz_datetime_from_db = $quiz_date_from_db . ' ' . $quiz_time_from_db;
    list($hours, $minutes, $seconds) = explode(':', $duration);
    $end_time = date('H:i:s', strtotime("+{$hours} hours +{$minutes} minutes +{$seconds} seconds", strtotime($quiz_time_from_db)));

    if ($current_date == $quiz_date_from_db) {
        if ($current_time >= $quiz_datetime_from_db && $current_time_only < $end_time) {
            // Show the start button if current date matches quiz date, current time is greater than or equal to quiz time, and current time is before end time
            ?>
           <form action="quiz.php" method="post">
                <input type="hidden" name="quiz_id" value="<?php echo htmlspecialchars($quiz_id); ?>">
                <?php if ($row['set'] > 0) {
                      $quiz_links = explode(',', $row['quiz_link']);

                      // Trim and sanitize each link
                      $trimmed_links = array_map('trim', $quiz_links);
              
                      // Remove any empty elements caused by extra commas or spaces
                      $trimmed_links = array_filter($trimmed_links);
              
                      // Randomly select one link from trimmed links array
                      $random_link = $trimmed_links[array_rand($trimmed_links)];
                    ?>
                    <input type="hidden" name="quiz_link" value="<?php echo htmlspecialchars($random_link); ?>">
                <?php } else { ?>
                    <input type="hidden" name="quiz_link" value="<?php echo htmlspecialchars($quiz_link); ?>">
                <?php } ?>
                <input type="submit" value="Start">
            </form>
            <?php
        } elseif ($current_time_only >= $end_time) {
            // Show message if current time is after or equal to end time
            echo "Oops! Looks like you've missed it!";
        } else {
            // Show message if current time is before quiz time
            echo "Hold tight! The quiz will start soon.";
        }
    } elseif ($current_date < $quiz_date_from_db) {
        // Show message if current date is before quiz date
        echo "Quiz not available yet.";
    } else {
        // Show message if current date is after quiz date
        echo "Oops! Looks like you've missed it!";
    }
    ?>
</td>


            </tr>
            <?php 
                }
            ?>
        </tbody>
    </table>
    <?php 
        } else {
            $_SESSION['message'] = "No quizzes available";
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
      <!-- <script>
let warningCount = 0;

window.addEventListener('blur', function() {
    if (warningCount < 4) {
        alert('Warning ' + (warningCount + 1) + '/3: Do not switch tabs while filling the form.');
        warningCount++;
    } else {
        // Send an AJAX request to set the session message on the server-side
        let xhr = new XMLHttpRequest();
        xhr.open('GET', 'set_message.php', true);
        xhr.send();
    }
});
</script> -->

  </body>
</html>
