<?php
    include "conn.php";
     session_start();
     $u_ic=$_SESSION['user'];

     $search_query="select * from `users` where `ic_number`='$u_ic'";
     $search_query_exe=mysqli_query($con,$search_query);   
     if($search_query_exe){
        $row=mysqli_fetch_assoc($search_query_exe);
        $ic_number=$row['ic_number'];
        $batch_code=$row['batch_code'];
     }
     $sql_delete = "DELETE FROM login_sessions WHERE ic_number = '$ic_number' AND batch_code = '$batch_code'";
     if ($con->query($sql_delete) === TRUE) {
    //     echo "Logout successful";
     }
     $u_ic=$_SESSION['logout_user'];
     $search_query="select * from `users` where `ic_number`='$u_ic'";
     $search_query_exe=mysqli_query($con,$search_query);   
     if($search_query_exe){
        $row=mysqli_fetch_assoc($search_query_exe);
        $ic_number=$row['ic_number'];
        $batch_code=$row['batch_code'];
     }
     $sql_delete = "DELETE FROM login_sessions WHERE ic_number = '$ic_number' AND batch_code = '$batch_code'";
     if ($con->query($sql_delete) === TRUE) {
    //     echo "Logout successful";
     }
    // Clear all session variables
    // $_SESSION = [];
    $_SESSION[]=null;
    session_unset();
    session_destroy();
    header("Location: index.php");
?>