<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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
    $user_id = $_POST['ic_number']; 
    $set_id = intval($_POST['set_id']);
    $quiz_id = intval($_POST['quiz_id']);
    $batch_code = intval($_POST['batch_code']);  
    $answers = $_POST['answers'];

    // Fetch the pass percentage and total questions
    $find_per = "SELECT pass_percentage, num_questions FROM quiz_topics WHERE quiz_id = ?";
    $stmt = $conn->prepare($find_per);
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("i", $quiz_id);
    $stmt->execute();
    $find_res = $stmt->get_result();
    if ($find_res->num_rows > 0) {
        $row = $find_res->fetch_assoc();
        $pass_percentage = $row['pass_percentage'];
        $total_questions = $row['num_questions'];
        $questions_to_pass = ceil(($total_questions * $pass_percentage) / 100);
    } else {
        die("No quiz found with ID: " . $quiz_id);
    }

    // Insert user responses
    foreach ($answers as $question_id => $answer_id) {
        $stmt = $conn->prepare("INSERT INTO user_responses (user_id, set_id, question_id, answer_id, quiz_id,batch_code) VALUES (?, ?, ?, ?, ?,?)");
        if (!$stmt) {
            die("Prepare failed: " . $conn->error);
        }
        $stmt->bind_param("siiiii", $user_id, $set_id, $question_id, $answer_id, $quiz_id,$batch_code);
        $stmt->execute();
    }

    // Calculate the number of correct answers
    $correct_count_query = "
        SELECT COUNT(*) AS correct_count
        FROM user_responses ur
        JOIN answers a ON ur.answer_id = a.id
        WHERE ur.user_id = ? AND ur.quiz_id = ? AND ur.set_id = ? AND a.is_correct = 1
    ";
    $stmt = $conn->prepare($correct_count_query);
    if (!$stmt) {
        die("Prepare failed: " . $conn->error . " | SQL: " . $correct_count_query);
    }
    $stmt->bind_param("sii", $user_id, $quiz_id, $set_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $correct_count_row = $result->fetch_assoc();
        $correct_count = $correct_count_row['correct_count'];
    } else {
        $correct_count = 0;
    }

    // Determine if the user passed the quiz
    $result_status = ($correct_count >= $questions_to_pass) ? 1 : 0;

    // Update or insert the result into the results table
    $upsert_query = "
        INSERT INTO results (ic_number, set_id, quiz_id, result, total_questions, correct_answers,batch_code)
        VALUES (?, ?, ?, ?, ?, ?,?)
        ON DUPLICATE KEY UPDATE result = VALUES(result), total_questions = VALUES(total_questions), correct_answers = VALUES(correct_answers), batch_code = VALUES(batch_code)
    ";
    $stmt = $conn->prepare($upsert_query);
    if (!$stmt) {
        die("Prepare failed: " . $conn->error . " | SQL: " . $upsert_query);
    }
    $stmt->bind_param("siiiiii", $user_id, $set_id, $quiz_id, $result_status, $total_questions, $correct_count,$batch_code);
    $stmt->execute();
    $_SESSION['message']="Quiz submitted successfully";
    // Redirect to the result page
    header("Location: viewquiz.php");
    exit(); // Ensure that no further code is executed after the redirection

    // // Output the result to the user (commented out as redirection is used)
    // if ($result_status == 1) {
    //     echo "Quiz submitted successfully! You passed.";
    // } else {
    //     echo "Quiz submitted successfully! You failed.";
    // }
}

$conn->close();
?>
