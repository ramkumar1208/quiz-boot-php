<?php 
include "conn.php";
error_reporting(E_ALL);
// require_once("function.php");
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if(isset($_POST['edit_quiz_by_id'])){
    echo "hi";
    $quiz_topic = $_POST['quiz_topic'];
    $batch_code = $_POST['batch_code'];
    $quiz_link = $_POST['quiz_Link'];
    $num_questions = $_POST['how_many_questions'];
    $total_marks = $_POST['total_marks'];
    $quiz_date = $_POST['quiz_date'];
    $quiz_time=$_POST['quiz_time'];
    $timings = $_POST['timings'];
    $id=$_POST['quiz_id'];


    $stmt = $con->prepare("UPDATE quiz_topics SET 
    quiz_topic = ?, 
    batch_code = ?, 
    quiz_link = ?, 
    num_questions = ?, 
    total_marks = ?, 
    quiz_date = ?, 
    total_time = ?, 
    quiz_time = ?
WHERE quiz_id = ?");

$stmt->bind_param("sssiisssi", $quiz_topic,  $batch_code,   $quiz_link,   $num_questions,     $total_marks,  $quiz_date,       $timings, $quiz_time,  $id);

if ($stmt->execute()) {
    $_SESSION['message'] = "Quiz Edited successfully.";
    header("Location: viewquiz.php"); 
    exit(); 
} else {
    $_SESSION['message'] = "Error: " . $stmt->error;
    header("Location: editquiz.php"); 
    exit(); 
}



  }
}
?>