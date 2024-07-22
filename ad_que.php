<!DOCTYPE html>
<html>
<head>
    <title>Add Questions</title>
</head>
<body>
    <h2>Add Questions to Set</h2>
    <form action="add_questions.php" method="post" enctype="multipart/form-data">
        <label for="batch_code">Batch code</label>
        <input type="text" id="batch_code" name="batch_code" required><br><br>
        
        <label for="set_name">Set Name:</label>
        <input type="text" id="set_name" name="set_name" required><br><br>
        
        <div id="questions">
            <div class="question">
                <label for="question">Question:</label>
                <input type="text" name="questions[]" required><br>
                <label for="question_image">Upload Question Image (optional):</label>
                <input type="file" name="question_images[]" accept="image/*"><br><br>
                
                <label for="answers">Answers:</label><br>
                <div class="answers">
                    <input type="text" name="answers[0][]" required>
                    <input type="radio" name="correct_answer[0]" value="0" required> Correct<br>
                </div>
                <button type="button" onclick="addAnswer(this)">Add Another Answer</button>
                <button type="button" onclick="removeQuestion(this)">Remove Question</button><br><br>
            </div>
        </div>
        
        <button type="button" onclick="addQuestion()">Add Another Question</button><br><br>
        <input type="submit" value="Submit">
    </form>
    
    <script>
        let questionIndex = 1;

        function addQuestion() {
            let questionsDiv = document.getElementById('questions');
            let newQuestionDiv = document.createElement('div');
            newQuestionDiv.classList.add('question');
            newQuestionDiv.innerHTML = `
                <label for="question">Question:</label>
                <input type="text" name="questions[]" required><br>
                <label for="question_image">Upload Question Image (optional):</label>
                <input type="file" name="question_images[]" accept="image/*"><br><br>
                
                <label for="answers">Answers:</label><br>
                <div class="answers">
                    <input type="text" name="answers[${questionIndex}][]" required>
                    <input type="radio" name="correct_answer[${questionIndex}]" value="0" required> Correct<br>
                </div>
                <button type="button" onclick="addAnswer(this)">Add Another Answer</button>
                <button type="button" onclick="removeQuestion(this)">Remove Question</button><br><br>
            `;
            questionsDiv.appendChild(newQuestionDiv);
            questionIndex++;
        }

        function addAnswer(button) {
            let answersDiv = button.previousElementSibling;
            let answerIndex = answersDiv.querySelectorAll('input[type="text"]').length;
            let questionIndex = Array.from(document.querySelectorAll('.question')).indexOf(button.parentElement);

            let newAnswerDiv = document.createElement('div');
            newAnswerDiv.innerHTML = `
                <input type="text" name="answers[${questionIndex}][]" required>
                <input type="radio" name="correct_answer[${questionIndex}]" value="${answerIndex}" required> Correct<br>
            `;
            answersDiv.appendChild(newAnswerDiv);
        }

        function removeQuestion(button) {
            let questionDiv = button.parentElement;
            questionDiv.remove();
        }
    </script>
</body>
</html>
