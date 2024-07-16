<?php
include "conn.php";
session_start();

if (!isset($_SESSION['admin'])) {
    $_SESSION['message'] = "Please login first";
    header("Location: admin.php");
    exit();
}

if (isset($_GET['edit_question']) && !empty($_GET['edit_qid'])) {
    // Fetch the question details from the database based on edit_qid
    $edit_qid = $_GET['edit_qid'];
    $question_sql = "SELECT * FROM `questions` WHERE `qid` = '$edit_qid'";
    $question_result = mysqli_query($con, $question_sql);
    $question_row = mysqli_fetch_assoc($question_result);

    // Fetch options for the question
    $options_sql = "SELECT * FROM `answers` WHERE `ans_id` = '$edit_qid'";
    $options_result = mysqli_query($con, $options_sql); 
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['edit_question'])) {
        // Handle form submission for editing the question
        $new_question = $_POST['question'];
        // Update the question in the database
        $update_question_sql = "UPDATE `questions` SET `question` = '$new_question' WHERE `qid` = '$edit_qid'";
        mysqli_query($con, $update_question_sql);

        // Loop through options and answers submitted in the form and update them in the database
        foreach ($_POST['options'] as $option_id => $option) {
            $update_option_sql = "UPDATE `answers` SET `answer` = '$option' WHERE `aid` = '$option_id'";
            mysqli_query($con, $update_option_sql);
        }

        $_SESSION['message'] = "Question updated successfully";
        header("Location: editquestion.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Question</title>
    <link rel="stylesheet" href="s_new.css" />
    <!-- Unicons -->
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="quiz_css.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <!-- Add your CSS and JavaScript links here -->
</head>
<body>
    <header class="header">
        <!-- Your header content here -->
    </header>
    <main>
        <div class="home">
            <div class="center-div">
                <?php if(isset($_SESSION['message']) && !empty($_SESSION['message'])): ?>
                    <div class="alert alert-danger alert-dismissible">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> 
                        <?php echo $_SESSION['message']; ?>
                    </div>
                <?php endif; ?>
            </div>
            
            <section class="main-section">
            <form action="editquestion.php?edit_qid=<?php echo $edit_qid; ?>" method="post">
        <div class="container">
          <div class="row justify-content-center">
               <div class="col-md-8">
                <div class="card my-2 p-3">
                  <div class="card-body">
                    <h5 class="card-title py-2">Question </h5>
                      <div class="form-check">
                      <textarea class="form-control" name="question" ><?php echo $question_row['question']; ?></textarea><br>
                        <label for="option">Option</label><br>
                       A )<input type="text" class="form-check-input" name="option1"><br>
                       B )<input type="text" class="form-check-input" name="option2"><br>
                       C )<input type="text" class="form-check-input" name="option3"><br>
                       D )<input type="text" class="form-check-input" name="option4"><br> 
                        <label for="answer">Answer</label>
                       <input type="text" class="form-check-input" name="answer"><br>
                      </div>
                  </div>
                </div>
              </div>
            <div class="col-md-8 mb-5">
              <button type="submit" class="btn btn-success" name="answer-submit">Submit Question</button>
            </div>
          </div>
        </div>
    </form>
            </section>
        </div>
    </main>
    <!-- Your footer content here -->
</body>
</html>
