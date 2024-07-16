<?php
    import("conn.php");
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $u_name=$_POST['name'];
        $u_email=$_POST['email'];
        $u_mobile=$_POST['mobile'];
        $u_dob=$_POST['date_of_birth'];
        $u_pass=$_POST['u_pass'];
        $search_query="select * from 'users' where 'user_email'='$u_email'";
        $search_users=mysqli_query($con,$search_query);
        if(mysqli_num_rows($search_users)>0){
            
        }
        $insert_query="insert into users values('$uname','$u_mobile','$u_email','$u_dob','$u_pass')";
        $insert_data=mysqli_query($con,$insert_query);
        if($insert_data){
            location("quiz.php");
        }else{
            location("index.php");
        }
    }
    
?>