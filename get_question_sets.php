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

$batch_code = isset($_GET['batch_code']) ? $_GET['batch_code'] : '';

if ($batch_code) {
    $stmt = $conn->prepare("SELECT set_id, set_name FROM question_sets WHERE batch_code = ?");
    $stmt->bind_param("s", $batch_code);
    $stmt->execute();
    $result = $stmt->get_result();

    $question_sets = [];
    while ($row = $result->fetch_assoc()) {
        $question_sets[] = ['id' => $row['set_id'], 'name' => $row['set_name']];
    }
    $stmt->close();

    header('Content-Type: application/json');
    echo json_encode($question_sets);
} else {
    echo json_encode([]);
}

$conn->close();
?>
