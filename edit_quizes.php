<?php
include "conn.php";
error_reporting(E_ALL);
session_start();

if (!isset($_SESSION['admin'])) {
    $_SESSION['message'] = "Please login first";
    header("Location: admin.php");
    exit();
}
$admin = $_SESSION['admin'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['edit_quiz_by_id'])) {
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
        $id = $_POST['quiz_id'];
        $set_column_value = count($mysets) > 1 ? count($mysets) : 0;

        $stmt = $con->prepare("UPDATE quiz_topics SET 
            quiz_topic = ?, 
            batch_code = ?, 
            question_sets = ?, 
            num_questions = ?, 
            pass_percentage = ?, 
            quiz_date = ?, 
            total_time = ?, 
            quiz_time = ?, 
            `set` = ?
            WHERE quiz_id = ?");

        $stmt->bind_param("sssissssii", $quiz_topic, $batch_code, $question_sets, $num_questions, $pass_percentage, $quiz_date, $timings, $quiz_time, $set_column_value, $id);

        if ($stmt->execute()) {
            $_SESSION['message'] = "Quiz Edited successfully.";
            header("Location: editquiz.php");
            exit(); 
        } else {
            $_SESSION['message'] = "Error: " . $stmt->error;
            header("Location: editquiz.php");
            exit(); 
        }

    } else if (isset($_POST['delete'])) {
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
     
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Quiz App</title>
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
    <div class="form-container">
  <form action="edit_quizes.php" method="post">
    <h5 class="text-center mb-4">Edit Quiz</h5>
    <div class="form-group">
      <label for="quiz_topic">Quiz Topic</label>
      <textarea class="form-control" name="quiz_topic" id="quiz_topic"><?php echo htmlspecialchars($row['quiz_topic']); ?></textarea>
    </div>
    <input type="hidden" name="quiz_id" value="<?php echo $row['quiz_id']; ?>">
    <div class="form-group">
      <label for="batch_code">Batch Code</label>
      <input type="text" class="form-control" name="batch_code" id="batch_code" required oninput="handleBatchCodeInput(event)" value="<?php echo $row['batch_code']; ?>">
    </div>
    <div class="form-group">
      <label for="questionSets">Select Question Sets</label>
      <select id="questionSets" class="form-control" name="question_sets[]" multiple required>
        <!-- Options will be populated dynamically -->
      </select>
    </div>
    <div class="form-group">
      <label for="how_many_questions">How Many Questions</label>
      <input type="number" class="form-control" name="how_many_questions" id="how_many_questions" required value="<?php echo $row['num_questions']; ?>">
    </div>
    <div class="form-group">
      <label for="pass_percentage">Passing Percentage</label>
      <input type="number" class="form-control" name="pass_percentage" id="pass_percentage" required value="<?php echo $row['pass_percentage']; ?>">
    </div>
    <div class="form-group">
      <label for="quiz_date">Quiz Date</label>
      <?php 
      $date = new DateTime($row['quiz_date']);
      $formattedDate = $date->format('Y-m-d');
      ?>
      <input type="date" class="form-control" name="quiz_date" id="quiz_date" required value="<?php echo $formattedDate; ?>">
    </div>
    <div class="form-group">
      <label for="quiz_time">Quiz Time</label>
      <input type="time" class="form-control" name="quiz_time" id="quiz_time" value="13:15" required>
    </div>
    <div class="form-group">
      <label for="timings">Total Timings for the Quiz (HH:MM:SS)</label>
      <input type="text" class="form-control" name="timings" id="timings" required value="<?php echo $row['total_time']; ?>">
    </div>
    <button type="submit" class="btn btn-success btn-block" name="edit_quiz_by_id">Edit Quiz</button>
  </form>
</div>


    
    <?php 
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
