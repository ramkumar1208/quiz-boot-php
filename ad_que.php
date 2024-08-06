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
        height: 20vh;
      }
      .bs-example {
        margin: 5px;
      }
      .container-fluid {
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
      .batch-code-group {
  display: flex;
  align-items: center;
  margin-bottom: 10px;
}

.batch-code-group label {
  margin-right: 10px;
  font-size: 16px;
  font-weight: 500;
}

.batch-code-group input {
  flex: 1;
  max-width: 300px; /* Set a maximum width for the input field */
  padding: 5px 10px;
  font-size: 14px;
  border: 1px solid #ccc;
  border-radius: 4px;
  margin-right: 10px;
}

.batch-code-group button {
  padding: 5px 10px;
  font-size: 14px;
  border: 1px solid #007bff;
  background-color: #007bff;
  color: white;
  border-radius: 4px;
  cursor: pointer;
}

.batch-code-group button:hover {
  background-color: #0056b3;
  border-color: #0056b3;
}
    </style>
  </head>
  <body>
  <div class="container-fluid">
    <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
        <a class="navbar-brand" href="index.php">
          <img src="logo.png" alt="" width=50px> 
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
            <a class="nav-link" href="department_management.php">Departments</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="student_management.php">Students management</a>
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
    
    <div class="form-container">
    <h2>Add Questions Set</h2>
    <form action="add_questions.php" method="post" enctype="multipart/form-data">
        <div id="batch-codes">
            <div class="batch-code-group">
                <input type="text" name="batch_code[]" placeholder="Enter Batch Code" id="batch_code" required>
                <button type="button" onclick="addBatchCode()">Add</button>
                <!-- <button type="button" onclick="removeBatchCode(this)">Remove</button> -->
            </div>
        </div>
        <br>
        <label for="set_name">Set Name:</label>
        <input type="text" id="set_name" name="set_name" required><br><br>
        
        <div id="questions">
            <div class="question">
                <label for="question">Question:</label>
                <input type="text" name="questions[]" required><br>
                <label for="question_image">Upload Question Image (optional):</label>
                <input type="file" name="question_images[]" accept="image/*"><br><br>
                
                <label for="answers">Answers:</label><br>
                <div class="answers">
                    <input type="text" name="answers[0][]" required>
                    <input type="radio" name="correct_answer[0]" value="0" required> Correct<br>
                </div>
                <button type="button" onclick="addAnswer(this)">Add Another Answer</button>
                <button type="button" onclick="removeQuestion(this)">Remove Question</button><br><br>
            </div>
        </div>
        
        <button type="button" onclick="addQuestion()">Add Another Question</button><br><br>
        <input type="submit" value="Submit">
    </form>
    </div>

    <script>
        let questionIndex = 1;

        function addQuestion() {
            let questionsDiv = document.getElementById('questions');
            let newQuestionDiv = document.createElement('div');
            newQuestionDiv.classList.add('question');
            newQuestionDiv.innerHTML = `
                <label for="question">Question:</label>
                <input type="text" name="questions[]" required><br>
                <label for="question_image">Upload Question Image (optional):</label>
                <input type="file" name="question_images[]" accept="image/*"><br><br>
                
                <label for="answers">Answers:</label><br>
                <div class="answers">
                    <input type="text" name="answers[${questionIndex}][]" required>
                    <input type="radio" name="correct_answer[${questionIndex}]" value="0" required> Correct<br>
                </div>
                <button type="button" onclick="addAnswer(this)">Add Another Answer</button>
                <button type="button" onclick="removeQuestion(this)">Remove Question</button><br><br>
            `;
            questionsDiv.appendChild(newQuestionDiv);
            questionIndex++;
        }

        function addAnswer(button) {
            let answersDiv = button.previousElementSibling;
            let answerIndex = answersDiv.querySelectorAll('input[type="text"]').length;
            let questionIndex = Array.from(document.querySelectorAll('.question')).indexOf(button.parentElement);

            let newAnswerDiv = document.createElement('div');
            newAnswerDiv.innerHTML = `
                <input type="text" name="answers[${questionIndex}][]" required>
                <input type="radio" name="correct_answer[${questionIndex}]" value="${answerIndex}" required> Correct<br>
            `;
            answersDiv.appendChild(newAnswerDiv);
        }

        function removeQuestion(button) {
            let questionDiv = button.parentElement;
            questionDiv.remove();
        }

        function addBatchCode() {
            let batchCodesDiv = document.getElementById('batch-codes');
            let newBatchCodeDiv = document.createElement('div');
            newBatchCodeDiv.classList.add('batch-code-group');
            newBatchCodeDiv.innerHTML = `
                <input type="text" name="batch_code[]" placeholder="Enter Batch Code" required>
               
                <button type="button" onclick="removeBatchCode(this)">Remove</button>
            `;
            batchCodesDiv.appendChild(newBatchCodeDiv);
        }
        // <button type="button" onclick="addBatchCode()">Add</button>
        function removeBatchCode(button) {
            button.parentElement.remove();
        }
    </script>

  </div>
  </body>
</html>
