<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "quiz_app";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $set_name = $_POST['set_name'];
    $batch_codes = $_POST['batch_code']; // This is an array of batch codes
    $questions = $_POST['questions'];
    $answers = $_POST['answers'];
    $correct_answers = $_POST['correct_answer'];

    // Convert batch codes array to a comma-separated string
    $batch_codes_str = implode(",", $batch_codes);

    $target_dir = "uploads/";
    $total_questions = count($questions);

    // Insert the set name and batch codes into the question_sets table
    $stmt = $conn->prepare("INSERT INTO question_sets (set_name, batch_code, total_questions) VALUES (?, ?, ?)");
    $stmt->bind_param("ssi", $set_name, $batch_codes_str, $total_questions);
    $stmt->execute();
    $set_id = $stmt->insert_id;

    for ($i = 0; $i < count($questions); $i++) {
        $question = $questions[$i];
        $question_image = "";
        if (!empty($_FILES['question_images']['name'][$i])) {
            $question_image = $target_dir . basename($_FILES["question_images"]["name"][$i]);
            move_uploaded_file($_FILES["question_images"]["tmp_name"][$i], $question_image);
        }

        // Insert question into the questions table
        $stmt = $conn->prepare("INSERT INTO questions (set_id, question, question_image, set_name) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isss", $set_id, $question, $question_image, $set_name);
        $stmt->execute();
        $question_id = $stmt->insert_id;

        // Insert answers into the answers table
        for ($j = 0; $j < count($answers[$i]); $j++) {
            $answer = $answers[$i][$j];
            $is_correct = ($correct_answers[$i] == $j) ? 1 : 0;

            $stmt = $conn->prepare("INSERT INTO answers (question_id, answer, is_correct) VALUES (?, ?, ?)");
            $stmt->bind_param("isi", $question_id, $answer, $is_correct);
            $stmt->execute();
        }
    }
    $_SESSION['message']="Questions added successfully!";
    echo "Questions added successfully!";
    header("Location : ad_que.php");
}

$conn->close();
?>
