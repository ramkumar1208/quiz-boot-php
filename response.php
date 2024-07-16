<?php 
     date_default_timezone_set('Asia/Singapore');
    session_start();
    $from_time=date('Y-m-d H:i:s');
    $to_time=$_SESSION['end_time'];
    $time_first=strtotime($from_time);
    // echo $time_first."<br>";
    $time_second=strtotime($to_time);
    // echo $time_second."<br>";
    $different_in_sec=$time_second-$time_first;
    echo gmdate("H:i:s",$different_in_sec);
?>