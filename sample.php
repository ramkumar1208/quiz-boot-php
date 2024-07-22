<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Quiz Page</title>
    <script>
        let switchCount = 0;
        const maxSwitchCount = 3;
        let hasBlurred = false;
        if (!document.hasFocus()) 
        {
            alert("Warning!");
        }
        function handleSwitch() {
            if (!hasBlurred) {
                hasBlurred = true;
                switchCount++;
                if (switchCount <= maxSwitchCount) {
                    alert('Warning: You have switched tabs or applications ' + switchCount + ' time(s). After 3 switches, your quiz will be submitted.');
                }
                if (switchCount >= maxSwitchCount) {
                    document.getElementById('quizForm').submit();
                }
            }
        }

        function resetBlur() {
            hasBlurred = false;
            console.log("switch count is "+switchCount);
        }

        function checkVisibility() {
            if (document.hidden) {
                console.log("page blured");
                handleSwitch();
            } else {
                resetBlur();
            }
        }

        document.addEventListener('visibilitychange', checkVisibility);
        window.addEventListener('blur', handleSwitch);
        window.addEventListener('focus', resetBlur);
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
