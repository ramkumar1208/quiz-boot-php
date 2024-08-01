<?php
include "conn.php";
session_start();

if (!isset($_SESSION['admin'])) {
    $_SESSION['message'] = "Please login first";
    header("Location: admin.php");
    exit();
}

$result_data = [];
$quiz_name = "";
$set_name = "";
$questions_data = [];

// Function to convert data to CSV
function exportToCSV($data, $filename) {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment;filename="' . $filename . '"');
    $output = fopen('php://output', 'w');

    if (!empty($data)) {
        // Write column headers
        fputcsv($output, array_keys(reset($data))); // Get the headers from the first row
    }
    
    // Write data rows
    foreach ($data as $row) {
        fputcsv($output, $row);
    }
    
    fclose($output);
    exit();
}

if (isset($_GET['result_id'])) {
    $result_id = mysqli_real_escape_string($con, $_GET['result_id']);
    
    // Fetch complete result from the database
    $query = "SELECT * FROM results WHERE result_id = '$result_id'";
    $result = mysqli_query($con, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $result_data = mysqli_fetch_assoc($result);
        $quiz_id = $result_data['quiz_id'];
        $set_id = $result_data['set_id'];
        $student_id = $result_data['ic_number'];
        
        // Fetch quiz name
        $quiz_query = "SELECT quiz_topic FROM quiz_topics WHERE quiz_id = '$quiz_id'";
        $quiz_result = mysqli_query($con, $quiz_query);
        if ($quiz_result && mysqli_num_rows($quiz_result) > 0) {
            $quiz_name = mysqli_fetch_assoc($quiz_result)['quiz_topic'];
        }

        // Fetch set name
        $set_query = "SELECT set_name FROM question_sets WHERE set_id = '$set_id'";
        $set_result = mysqli_query($con, $set_query);
        if ($set_result && mysqli_num_rows($set_result) > 0) {
            $set_name = mysqli_fetch_assoc($set_result)['set_name'];
        }

        // Fetch questions, student answers, and correct answers
        $questions_query = "
        SELECT q.question, a_student.answer AS student_answer, a_correct.answer AS correct_answer
        FROM user_responses sa 
        JOIN questions q ON sa.question_id = q.id 
        JOIN answers a_correct ON q.id = a_correct.question_id AND a_correct.is_correct = 1
        JOIN answers a_student ON sa.answer_id = a_student.id
        WHERE sa.quiz_id = '$quiz_id' AND sa.set_id = '$set_id' AND sa.user_id = '$student_id'";
    
        $questions_result = mysqli_query($con, $questions_query) or die("Error in query: " . mysqli_error($con));
    
        if ($questions_result && mysqli_num_rows($questions_result) > 0) {
            while ($row = mysqli_fetch_assoc($questions_result)) {
                $questions_data[] = $row;
            }
        }

        // Combine result data and question data for CSV
        $combined_data = [];
        foreach ($questions_data as $question) {
            $combined_data[] = [
                'IC Number' => $result_data['ic_number'],
                'Batch Code' => $result_data['batch_code'],
                'Quiz Name' => $quiz_name,
                'Set Name' => $set_name,
                'Result' => $result_data['result'] == 1 ? 'Pass' : 'Fail',
                'Total Questions' => $result_data['total_questions'],
                'Correct Answers' => $result_data['correct_answers'],
                'Question' => $question['question'],
                'Student Answer' => $question['student_answer'],
                'Correct Answer' => $question['correct_answer'],
                'Submited At' => $result_data['created_at'],
            ];
        }
        
        if (isset($_GET['export']) && $_GET['export'] == 'csv') {
            // Define the directory and file path
            $directory = 'generated_files/';
            $fileName = 'result_data.csv';
            $filePath = $directory . $fileName;
            
            // Ensure the directory exists, create it if not
            if (!file_exists($directory)) {
                mkdir($directory, 0777, true);
            }
        
            // Call the function with the updated file path
            exportToCSV($combined_data, $filePath);
        }
    
    } else {
        $error_message = "No result found.";
    }
} else {
    $error_message = "Invalid result ID.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Page</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <style>
        .container-fluid {
            background-image: url("bg.jpg");
            background-size: cover;
            background-position: center;
            padding: 20px; /* Add padding to ensure content doesn't touch edges */
        }
        .table-container {
            background-color: #ffffff; /* White background for the table */
            border-radius: 5px; /* Rounded corners for a cleaner look */
            padding: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Optional shadow for depth */
            overflow-x: auto; /* Horizontal scrolling if needed */
        }
        table {
            background-color: #ffffff; /* Ensure table has white background */
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
        .page-content {
            max-width: 100%; /* Ensure the content fits within the container */
            overflow-x: auto; /* Adds horizontal scrolling if content exceeds width */
        }
    </style>
</head>
<body>
<div class="container-fluid">
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
            <a class="navbar-brand" href="index.php">
                <img src="logo.png" alt="" width="50px"> 
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
                    <a class="nav-link" href="department_management.php">Departments</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="student_management.php">Students management</a>
                </li>
            </ul>
            <div class="bs-example">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12 bg-light text-right">
                            <?php 
                            if (isset($_SESSION['admin'])) { 
                                $user_email = $_SESSION['admin'];
                                echo $user_email;  
                                ?>
                                <a href="logout.php"><button type="button" class="btn btn-primary">Log-out</button></a>
                            <?php } else { ?>        
                                <a href="admin_login.php"><button type="button" class="btn btn-primary">Login</button></a>
                            <?php } ?>    
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>
    <div class="container mt-5 page-content">
        <?php if (isset($error_message)) { ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php } else if (!empty($result_data)) { ?>
            <h2 class="text-center mb-4">Complete Result</h2>
            <div class="table-container">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Field</th>
                            <th>Value</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>IC Number</td>
                            <td><?php echo htmlspecialchars($result_data['ic_number']); ?></td>
                        </tr>
                        <tr>
                            <td>Batch Code</td>
                            <td><?php echo htmlspecialchars($result_data['batch_code']); ?></td>
                        </tr>
                        <tr>
                            <td>Quiz Name</td>
                            <td><?php echo htmlspecialchars($quiz_name); ?></td>
                        </tr>
                        <tr>
                            <td>Set Name</td>
                            <td><?php echo htmlspecialchars($set_name); ?></td>
                        </tr>
                        <tr>
                            <td>Result</td>
                            <td><?php echo $result_data['result'] == 1 ? 'Pass' : 'Fail'; ?></td>
                        </tr>
                        <tr>
                            <td>Total Questions</td>
                            <td><?php echo htmlspecialchars($result_data['total_questions']); ?></td>
                        </tr>
                        <tr>
                            <td>Correct Answers</td>
                            <td><?php echo htmlspecialchars($result_data['correct_answers']); ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <h3 class="text-center mb-4">Questions and Answers</h3>
            <div class="table-container">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Question</th>
                            <th>Student Answer</th>
                            <th>Correct Answer</th>
                            <th>status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        foreach ($questions_data as $question) { ?>
                            <tr>
                                <td><?php echo htmlspecialchars($question['question']); ?></td>
                                <td><?php 
                                

                                echo htmlspecialchars($question['student_answer']); ?></td>
                                
                                <td><?php echo htmlspecialchars($question['correct_answer']); ?></td>
                                <td><?php ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
            <!-- Export button -->
            <a href="?result_id=<?php echo urlencode($result_id); ?>&export=csv" class="btn btn-primary">Export to CSV</a>
        <?php } else { ?>
            <div class="alert alert-info">No data available.</div>
        <?php } ?>
    </div>
</div>
</body>
</html>
