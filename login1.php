<?php
  error_reporting(E_ALL);
    session_start();
    include "conn.php";
    $_SESSION['message']="";
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $u_ic=$_POST['u_ic'];
        $u_batch=$_POST['u_batch'];
        
        $session_id = session_id();

        $search_query="select * from `users` where `ic_number`='$u_ic' && `batch_code`='$u_batch'";
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
    
?>
<span style="font-family: verdana, geneva, sans-serif;"><!DOCTYPE html>
<html lang="en">
  <head>
    <title>Quiz App</title>
    <link rel="stylesheet" href="s.css" />
    <link
      href="https://fonts.googleapis.com/css2?family=Roboto:wght@300&display=swap"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <style>
    .center-div {
      display: flex;
      justify-content: center;
      align-items: center;
      height: 10vh;
    }
  </style>
  </head>
  <body>
  <header class="header">
      <nav class="nav">
      <!-- <a class="navbar-brand" href="#">
      <img src="logo.png" alt="" width=50px> 
    </a> -->

        <ul class="nav_items">
          <li class="nav_item">
            <a href="index.php" class="nav_link">Home</a>
            <a href="quiz.php" class="nav_link">Quiz</a>
            <a href="#" class="nav_link">Contact</a>
          </li>
        </ul>
        <a href="login1.php"><button class="button" id="form-open">Login Here</button></a>
      </nav>
    </header>
    
    <?php if (isset($_SESSION['message']) && !empty($_SESSION['message'])) {
        $message = $_SESSION['message'];
        // $_SESSION['message'] = ""; 
        ?>
        <div class="center-div">
        <div class="alert alert-danger alert-dismissible">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            <?php echo $message; ?>
            <?php if ($message === "You are already logged in from another device.") {  ?>
                <a href="logout.php"><button>Logout That Device</button></a>
            <?php } ?>
        </div>
        </div>
    <?php } ?>


    <div class="login-box">
      <h1>Login</h1>
      <form action="login1.php" method="post">
        <label>IC Number</label>
        <input type="text" placeholder="Enter IC Number" required name="u_ic"/>
        <label>Batch Code</label>
        <input type="text" placeholder="Enter Batch Code" required name="u_batch"/>
        <input type="submit" value="Login" />
      </form>
      <!-- <p class="para-tag">
      Not have an account? <a href="register2.php">Sign Up Here</a>
    </p> -->
    </div>
  
  </body>
</html></span>