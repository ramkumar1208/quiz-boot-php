<?php
include "conn.php";
session_start();
if (!isset($_SESSION['admin'])) {
    $_SESSION['message'] = "Please login first";
    header("Location: admin.php");
    exit();
}

// Initialize search variables
$ic_number_search = isset($_POST['ic_number_search']) ? mysqli_real_escape_string($con, $_POST['ic_number_search']) : "";
$batch_code_search = isset($_POST['batch_code_search']) ? mysqli_real_escape_string($con, $_POST['batch_code_search']) : "";

// Build the search query
// $query = "SELECT * FROM results WHERE 1=1"; // Base query
$query = "SELECT r.*, u.user_name, qs.set_name, qt.quiz_topic
          FROM results r
          JOIN users u ON r.ic_number = u.ic_number
          JOIN question_sets qs ON r.set_id = qs.set_id
          JOIN quiz_topics qt ON r.quiz_id = qt.quiz_id";

if (!empty($ic_number_search)) {
    $query .= " AND ic_number = '$ic_number_search'";
}

if (!empty($batch_code_search)) {
    $query .= " AND batch_code = '$batch_code_search'";
}

// Execute the query
$result = mysqli_query($con, $query);

$results = [];
if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $results[] = $row;
    }
}

echo json_encode($results);
exit();
?>
