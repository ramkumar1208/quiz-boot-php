<?php
    session_start();
    include "conn.php";
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $u_name=$_POST['u_name'];
        $u_email=$_POST['u_email'];
        $u_mobile=$_POST['u_mobile'];
        $u_dob=$_POST['date_of_birth'];
        // $u_pass=$_POST['u_pass'];
        $u_ic=$_POST['u_ic'];
        $u_batch=$_POST['u_batch'];
        // $search_query="select * from `users` where `user_email`='$u_email'";
        // $search_users=mysqli_query($con,$search_query);
        // if(mysqli_num_rows($search_users)>0){
            
        // }
        $insert_query="insert into users(`user_name`,`mobile`,`user_email`,`user_dob`,`ic_number`,`batch_code`) values('$u_name','$u_mobile','$u_email','$u_dob','$u_ic','$u_batch')";
        $insert_data=mysqli_query($con,$insert_query);
        if($insert_data){
          // echo $insert_query;
          $_SESSION['message']="Student added sucessfully";
          header("Location: student_management.php");
        }else{
          $_SESSION['message']="Error occurs";
          header("Location: student_management.php");
        }
    }
    
?>
<span style="font-family: verdana, geneva, sans-serif;"><!DOCTYPE html>
<html lang="en">
  <head>
    <title>Add Students</title>
    <link rel="stylesheet" href="s.css" />
    <link
      href="https://fonts.googleapis.com/css2?family=Roboto:wght@300&display=swap"
      rel="stylesheet"
    />
  </head>
  <body>
  <header class="header">
      <nav class="nav">
      <a class="navbar-brand" href="index.php">
      <img src="logo.png" alt="" width=50px > 
    </a>
        <ul class="nav_items">
          <li class="nav_item">
            <a href="admin.php" class="nav_link">Home</a>
            <a href="admin_quiz" class="nav_link">Quiz</a>
            <a href="" class="nav_link">Contact</a>
          </li>
        </ul>
        <!-- <a href="login.php"><button class="button" id="form-open">Login</button></a> -->
      </nav>
    </header>
    <div class="signup-box">
      <h1>Add Students Here</h1>
      <!-- <h4>It's free and only takes a minute</h4> -->
      <form action="register2.php" method="post">
        <label>Name</label>
        <input type="name" placeholder="Enter your name" required name="u_name"/>
        <label>Email</label>
        <input type="email" placeholder="Enter your email" required name="u_email"/>
        <label>Mobile</label>
        <input type="text" placeholder="Enter your mobile" required name="u_mobile"/>
        <label>Date of Birth</label>
        <input type="date" placeholder="Enter your date of birth" required name="date_of_birth"/>
        <label>IC Number</label>
        <input type="text" placeholder="Enter IC Number" required name="u_ic"/>
        <label>Batch Code</label>
        <input type="name" placeholder="Enter Batch code" required name="u_batch"/>        
        <!-- <label>Password</label>
        <input type="password" placeholder="" name="u_pass"/>
        <label>Confirm Password</label>
        <input type="password" placeholder="" /> -->
        <input type="submit" value="Add Student" />
      </form>
      <!-- <p class="para-tag">
      Already have an account? <a href="login1.php">Login here</a>
    </p> -->
    </div>
    
  </body>
</html></span>