<?php
include "conn.php"; 
error_reporting(E_ALL);
session_start();

if (isset($_SESSION['admin'])) {
    // admin is logged in
} else {
    $_SESSION['message'] = "Admin please login first";
    header('Location: login.php'); // Redirect to the login page
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $quiz_topic = $_POST['quiz_topic'];
    $batch_code = $_POST['batch_code'];

    $sets = $_POST['question_sets'];
    $mysets = array_filter($sets);
    $question_sets = implode(',', $mysets);

    $num_questions = $_POST['how_many_questions'];
    $pass_percentage = $_POST['pass_percentage'];
    $quiz_date = $_POST['quiz_date'];
    $quiz_time = $_POST['quiz_time'];
    $timings = $_POST['timings'];

    // Check if there is more than one question set selected
    $set_column_value = count($mysets) > 1 ? count($mysets) : 0;

    // Prepare insert query
    $insert_query = "INSERT INTO quiz_topics (quiz_topic, batch_code, question_sets, num_questions, pass_percentage, quiz_date, total_time, quiz_time, `set`)
                     VALUES ('$quiz_topic', '$batch_code', '$question_sets', $num_questions, $pass_percentage, '$quiz_date', '$timings', '$quiz_time', $set_column_value)";

    // Execute insert query
    if (mysqli_query($con, $insert_query)) {
        $_SESSION['message'] = "Quiz added successfully.";
    } else {
        $_SESSION['message'] = "Error: " . mysqli_error($con);
    }

    header('Location: addquiz.php'); // Redirect after insertion
    exit();
}
?>

<!DOCTYPE html>
<!-- Coding by CodingLab || www.codinglabweb.com -->
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css"> -->

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
    height: 100vh; /* This will make the div vertically centered on the viewport */
  }
    
        /* 
        .alert-dismissible {
            position: absolute;
            top: 0;
            width: 100%;
            z-index: 9999; 
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
  border-radius: 10px; 
} */
    .container-bg {
                    background-image: url("bg.jpg");
                    background-size: cover;
                    background-position: center;
                    min-height: 100vh;
                    display: flex;
                    flex-direction: column;
                }
                .form-container {
                    background: rgba(255, 255, 255, 0.8);
                    padding: 20px;
                    border-radius: 10px;
                    margin: 20px auto;
                    width: 80%;
                    max-width: 800px;
                }
                .image-preview {
                    max-width: 100px;
                    max-height: 100px;
                }
</style>
<script>
           async function fetchQuestionSets(batchCode) {
        const response = await fetch(`get_question_sets.php?batch_code=${batchCode}`);
        const questionSets = await response.json();

        const select = document.getElementById('questionSets');
        select.innerHTML = '';

        questionSets.forEach(set => {
            const option = document.createElement('option');
            option.value = set.id;
            option.textContent = `${set.name} (Total Questions: ${set.total_questions})`;
            select.appendChild(option);
        });
    }

    function handleBatchCodeInput(event) {
        const batchCode = event.target.value;
        if (batchCode) {
            fetchQuestionSets(batchCode);
        }
    }

        function addInput(button) {
            const inputContainer = document.getElementById('inputContainer');
            const newInputGroup = document.createElement('div');
            newInputGroup.classList.add('input-group');
            newInputGroup.innerHTML = `
                <input type="text" name="inputs[]" />
                <button type="button" onclick="addInput(this)">Add</button>
            `;
            inputContainer.appendChild(newInputGroup);
        }
    </script>
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
   

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>


    <div class="container-bg">
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

                <div class="form-container">
                <form action="addquiz.php" method="post">
                                    <h5 class="card-title py-2">Add Quiz Topic</h5>
                                    <textarea class="form-control" name="quiz_topic" required></textarea><br>
                                    <label for="batch_code">Enter Batch Code</label><br>
                                    <input type="text" class="form-check-input" name="batch_code" id="batch_code" required oninput="handleBatchCodeInput(event)"><br><br>
                                    <label for="questionSets">Select Question Sets</label><br>
                                    <select id="questionSets" name="question_sets[]" multiple required>
                                        <!-- Options will be populated dynamically -->
                                    </select><br><br>
                                    <label for="how_many_questions">How Many Questions</label><br>
                                    <input type="text" class="form-check-input" name="how_many_questions" required><br><br>
                                    <label for="total_marks">Passing percentage</label><br>
                                    <input type="text" class="form-check-input" name="pass_percentage" required><br><br>
                                    <label for="quiz_date">Quiz Date</label><br>
                                    <input type="date" class="form-check-input" name="quiz_date" required><br><br>
                                    <label for="quiz_time">Quiz Time</label><br>
                                    <input type="time" id="quiz_time" name="quiz_time" value="13:15"><br><br>
                                    <label for="timings">Total Timings for the Quiz (00:00:00)</label><br>
                                    <input type="text" class="form-check-input" name="timings" required><br><br>
                        <button type="submit" class="btn btn-success" name="answer-submit">Add Quiz</button>
                    
        </form>

                </div>
            </div>
            <script>
        function closeAlert() {
            const alertBox = document.getElementById('alertBox');
            alertBox.parentElement.style.display = 'none';
        }
    </script>
</body>
</html>
