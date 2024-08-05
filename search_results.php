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
$student_name_search = isset($_POST['student_name_search']) ? mysqli_real_escape_string($con, $_POST['student_name_search']) : "";
$set_name_search = isset($_POST['set_name_search']) ? mysqli_real_escape_string($con, $_POST['set_name_search']) : "";
$quiz_topic_search = isset($_POST['quiz_topic_search']) ? mysqli_real_escape_string($con, $_POST['quiz_topic_search']) : "";
$result_search = isset($_POST['result_search']) ? mysqli_real_escape_string($con, $_POST['result_search']) : "";
$submitted_at_search = isset($_POST['submitted_at_search']) ? mysqli_real_escape_string($con, $_POST['submitted_at_search']) : "";
$from_date = $_POST['from_date'] ?? '';
$to_date = $_POST['to_date'] ?? '';
// Build the search query
$query = "SELECT r.*, u.user_name, qs.set_name, qt.quiz_topic
          FROM results r
          JOIN users u ON r.ic_number = u.ic_number
          JOIN question_sets qs ON r.set_id = qs.set_id
          JOIN quiz_topics qt ON r.quiz_id = qt.quiz_id
          WHERE 1=1";

if (!empty($ic_number_search)) {
    $query .= " AND r.ic_number LIKE '%$ic_number_search%'";
}

if (!empty($batch_code_search)) {
    $query .= " AND r.batch_code LIKE '%$batch_code_search%'";
}

if (!empty($student_name_search)) {
    $query .= " AND r.student_name LIKE '%$student_name_search%'";
}

if (!empty($set_name_search)) {
    $query .= " AND qs.set_name LIKE '%$set_name_search%'";
}

if (!empty($quiz_topic_search)) {
    $query .= " AND qt.quiz_topic LIKE '%$quiz_topic_search%'";
}

if (!empty($result_search)) {
    $query .= " AND r.result = '$result_search'";
}

if (!empty($submitted_at_search)) {
    switch ($submitted_at_search) {
        case 'year':
            $pastDate = date('Y-m-d', strtotime('-1 year'));
            $query .= " AND DATE(r.created_at) >= '$pastDate'";
            break;
        case 'month':
            $pastDate = date('Y-m-d', strtotime('-1 month'));
            $query .= " AND DATE(r.created_at) >= '$pastDate'";
            break;
        case 'week':
            $pastDate = date('Y-m-d', strtotime('-1 week'));
            $query .= " AND DATE(r.created_at) >= '$pastDate'";
            break;
        case 'custom':
            if (!empty($from_date) && !empty($to_date)) {
                $query .= " AND DATE(r.created_at) BETWEEN '$from_date' AND '$to_date'";
            }
            break;
    }
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
