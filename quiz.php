<?php
include "conn.php";
error_reporting(E_ALL);
session_start();

if (!isset($_SESSION['user'])) {
    $_SESSION['message'] = "Please login first";
    header("Location: index.php");
    exit();
}
$user = $_SESSION['user'];
$session_db = mysqli_query($con, "SELECT * FROM login_sessions WHERE ic_number='$user'");
$row = mysqli_fetch_array($session_db);
$session_from_db = $row['session_id'];
$session_id = session_id();
if ($session_id != $session_from_db) {
    $_SESSION['message'] = "You are logged out from another device. Please login first";
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Quiz App</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <style>
        .center-div {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .bs-example {
            margin: 5px;
        }
        .container-bg {
                    background-image: url("bg.jpg");
                    background-size: cover;
                    background-position: center;
                    min-height: 100vh;
                    display: flex;
                    flex-direction: column;
                }
        .iframe-container {
            position: relative;
            overflow: hidden;
            width: 100%;
            height: 100vh;
            padding-top: 56.25%;
        }
        .iframe-container iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border: 0;
        }
        #response {
            position: fixed;
            top: 13%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: red;
            z-index: 1000;
            font-size: 24px;
            text-align: center;
        }
        .question-container {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .question-image {
            max-width: 100%;
            height: auto;
        }
    </style>
</head>
<body>
<div class="container-bg">
    <div class="container-fluid">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
                <a class="navbar-brand" href="index.php">
                    <img src="logo.png" alt="" width="50px">
                </a>
                <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
                    <li class="nav-item active">
                        <a class="nav-link" href="index.php">Home <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="quiz.php">Quiz</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Contact</a>
                    </li>
                </ul>
                <div class="bs-example">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12 bg-light text-right">
                                <?php
                                if ($_SESSION['user']) {
                                    $user_email = $_SESSION['user'];
                                    echo $user_email;
                                    ?>
                                    <a href="logout.php"><button type="button" class="btn btn-primary">Log-out</button></a>
                                <?php } else { ?>
                                    <a href="login1.php"><button type="button" class="btn btn-primary">Login</button></a>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $quiz_id = $_POST['quiz_id'];
            $batch_code=$_POST['batch_code'];
            if (isset($_POST['ques_set']) && !empty($_POST['ques_set'])) {
                $quiz_id = $_POST['quiz_id'];

                date_default_timezone_set('Asia/Singapore');
                include "conn.php";
                $res = mysqli_query($con, "SELECT * FROM quiz_topics WHERE quiz_id='$quiz_id'");
                while ($row = mysqli_fetch_array($res)) {
                    $duration = $row['total_time'];
                    $_SESSION['start_time'] = $row['quiz_time'];
                }
                $_SESSION['duration'] = $duration;

                list($hours, $minutes, $seconds) = explode(':', $_SESSION['duration']);
                $end_time = date('Y-m-d H:i:s', strtotime("+{$hours} hours +{$minutes} minutes +{$seconds} seconds", strtotime($_SESSION["start_time"])));
                $_SESSION['end_time'] = $end_time;

                ?>
                
                <?php
            } else {
                $_SESSION['message'] = "Quiz is not Here.";
                header("Location: quiz.php");
            }    
            
$set_id = isset($_POST['ques_set']) ? intval($_POST['ques_set']) : 0;

if ($set_id > 0) {
    $stmt = $con->prepare("SELECT q.id AS question_id, q.question, q.question_image, a.id AS answer_id, a.answer
                            FROM questions q
                            JOIN answers a ON q.id = a.question_id
                            WHERE q.set_id = ?
                            ORDER BY q.id, a.id");
    if ($stmt === false) {
        die("Prepare failed: " . $con->error);
    }
    $stmt->bind_param("i", $set_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $questions = [];
    while ($row = $result->fetch_assoc()) {
        $questions[$row['question_id']]['question'] = $row['question'];
        $questions[$row['question_id']]['question_image'] = $row['question_image'];
        $questions[$row['question_id']]['answers'][] = [
            'answer_id' => $row['answer_id'],
            'answer' => $row['answer']
        ];
    }
    $stmt->close();
}
            ?>
            <div id="response"></div>
            <div class="question-container mt-4">
                <form id="quizForm" action="submit_quiz.php" method="post">
                <input type="hidden" name="batch_code" value="<?php echo $batch_code; ?>">
                    <?php if (!empty($questions)): ?>
                        <?php foreach ($questions as $question_id => $question): ?>
                            <div class="mb-4">
                                <p><?php echo htmlspecialchars($question['question']); ?></p>
                                <?php if (!empty($question['question_image'])): ?>
                                    <img class="question-image mb-3" src="<?php echo htmlspecialchars($question['question_image']); ?>" alt="Question Image"><br>
                                <?php endif; ?>
                                <?php foreach ($question['answers'] as $answer): ?>
                                    <label>
                                        <input type="radio" name="answers[<?php echo $question_id; ?>]" value="<?php echo $answer['answer_id']; ?>" required>
                                        <?php echo htmlspecialchars($answer['answer']); ?>
                                    </label><br>
                                <?php endforeach; ?>
                            </div>
                        <?php endforeach; ?>
                        <input type="hidden" name="ic_number" value="<?php echo $user; ?>">
                        <input type="hidden" name="set_id" value="<?php echo $set_id; ?>">
                        <input type="hidden" name="quiz_id" value="<?php echo $quiz_id; ?>">        
                        <input class="btn btn-primary" type="submit" value="Submit">
                    <?php else: ?>
                        <p>No questions found for this set.</p>
                    <?php endif; ?>
                </form>
            </div>
            <?php
        }
        ?>

        <?php
        if (isset($_SESSION['message']) && !empty($_SESSION['message'])) {
            $message = $_SESSION['message'];
            $_SESSION['message'] = "";
            ?>
            <div class="center-div">
                <div class="alert alert-danger alert-dismissible">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <?php echo $message; ?>
                    <?php if ($message === "You are already logged in from another device.") { ?>
                        <a href="logout.php"><button>Logout That Device</button></a>
                    <?php } ?>
                </div>
            </div>
        <?php } ?>
    </div>
</div>

<script>
var timer = setInterval(function(){
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.open('GET', "response.php", false);
    xmlhttp.send(null);
    var responseText = xmlhttp.responseText;
    document.getElementById("response").innerHTML = responseText;

    var remainingTimeText = document.getElementById("response").innerText || document.getElementById("response").textContent;
    console.log(remainingTimeText);

    if (remainingTimeText === "00:00:00") {
        clearInterval(timer);
        document.getElementById("quizForm").submit();
    }
}, 1000);
</script>
</body>
</html>
