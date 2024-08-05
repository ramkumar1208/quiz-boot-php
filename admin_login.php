<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
include "conn.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['message'] = "";
    $u_email = $_POST['u_email'];
    $u_pass = $_POST['u_pass'];
    $search_query = "SELECT * FROM `teachers` WHERE `t_email`='$u_email' AND `t_pass`='$u_pass'";
    $search_users = mysqli_query($con, $search_query);

    if (mysqli_num_rows($search_users) >= 1) {
        $_SESSION['admin'] = $u_email;
        $_SESSION['message'] = "";
        echo "<script>window.location.href = 'admin.php';</script>";
    } else {
        $_SESSION['message'] = "Admin not found";
        header("Location: admin_login.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz App</title>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <style>
      body, html {
    height: 100%;
    margin: 0;
    overflow: hidden;
}

.container-fluid {
    height: 100%;
    background-image: url("bg.jpg");
    background-size: cover;
    background-position: center;
    background-attachment: fixed;
}

.center-div {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100%;
}

.login-box {
    background: rgba(255, 255, 255, 0.8);
    padding: 2rem;
    border-radius: 10px;
    box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.2);
    max-width: 400px;
    width: 100%;
    margin: 1.5rem;
}

.login-box h1 {
    margin-bottom: 1.5rem;
    text-align: center;
}

.login-box label {
    margin-bottom: 0.5rem;
    display: block;
}

.login-box input[type="email"], 
.login-box input[type="password"] {
    width: 100%;
    margin-bottom: 1rem;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
}

.login-box input[type="submit"] {
    background: #007bff;
    color: white;
    border: none;
    padding: 10px;
    border-radius: 5px;
    width: 100%;
    cursor: pointer;
    transition: background 0.3s;
}

.login-box input[type="submit"]:hover {
    background: #0056b3;
}

    </style>
</head>
<body>
    <div class="container-fluid">
        <header class="header">
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <a class="navbar-brand" href="index.php">
                    <img src="logo.png" alt="Logo" width="50">
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
                <a class="navbar-brand" href="index.php">
                <img src="logo.png" alt="" width=50px>
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
            </ul>

            <div class="bs-example">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12 bg-light text-right">
                            <?php if (isset($_SESSION['admin'])) {
                                $user_email = $_SESSION['admin'];
                                echo $user_email; ?>
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
        </header>

        <?php if (isset($_SESSION['message']) && !empty($_SESSION['message'])): ?>
            <div class="center-div">
                <div class="alert alert-danger alert-dismissible">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <?php echo $_SESSION['message']; ?>
                    <?php if ($_SESSION['message'] === "You are already logged in from another device."): ?>
                        <a href="logout.php"><button>Logout That Device</button></a>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>

        <div class="center-div">
            <div class="login-box">
                <h1>Admin Login Here</h1>
                <form action="admin_login.php" method="post">
                <label>Email</label><br>
                                    <input type="email" placeholder="Enter your email" required name="u_email"/><br>
                                    <label>Password</label><br>
                                    <input type="password" placeholder="Enter your password" required name="u_pass"/><br><br>
                                    <button type="submit" class="btn btn-success" name="answer-submit">Login</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
