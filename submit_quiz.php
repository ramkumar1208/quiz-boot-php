<?php
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
    $user_id = 1; // Assume a static user ID for this example. Replace with actual user ID in a real application.
    $set_id = intval($_POST['set_id']);
    $answers = $_POST['answers'];

    foreach ($answers as $question_id => $answer_id) {
        $stmt = $conn->prepare("INSERT INTO user_responses (user_id, set_id, question_id, answer_id) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiii", $user_id, $set_id, $question_id, $answer_id);
        $stmt->execute();
    }

    echo "Quiz submitted successfully!";
}

$conn->close();
?>
