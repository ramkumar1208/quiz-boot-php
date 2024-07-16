<?php
include "conn.php"; 
error_reporting(E_ALL);
session_start();
  if(isset($_SESSION['admin'])){
    
  }else{
    $_SESSION['message']="admin please login first";
  }
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $quiz_topic = $_POST['quiz_topic'];
  $batch_code = $_POST['batch_code'];
  
  $myInputs = $_POST['inputs'];
            
  // Remove empty values
  $myInputs = array_filter($myInputs);

  // Combine inputs into comma-separated string if there are multiple inputs
  $quiz_links = implode(',', $myInputs);
  $num_questions = $_POST['how_many_questions'];
  $total_marks = $_POST['total_marks'];
  $quiz_date = $_POST['quiz_date'];
  $quiz_time=$_POST['quiz_time'];
  $timings = $_POST['timings'];

  // Prepare insert query
  $insert_query = "INSERT INTO quiz_topics (quiz_topic, batch_code, quiz_link, num_questions, total_marks, quiz_date, total_time ,quiz_time)
                   VALUES ('$quiz_topic', '$batch_code', '$quiz_links', $num_questions, $total_marks, '$quiz_date', '$timings','$quiz_time')";

  // Execute insert query
  if(mysqli_query($con, $insert_query)) {
      $_SESSION['message']="Quiz added successfully.";
  } else {
    $_SESSION['message']="Error: " . mysqli_error($con);
  }
}
?> 
<!DOCTYPE html>
<!-- Coding by CodingLab || www.codinglabweb.com -->
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">

    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Add Quiz</title>
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
    .container-fluid {
  background-image: url("bg.jpg");
  background-size: cover;
  background-position: center;
      height: 120vh;
}
.main-section {
  position: relative;
  top: 0%;
  left: 50%;
  transform: translateX(-50%);
  background-color: white;
  max-width: 800px;
  border: none;
  border-radius: 10px; /* Add your desired border radius */
}
</style>
<script>
        function addInput(button) {
            const inputGroup = document.createElement('div');
            inputGroup.className = 'input-group';

            const input = document.createElement('input');
            input.type = 'text';
            input.name = 'inputs[]';

           

            const removeButton = document.createElement('button');
            removeButton.type = 'button';
            removeButton.innerText = 'Remove';
            removeButton.onclick = function() { removeInput(removeButton) };

            inputGroup.appendChild(input);
         
            inputGroup.appendChild(removeButton);

            document.getElementById('inputContainer').appendChild(inputGroup);
        }

        function removeInput(button) {
            const inputGroup = button.parentNode;
            inputGroup.parentNode.removeChild(inputGroup);
        }
    </script>
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
<div class="main-section">
    <form action="addquiz.php" method="post">
        <div class="container">
          <div class="row justify-content-center">
               <div class="col-md-8">
                <div class="card my-2 p-3">
                  <div class="card-body">
                      <div class="form-check">
                      <h5 class="card-title py-2">Add Quiz Topic</h5>
                      <textarea class="form-control" name="quiz_topic" required></textarea><br>
                        <label for="batch_code">Enter batch Code</label><br>
                       <input type="text" class="form-check-input" name="batch_code" id="batch_code" required><br><br>
                       <label for="option">Enter Quiz Links here</label><br>
                       <form id="dynamicForm">
                          <div id="inputContainer">
                              <div class="input-group">
                                  <input type="text" name="inputs[]" />
                                  <button type="button" onclick="addInput(this)">Add</button>
                              </div>
                          </div>
                      </form>

                       <label for="option">How many questions</label><br>
                       <input type="text" class="form-check-input" name="how_many_questions" required><br><br>
                       <label for="option">Total Marks</label><br>
                       <input type="text" class="form-check-input" name="total_marks" required><br><br>
                       <label for="option">Quiz date</label><br>
                       <input type="date" class="form-check-input" name="quiz_date" required><br><br>
                       <label for="quiz_time">Quiz Time:</label><br>
                       <input type="time" id="quiz_time" name="quiz_time" value="13:15"><br><br>
                       <label for="answer">Total Timings for the quiz(00:00:00)</label><br>
                       <input type="text" class="form-check-input" name="timings" required><br><br>
                      </div>
                  </div>
                </div>
              </div>
            <div class="col-md-8 mb-5">
              <button type="submit" class="btn btn-success" name="answer-submit">Add Quiz</button>
            </div>
          </div>
        </div>
    </form>
    </div>
   
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
