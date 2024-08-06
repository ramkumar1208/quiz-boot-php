<?php
include "conn.php";
error_reporting(E_ALL);
session_start();

if (!isset($_SESSION['admin'])) {
    $_SESSION['message'] = "Admin please login first";
    header('Location: admin.php');
    exit();
}
$admin = $_SESSION['admin'];

$view_query = null; // Initialize the variable

if (isset($_POST['search'])) {
    $batch_code = $_POST['batch_code'];
    
    if (!empty($batch_code)) {
        // Prepare the query with FIND_IN_SET if batch_code is not empty
        $view_question = "SELECT * FROM question_sets WHERE FIND_IN_SET(?, batch_code)";
        $stmt = $con->prepare($view_question);
        $stmt->bind_param("s", $batch_code);
    } else {
        // Prepare the query to select all records if batch_code is empty
        $view_question = "SELECT * FROM question_sets";
        $stmt = $con->prepare($view_question);
    }
    
    // Execute the query and get results
    $stmt->execute();
    $view_query = $stmt->get_result();
} else {
    // Prepare the query to select all records if no search is performed
    $view_question = "SELECT * FROM question_sets";
    $stmt = $con->prepare($view_question);
    $stmt->execute();
    $view_query = $stmt->get_result();
}

// Close the prepared statement
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Quiz App</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <style>
        .container-bg {
            background-image: url("bg.jpg");
            background-size: cover;
            background-position: center;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .view-quizzes {
            background-color: white;
            max-width: 800px;
            margin: 20px auto;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .search-input {
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .center-div {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .alert {
            margin: 20px;
        }
    </style>
</head>
<body>
<div class="container-bg">
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
                    <a class="nav-link" href="student_management.php">Students Management</a>
                </li>
            </ul>
            <div class="ml-auto">
                <?php 
                if (isset($_SESSION['admin'])) { 
                    $admin_email = $_SESSION['admin'];
                    echo $admin_email; ?>
                    <a href="logout.php"><button type="button" class="btn btn-primary">Log-out</button></a>
                <?php } else { ?>
                    <a href="admin_login.php"><button type="button" class="btn btn-primary">Login</button></a>
                <?php } ?>
            </div>
        </div>
    </nav>

    <div class="view-quizzes">
        <h2>Quiz Schedule</h2>
        <form method="post" action="">
            <label for="batch_code">Search by Batch Code</label>
            <input type="text" name="batch_code" id="batch_code" class="form-control search-input" placeholder="Enter Batch Code">
            <button type="submit" name="search" class="btn btn-primary mt-2">Search</button>
        </form>
        <?php if ($view_query && mysqli_num_rows($view_query) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Batch</th>
                    <th>Set Name</th>
                    <th>Total Questions</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($view_query)): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['batch_code']); ?></td>
                    <td><?php echo htmlspecialchars($row['set_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['total_questions']); ?></td>
                    <td>
                        <form action="edit_ques_by_id.php" method="post">
                            <input type="hidden" name="set_id" value="<?php echo htmlspecialchars($row['set_id']); ?>">
                            <input type="hidden" name="set_name" value="<?php echo htmlspecialchars($row['set_name']); ?>">
                            <input type="hidden" name="batch_code" value="<?php echo htmlspecialchars($row['batch_code']); ?>">
                            <input type="submit" name="edit" value="Edit" class="btn btn-warning btn-sm">
                            <input type="submit" name="delete_by_set_id" value="Delete" onclick="return confirmDelete();" class="btn btn-danger btn-sm">
                        </form>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <?php else: ?>
            <p>No question sets available</p>
        <?php endif; ?>
    </div>

    <?php if (isset($_SESSION['message']) && !empty($_SESSION['message'])): ?>
    <div class="center-div">
        <div class="alert alert-danger alert-dismissible">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            <?php echo $_SESSION['message']; $_SESSION['message'] = ""; ?>
            <?php if ($_SESSION['message'] === "You are already logged in from another device.") { ?>
                <a href="logout.php"><button class="btn btn-danger">Logout That Device</button></a>
            <?php } ?>
        </div>
    </div>
    <?php endif; ?>
</div>

<script>
    function confirmDelete() {
        return confirm('Are you sure you want to delete this quiz?');
    }
</script>
</body>
</html>
