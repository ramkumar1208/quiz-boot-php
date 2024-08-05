<?php
  error_reporting(E_ALL);
    session_start();
    if (!isset($_SESSION['user'])) {
    include "conn.php";
    $_SESSION['message']="";
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $u_ic=$_POST['u_ic'];
        $u_batch=$_POST['u_batch'];
        
        $session_id = session_id();

        $search_query="select * from users where ic_number='$u_ic' && batch_code='$u_batch'";
        $search_users=mysqli_query($con,$search_query);
        if(mysqli_num_rows($search_users) == 1){
          $sql = "SELECT * FROM login_sessions WHERE ic_number = '$u_ic' AND batch_code = '$u_batch'";
        $result = $con->query($sql);
        
        if ($result->num_rows > 0) {
          $row = $result->fetch_assoc();
          if ($row['session_id'] != $session_id) {
              $_SESSION['logout_user']=$u_ic;
              $_SESSION['message'] = "You are already logged in from another device.";
              header("Location: index.php");
              exit;
          }
        }else{
           // Insert/update login session
        $sql = "INSERT INTO login_sessions (ic_number, batch_code, session_id) VALUES ('$u_ic', '$u_batch', '$session_id')
        ON DUPLICATE KEY UPDATE session_id = '$session_id'";
        if ($con->query($sql) === TRUE) {
           //echo "Login successful";
          $_SESSION['user']=$u_ic;
          $_SESSION['batch']=$u_batch;
          header("Location: viewquiz.php");
          
        } else {
            echo "Error: " . $sql . "<br>" . $con->error;
        }
        }
        }else if(mysqli_num_rows($search_users) == 0){
          $_SESSION['message']="user not found";
          header("Location: login1.php");
          exit;
        }
    }
   
}else{
    header("Location: viewquiz.php");
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

.login-box input[type="text"], 
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
                    <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
                        <li class="nav-item active">
                            <a class="nav-link" href="index.php">Home<span class="sr-only">(current)</span></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="viewquiz.php">Quiz</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Contact</a>
                        </li>
                        <!-- <li class="nav-item">
                            <a class="nav-link" href="admin.php">Admin</a>
                        </li> -->
                    </ul>
                    <div class="ml-auto">
                        <?php if (isset($_SESSION['user'])): ?>
                            <?php $user_email = $_SESSION['user']; ?>
                            <span class="navbar-text"><?php echo htmlspecialchars($user_email); ?></span>
                            <a href="logout.php"><button type="button" class="btn btn-primary">Log-out</button></a>
                        <?php else: ?>
                            <a href="login1.php"><button type="button" class="btn btn-primary">Login</button></a>
                        <?php endif; ?>
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
                <h1>Login</h1>
                <form action="login1.php" method="post">
                    <label for="u_ic">IC Number</label>
                    <input type="text" id="u_ic" name="u_ic" placeholder="Enter IC Number" required>
                    <label for="u_batch">Batch Code</label>
                    <input type="text" id="u_batch" name="u_batch" placeholder="Enter Batch Code" required>
                    <input type="submit" value="Login">
                </form>
            </div>
        </div>
    </div>
</body>
</html>
