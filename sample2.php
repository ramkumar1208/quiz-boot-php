
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Quiz Page</title>
    <script>
        let switchCount = 0;
        const maxSwitchCount = 3;

        document.addEventListener('visibilitychange', function() {
            if (document.hidden) {
                switchCount++;
                if (switchCount <= maxSwitchCount) {
                    alert('Warning: You have switched tabs ' + switchCount + ' time(s). After 3 switches, your quiz will be submitted.');
                }
                if (switchCount >= maxSwitchCount) {
                    document.getElementById('quizForm').submit();
                }
            }
        });
    </script>
</head>
<body>
    <h1>Quiz</h1>
    <form id="quizForm" action="submit_quiz.php" method="post">
        <!-- Quiz questions go here -->
        <input type="text" name="question1" placeholder="Answer 1"><br>
        <input type="text" name="question2" placeholder="Answer 2"><br>
        <input type="submit" value="Submit Quiz">
    </form>
</body>
</html>
