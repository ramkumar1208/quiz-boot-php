<?php 
error_reporting(E_ALL);
include "conn.php";
session_start();

if (isset($_POST['answer-submit'])) {

    $u_email = $_SESSION['user'];
    $already_attend="select * from `score` where `email`='$u_email'";
    $already_attend_data=mysqli_query($con,$already_attend);
    if(mysqli_num_rows($already_attend_data)>0){
        echo "hello";
        $_SESSION['message']="$u_email already attend the quiz";
        header("Location: index.php");
        exit();
    }
    // Checking if our Questions are even attempted
    if (!empty($_POST['checkanswer'])) {
        
        // Set a flag for correct answers
        $correctAnswers = 0;
        $selected = $_POST['checkanswer'];
        
        $sql = "SELECT * FROM questions";
        $result = mysqli_query($con, $sql);
    
        $i = 1; // Start from 0 instead of 1
        while ($row = mysqli_fetch_assoc($result)) {
            
            // Matching Database Answerid with User selected answer id
            // If ans_id are matched our flag value is updated
            if ($row['ans_id'] == $selected[$i]) {
                $fetch_ans = "SELECT answer FROM `answers` WHERE `aid`='{$selected[$i]}'";
                $fetch_data = mysqli_query($con, $fetch_ans);
                $row_ans = mysqli_fetch_assoc($fetch_data);
                $answer = $row_ans['answer']; 
                $insert_q = "INSERT INTO `history`(`email`,`qid`,`answer`,`mark`) VALUES ('$u_email', '{$row['qid']}', '$answer', '1')";
                $insert_data = mysqli_query($con, $insert_q);
                if($insert_data){
                }
                else{
                    die("Error: " . mysqli_error($con));
                }
                $correctAnswers++;
            } else {
                $fetch_ans = "SELECT answer FROM `answers` WHERE `aid`='{$selected[$i]}'";
                $fetch_data = mysqli_query($con, $fetch_ans);
                $row_ans = mysqli_fetch_assoc($fetch_data);
                $answer = $row_ans['answer'];
                $insert_q = "INSERT INTO `history`(`email`,`qid`,`answer`,`mark`) VALUES ('$u_email', '{$row['qid']}', '$answer', '0')";
                $insert_data = mysqli_query($con, $insert_q);
                if($insert_data){
                }
                else{
                    die("Error: " . mysqli_error($con));
                }
            }
            $i++;
        }
    
        // Stored our score and attempted question value in session to be used on Result page
        $_SESSION['attempted'] = count($_POST['checkanswer']);
        $_SESSION['score'] = $correctAnswers;
        if($correctAnswers >= 7 ){
            $insert_datas = mysqli_query($con, "INSERT INTO `score`(`email`,`total`,`grade`) VALUES('$u_email','$correctAnswers','pass')");
        } else {
            $insert_datas = mysqli_query($con, "INSERT INTO `score`(`email`,`total`,`grade`) VALUES('$u_email','$correctAnswers','fail')");
        }

        if($insert_datas){
            if($correctAnswers >= 7 ){
                $_SESSION['message']="Quiz Submitted Successfully you are pass";    
            }else{
                $_SESSION['message']="Quiz Submitted Successfully but you are fail";
            }
            
            header("location: index.php");
        }else{
            die("Error: " . mysqli_error($con));
        }
    }
}

?>
