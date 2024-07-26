<?php 
include "conn.php";
session_start();
if (!isset($_SESSION['admin'])) {
    $_SESSION['message'] = "Please login first";
    header("Location: admin.php");
    exit();
}

// Initialize search variables
$ic_number_search = "";
$batch_code_search = "";

// Initialize result variable
$result = null;

// Check if the request method is POST and process accordingly
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ic_number_search = isset($_POST['ic_number_search']) ? $_POST['ic_number_search'] : "";
    $batch_code_search = isset($_POST['batch_code_search']) ? $_POST['batch_code_search'] : "";

    // Build the search query
    $query = "SELECT * FROM results WHERE 1=1"; // Base query

    if (!empty($ic_number_search)) {
        $query .= " AND ic_number LIKE '%" . mysqli_real_escape_string($con, $ic_number_search) . "%'";
    }

    if (!empty($batch_code_search)) {
        $query .= " AND batch_code LIKE '%" . mysqli_real_escape_string($con, $batch_code_search) . "%'";
    }

    // Execute the query
    $result = mysqli_query($con, $query);
} else {
    // Default query to display all results if no search is applied
    $query = "SELECT * FROM results";
    $result = mysqli_query($con, $query);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Results</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <style>
        .center-div {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 20vh;
        }
        .container-fluid {
            background-color: #f8f9fa; /* Light grey background */
            height: 100vh;
        }
        .table-responsive {
            margin-top: 20px;
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
                        <a class="nav-link" href="student_management.php">Student Management</a>
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

        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="text-center my-4">Student Results</h2>

                    <!-- Search Form -->
                    <form method="POST" class="form-inline justify-content-center">
                        <div class="form-group mx-sm-3 mb-2">
                            <label for="ic_number_search" class="sr-only">IC Number</label>
                            <input type="text" class="form-control" id="ic_number_search" name="ic_number_search" placeholder="IC Number" value="<?php echo htmlspecialchars($ic_number_search); ?>">
                        </div>
                        <div class="form-group mx-sm-3 mb-2">
                            <label for="batch_code_search" class="sr-only">Batch Code</label>
                            <input type="text" class="form-control" id="batch_code_search" name="batch_code_search" placeholder="Batch Code" value="<?php echo htmlspecialchars($batch_code_search); ?>">
                        </div>
                        <button type="submit" class="btn btn-primary mb-2">Search</button>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>IC Number</th>
                                    <th>Batch Code</th>
                                    <th>Set ID</th>
                                    <th>Quiz ID</th>
                                    <th>Result</th>
                                    <th>Total Questions</th>
                                    <th>Correct Answers</th>
                                    <th>Submitted At</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($result && mysqli_num_rows($result) > 0) {
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        echo "<tr>
                                            <td>{$row['ic_number']}</td>
                                            <td>{$row['batch_code']}</td>
                                            <td>{$row['set_id']}</td>
                                            <td>{$row['quiz_id']}</td>
                                            <td>{$row['result']}</td>
                                            <td>{$row['total_questions']}</td>
                                            <td>{$row['correct_answers']}</td>
                                            <td>{$row['created_at']}</td>
                                        </tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='8' class='text-center'>No results found</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>   
</body>
</html>
