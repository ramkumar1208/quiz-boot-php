<?php
    include "conn.php";
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $u_name=$_POST['u_name'];
        $u_email=$_POST['u_email'];
        $u_mobile=$_POST['u_mobile'];
        // $u_dob=$_POST['date_of_birth'];
        $u_pass=$_POST['u_pass'];
        $search_query="select * from `teachers` where `t_email`='$u_email'";
        $search_users=mysqli_query($con,$search_query);
        if(mysqli_num_rows($search_users)>0){
            
        }
        $insert_query="insert into `teachers`(`t_name`,`t_email`,`t_mobile`,`t_pass`) values('$u_name','$u_email','$u_mobile','$u_pass')";
        $insert_data=mysqli_query($con,$insert_query);
        if($insert_data){
          header("Location: admin_login.php");
        }else{
            die("Error: " . mysqli_error($con));
            //header("Location: admin_register.php");
        }
    }
    
?>
<span style="font-family: verdana, geneva, sans-serif;"><!DOCTYPE html>
<html lang="en">
  <head>
    <title>Sign Up | By Code Info</title>
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
            <a href="#" class="nav_link">Home</a>
            <a href="#" class="nav_link">Quiz</a>
            <a href="#" class="nav_link">Contact</a>
          </li>
        </ul>
        <a href="login.php"><button class="button" id="form-open">Login</button></a>
      </nav>
    </header>
    <div class="signup-box">
      <h1>Admin Register</h1>
      <h4>It's free and only takes a minute</h4>
      <form action="admin_register.php" method="post">
        <label>Name</label>
        <input type="name" placeholder="Enter your name" required name="u_name"/>
        <label>Email</label>
        <input type="email" placeholder="Enter your email" required name="u_email"/>
        <label>Mobile</label>
        <input type="text" placeholder="Enter your mobile" required name="u_mobile"/>
        <!-- <label>Date of Birth</label>
        <input type="date" placeholder="Enter your date of birth" required name="date_of_birth"/> -->
        <label>Password</label>
        <input type="password" placeholder="" name="u_pass"/>
        <label>Confirm Password</label>
        <input type="password" placeholder="" />
        <input type="submit" value="Sign-Up" />
      </form>
      <p class="para-tag">
      Already have an account? <a href="admin_login.php">Login here</a>
    </p>
    </div>
    
  </body>
</html></span>