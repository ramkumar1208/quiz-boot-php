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
#add_batch_code{
    font-size: 14px;
  border: 1px solid #007bff;
  background-color: #007bff;
  color: white;
  border-radius: 4px;
  cursor: pointer;
}
#add_batch_code:hover{
    background-color: #0056b3;
    border-color: #0056b3;  
}
.batch-code-group button:hover {
  background-color: #0056b3;
  border-color: #0056b3;
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
                    <form action="edit_ques_by_id.php" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="set_id" value="<?php echo $q_id; ?>">
                    <input type="hidden" name="total_question" value="<?php echo $total_ques; ?>">
                    <input type="hidden" name="removed_questions" id="removed_questions" value="">

                    <label for="set_name">Set name</label>
                    <input type="text" name="set_name" value="<?php echo $set_name; ?>" required><br>

                    <label for="batch_code">Batch codes</label>
                    <div id="batch_codes">
                        
                        <?php
                        $batch_codes = explode(',', $batch_code); // Split the batch codes into an array
                        foreach ($batch_codes as $index => $code) {
                            ?>
                            <div class="batch-code-group">
                                <input type="text" name="batch_code[]" value="<?php echo $code; ?>" required>
                                <button type="button" onclick="removeBatchCode(this)">Remove</button><br>
                                
                            </div>
                            <?php
                        }
                        ?>
                          <button type="button" onclick="addBatchCode()" id="add_batch_code">Add Batch Code</button><br><br>
                          
                    </div>
                  
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
                        function addBatchCode() {
                        let batchCodesDiv = document.getElementById('batch_codes');
                        let newBatchCodeDiv = document.createElement('div');
                        newBatchCodeDiv.classList.add('batch-code-group');
                        newBatchCodeDiv.innerHTML = `
                            <input type="text" name="batch_code[]" required>
                            <button type="button" onclick="removeBatchCode(this)">Remove</button>
                        `;
                        batchCodesDiv.appendChild(newBatchCodeDiv);
                    }

                    function removeBatchCode(button) {
                        button.parentElement.remove();
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
} else if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_quiz_by_id'])) {
    $questions = isset($_POST['questions']) ? $_POST['questions'] : [];
    $answers = isset($_POST['answers']) ? $_POST['answers'] : [];
    $correct_answers = isset($_POST['correct_answer']) ? $_POST['correct_answer'] : [];
    $q_id = mysqli_real_escape_string($con, $_POST['set_id']);
    $set_name = mysqli_real_escape_string($con, $_POST['set_name']);
    $batch_codes = isset($_POST['batch_code']) ? $_POST['batch_code'] : [];
    $batch_codes = array_map('trim', $batch_codes); // Trim whitespace from each batch code
    
    // Escape each batch code and then join them with commas
    $batch_codes_str = implode(',', array_map(function($code) use ($con) {
        return mysqli_real_escape_string($con, $code);
    }, $batch_codes));
    
    $uploaded_images = [];
    $new_questions_count = 0;
    $removed_questions = isset($_POST['removed_questions']) ? array_filter(explode(',', $_POST['removed_questions'])) : [];

    // Handle file uploads
    $target_dir = "uploads/";
    foreach ($_FILES['question_images']['name'] as $index => $name) {
        if (!empty($name)) {
            $target_file = $target_dir . basename($name);
            if (move_uploaded_file($_FILES['question_images']['tmp_name'][$index], $target_file)) {
                $uploaded_images[$index] = mysqli_real_escape_string($con, $target_file);
            } else {
                echo "Error uploading file: " . $_FILES['question_images']['error'][$index];
                exit();
            }
        } else {
            $uploaded_images[$index] = null; // No image uploaded
        }
    }

    // Remove deleted questions and their answers
    foreach ($removed_questions as $question_id) {
        if (!empty($question_id)) {
            $question_id = mysqli_real_escape_string($con, $question_id);
            $delete_answers_query = "DELETE FROM answers WHERE question_id='$question_id'";
            if (!mysqli_query($con, $delete_answers_query)) {
                echo "Error deleting answers: " . mysqli_error($con);
                exit();
            }

            $delete_question_query = "DELETE FROM questions WHERE id='$question_id'";
            if (!mysqli_query($con, $delete_question_query)) {
                echo "Error deleting question: " . mysqli_error($con);
                exit();
            }
        }
    }

    foreach ($questions as $q_index => $question) {
        $question_id = isset($_POST['question_ids'][$q_index]) ? mysqli_real_escape_string($con, $_POST['question_ids'][$q_index]) : null;
        $question_image = isset($uploaded_images[$q_index]) ? $uploaded_images[$q_index] : null;
        $question = mysqli_real_escape_string($con, $question);

        if ($question_id) {
            // Fetch existing image path if no new image is uploaded
            if ($question_image === null) {
                $query = "SELECT question_image FROM questions WHERE id='$question_id'";
                $result = mysqli_query($con, $query);
                $row = mysqli_fetch_assoc($result);
                $question_image = $row['question_image'];
            }
            // Update existing question
            $update_question_query = "UPDATE questions SET question='$question', question_image='$question_image' WHERE id='$question_id'";
            if (!mysqli_query($con, $update_question_query)) {
                echo "Error updating question: " . mysqli_error($con);
                exit();
            }
        } else {
            // Add new question
            $insert_question_query = "INSERT INTO questions (set_id, question, question_image, set_name) VALUES ('$q_id', '$question', '$question_image', '$set_name')";
            if (!mysqli_query($con, $insert_question_query)) {
                echo "Error inserting question: " . mysqli_error($con);
                exit();
            }
            $question_id = mysqli_insert_id($con); // Get the inserted question ID
            $new_questions_count++;
        }

        if (isset($answers[$q_index]) && is_array($answers[$q_index])) {
            foreach ($answers[$q_index] as $a_index => $answer) {
                $answer_id = isset($_POST['answer_ids'][$q_index][$a_index]) ? mysqli_real_escape_string($con, $_POST['answer_ids'][$q_index][$a_index]) : null;
                $answer = mysqli_real_escape_string($con, $answer);
                $is_correct = (isset($correct_answers[$q_index]) && $a_index == $correct_answers[$q_index]) ? 1 : 0;

                if ($answer_id) {
                    // Update existing answer
                    $update_answer_query = "UPDATE answers SET answer='$answer', is_correct='$is_correct' WHERE id='$answer_id'";
                    if (!mysqli_query($con, $update_answer_query)) {
                        echo "Error updating answer: " . mysqli_error($con);
                        exit();
                    }
                } else {
                    // Add new answer
                    $insert_answer_query = "INSERT INTO answers (question_id, answer, is_correct) VALUES ('$question_id', '$answer', '$is_correct')";
                    if (!mysqli_query($con, $insert_answer_query)) {
                        echo "Error inserting answer: " . mysqli_error($con);
                        exit();
                    }
                }
            }
        }
    }

    // Update total questions and set name in question_sets
    $total_questions = mysqli_real_escape_string($con, $_POST['total_question']);
    $remaining_questions = $total_questions - count($removed_questions) + $new_questions_count; // Adjust the count based on removals and additions

    $update_question_sets_query = "UPDATE question_sets SET total_questions='$remaining_questions', set_name='$set_name', batch_code='$batch_codes_str' WHERE set_id='$q_id'";
    if (!mysqli_query($con, $update_question_sets_query)) {
        echo "Error updating question set: " . mysqli_error($con);
        exit();
    }

    $_SESSION['message'] = "Questions and answers updated successfully.";
    header("Location:edit_questions.php");
    exit();
}else if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_by_set_id'])) {
  
        $q_id = mysqli_real_escape_string($con, $_POST['set_id']);

        // Delete answers related to the questions in the question set
        $delete_answers_query = "DELETE a FROM answers a
                                 INNER JOIN questions q ON a.question_id = q.id
                                 WHERE q.set_id='$q_id'";
        if (!mysqli_query($con, $delete_answers_query)) {
            echo "Error deleting answers: " . mysqli_error($con);
            exit();
        }

        // Delete questions in the question set
        $delete_questions_query = "DELETE FROM questions WHERE set_id='$q_id'";
        if (!mysqli_query($con, $delete_questions_query)) {
            echo "Error deleting questions: " . mysqli_error($con);
            exit();
        }

        // Delete the question set
        $delete_question_set_query = "DELETE FROM question_sets WHERE set_id='$q_id'";
        if (!mysqli_query($con, $delete_question_set_query)) {
            echo "Error deleting question set: " . mysqli_error($con);
            exit();
        }

        // Optional: Redirect or show success message
        $_SESSION['message'] = "Quiz set deleted successfully.";
        header("Location:edit_questions.php");
        exit();
    }
?>

