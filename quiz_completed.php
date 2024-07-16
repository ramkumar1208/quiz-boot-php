<?php 
    include "conn.php";
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $user=$_POST['ic_num'];
        $quiz_id=$_POST['quiz_id'];
        $insert_query="insert into  quiz_status(`quiz_id`,`ic_number`) values ('$quiz_id','$user') ";
        $query_execute=mysqli_query($con,$insert_query);
        if($query_execute){
            header("Location: viewquiz.php");
        }
    }

?>