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
    // Escape the batch code
    $batch_code = $conn->real_escape_string(trim($batch_code));

    // Prepare the SQL query using FIND_IN_SET
    $sql = "SELECT set_id, set_name, total_questions 
            FROM question_sets 
            WHERE FIND_IN_SET(?, batch_code)";
    $stmt = $conn->prepare($sql);
    
    // Bind the parameter
    $stmt->bind_param("s", $batch_code);
    
    $stmt->execute();
    $result = $stmt->get_result();

    $question_sets = [];
    while ($row = $result->fetch_assoc()) {
        $question_sets[] = [
            'id' => $row['set_id'], 
            'name' => $row['set_name'],
            'total_questions' => $row['total_questions']
        ];
    }
    $stmt->close();

    header('Content-Type: application/json');
    echo json_encode($question_sets);
} else {
    echo json_encode([]);
}

$conn->close();
?>
