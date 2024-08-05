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
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Add Quiz</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <style>
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
    </style>
  </head>
  <body>
    <?php if (isset($_SESSION['message']) && !empty($_SESSION['message'])): ?>
      <div class="alert alert-danger alert-dismissible fade show text-center" role="alert">
        <?php echo $_SESSION['message']; $_SESSION['message'] = ""; ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    <?php endif; ?>

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
          <h5 class="text-center mb-4">Add Quiz Topic</h5>
          <div class="form-group">
            <label for="quiz_topic">Quiz Topic</label>
            <textarea class="form-control" name="quiz_topic" id="quiz_topic" required></textarea>
          </div>
          <div class="form-group">
            <label for="batch_code">Batch Code</label>
            <input type="text" class="form-control" name="batch_code" id="batch_code" required oninput="handleBatchCodeInput(event)">
          </div>
          <div class="form-group">
            <label for="questionSets">Select Question Sets</label>
            <select id="questionSets" class="form-control" name="question_sets[]" multiple required>
              <!-- Options will be populated dynamically -->
            </select>
          </div>
          <div class="form-group">
            <label for="how_many_questions">How Many Questions</label>
            <input type="number" class="form-control" name="how_many_questions" id="how_many_questions" required>
          </div>
          <div class="form-group">
            <label for="pass_percentage">Passing Percentage</label>
            <input type="number" class="form-control" name="pass_percentage" id="pass_percentage" required>
          </div>
          <div class="form-group">
            <label for="quiz_date">Quiz Date</label>
            <input type="date" class="form-control" name="quiz_date" id="quiz_date" required>
          </div>
          <div class="form-group">
            <label for="quiz_time">Quiz Time</label>
            <input type="time" class="form-control" name="quiz_time" id="quiz_time" value="13:15" required>
          </div>
          <div class="form-group">
            <label for="timings">Total Timings for the Quiz (HH:MM:SS)</label>
            <input type="text" class="form-control" name="timings" id="timings" required>
          </div>
          <button type="submit" class="btn btn-success btn-block">Add Quiz</button>
        </form>
      </div>
    </div>
    <script>
      function handleBatchCodeInput(event) {
        const batchCode = event.target.value;
        if (batchCode) {
          fetchQuestionSets(batchCode);
        }
      }

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
    </script>
  </body>
</html>
