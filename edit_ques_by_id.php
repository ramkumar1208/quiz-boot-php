<?php
session_start();
include "conn.php";

if (!isset($_SESSION['admin'])) {
    if (!isset($_SESSION['message'])) {
        $_SESSION['message'] = "Admin, please login first.";
    }
    header("Location: admin_login.php");
    exit();
}

$admin = $_SESSION['admin'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit'])) {
    $q_id = $_POST['set_id'];

    $select_query = "SELECT * FROM question_sets WHERE set_id='$q_id'";
    $result = mysqli_query($con, $select_query);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $total_ques = $row['total_questions'];
        $set_name = $row['set_name'];
        $batch_code=$row['batch_code'];
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Edit Questions</title>
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
                .image-preview {
                    max-width: 100px;
                    max-height: 100px;
                }
            </style>
        </head>
        <body>
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
                    <form action="q.php" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="set_id" value="<?php echo $q_id; ?>">
                        <input type="hidden" name="set_name" value="<?php echo $set_name; ?>">
                        <input type="hidden" name="total_question" value="<?php echo $total_ques; ?>">
                        <input type="hidden" name="removed_questions" id="removed_questions" value="">
                                    <label for="question">Batch code</label>
                                    <input type="text" name="questions[<?php echo $batch_code; ?>]" value="<?php echo $batch_code; ?>" disabled><br>
                                    
                                    <label for="question">Set name</label>
                                    <input type="text" name="questions[<?php echo $set_name; ?>]" value="<?php echo $set_name; ?>" required><br>
                                    
                        <div id="questions">
                            <?php
                            $questions_query = "SELECT * FROM questions WHERE set_id='$q_id'";
                            $questions_result = mysqli_query($con, $questions_query);
                            $q_index = 0;
                            while ($question = mysqli_fetch_assoc($questions_result)) {
                                
                                ?>
                                <div class="question">
                                   
                                    <input type="hidden" name="question_ids[<?php echo $q_index; ?>]" value="<?php echo $question['id']; ?>">
                                    <label for="question">Question:</label>
                                    <input type="text" name="questions[<?php echo $q_index; ?>]" value="<?php echo $question['question']; ?>" required><br>
                                    <?php if (!empty($question['question_image'])): ?>
                                        <label>Current Question Image:</label><br>
                                        <img src="<?php echo $question['question_image']; ?>" class="image-preview"><br>
                                    <?php endif; ?>
                                    <label for="question_image">Upload New Question Image (optional):</label>
                                    <input type="file" name="question_images[<?php echo $q_index; ?>]" accept="image/*"><br><br>

                                    <label for="answers">Answers:</label><br>
                                    <div class="answers">
                                        <?php
                                        $answers_query = "SELECT * FROM answers WHERE question_id='" . $question['id'] . "'";
                                        $answers_result = mysqli_query($con, $answers_query);
                                        $a_index = 0;
                                        while ($answer = mysqli_fetch_assoc($answers_result)) {
                                            ?>
                                            <input type="hidden" name="answer_ids[<?php echo $q_index; ?>][<?php echo $a_index; ?>]" value="<?php echo $answer['id']; ?>">
                                            <input type="text" name="answers[<?php echo $q_index; ?>][<?php echo $a_index; ?>]" value="<?php echo $answer['answer']; ?>" required>
                                            <input type="radio" name="correct_answer[<?php echo $q_index; ?>]" value="<?php echo $a_index; ?>" <?php echo $answer['is_correct'] ? 'checked' : ''; ?> required> Correct<br>
                                            <?php
                                            $a_index++;
                                        }
                                        ?>
                                    </div>
                                    <button type="button" onclick="addAnswer(this)">Add Another Answer</button>
                                    <button type="button" onclick="removeQuestion(this)">Remove Question</button><br><br>
                                </div>
                                <?php
                                $q_index++;
                            }
                            ?>
                        </div>

                        <button type="button" onclick="addQuestion()">Add Another Question</button><br><br>
                        <input type="submit" name="edit_quiz_by_id" value="Edit Questions">
                    </form>

                    <script>
                        let questionIndex = <?php echo $q_index; ?>;
                        let removedQuestions = [];

                        function addQuestion() {
                            let questionsDiv = document.getElementById('questions');
                            let newQuestionDiv = document.createElement('div');
                            newQuestionDiv.classList.add('question');
                            newQuestionDiv.innerHTML = `
                                <input type="hidden" name="question_ids[${questionIndex}]" value="">
                                <label for="question">Question:</label>
                                <input type="text" name="questions[${questionIndex}]" required><br>
                                <label for="question_image">Upload Question Image (optional):</label>
                                <input type="file" name="question_images[${questionIndex}]" accept="image/*"><br><br>

                                <label for="answers">Answers:</label><br>
                                <div class="answers">
                                    <input type="text" name="answers[${questionIndex}][0]" required>
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
                                <input type="text" name="answers[${questionIndex}][${answerIndex}]" required>
                                <input type="radio" name="correct_answer[${questionIndex}]" value="${answerIndex}" required> Correct<br>
                            `;
                            answersDiv.appendChild(newAnswerDiv);
                        }

                        function removeQuestion(button) {
                            let questionDiv = button.parentElement;
                            let questionId = questionDiv.querySelector('input[type="hidden"]').value;
                            if (questionId) {
                                removedQuestions.push(questionId);
                            }
                            questionDiv.remove();
                            document.getElementById('removed_questions').value = removedQuestions.join(',');
                            console.log(removedQuestions); // Corrected here
                        }
                    </script>
                </div>
            </div>
        </body>
        </html>
        <?php
    } else {
        $_SESSION['message'] = "Question set not found.";
        header("Location: admin_quiz.php");
        exit();
    }
}
?>
