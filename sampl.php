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
.iframe-container {
        position: relative;
        overflow: hidden;
        width: 100%;
        height: 100vh;
        padding-top: 56.25%; /* 16:9 aspect ratio (divide 9 by 16 = 0.5625 or 56.25%) */
    }

    .iframe-container iframe {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        border: 0; /* Remove iframe border */
    }
    #response {
    position: fixed; /* Stay in place during scroll */
    top: 13%; /* Center vertically */
    left: 50%; /* Center horizontally */
    transform: translate(-50%, -50%); /* Adjust to exactly center the div */
    color: red; /* Text color */
    z-index: 1000;
    font-size: 24px; /* Make the timer text bigger */
    text-align: center;
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
        <a class="nav-link" href="quiz.php">Quiz</a>
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


<?php 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    ?>
    <div id="response">
</div>
<form id="quizForm" action="quiz_completed.php" method="post">
    <input type="hidden" name="quiz_id" value="<?php echo $quiz_id; ?>"> 
    <input type="hidden" name="quiz_link" value="<?php echo $quiz_link; ?>"><br>
  
    <?php 

    // Check if quiz_link is set and not empty
    if (isset($_POST['quiz_link']) && !empty($_POST['quiz_link'])) {
      $quiz_id=$_POST['quiz_id'];
      date_default_timezone_set('Asia/Singapore); // Set the correct timezone
      include "conn.php";
      $res=mysqli_query($con,"select * from quiz_topics where quiz_id='$quiz_id'");
      while($row=mysqli_fetch_array($res)){
          $duration=$row['total_time'];
          $_SESSION['start_time'] = $row['quiz_time'];
      }
      $_SESSION['duration']=$duration;
      
      
      // print_r($_SESSION['start_time']);
      
      // echo "<br>";
      // Splitting the duration into hours, minutes, and seconds
      list($hours, $minutes, $seconds) = explode(':', $_SESSION['duration']);
      // Adding the duration to the start time
      $end_time = date('Y-m-d H:i:s', strtotime("+{$hours} hours +{$minutes} minutes +{$seconds} seconds", strtotime($_SESSION["start_time"])));
      $_SESSION['end_time'] = $end_time;
  
      // echo $end_time;   
        $quiz_link = $_POST['quiz_link'];
?>
        <div class="iframe-container">
            <iframe src="<?php echo htmlspecialchars($quiz_link); ?>"></iframe>
        </div>   
<?php 
    } else {
        // Handle case where quiz_link is not provided
        $_SESSION['message']="Quiz is  not Here.";
        header("Location: quiz.php");
    }

    ?>
      <input type="submit" value="SUBMIT">
      </form>
    <?php 
}
?>

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
};
</script> -->
<script>
var timer = setInterval(function(){
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.open('GET',"response.php",false);
    xmlhttp.send(null);
    var responseText = xmlhttp.responseText;
    document.getElementById("response").innerHTML = responseText;
    
    // Use .innerText or .textContent depending on browser support
    var remainingTimeText = document.getElementById("response").innerText || document.getElementById("response").textContent;
    console.log(remainingTimeText);
    
    if (remainingTimeText === "00:00:00") {
        clearInterval(timer);
        document.getElementById("quizForm").submit();
    }
}, 1000);

</script>

  </body>
</html>
