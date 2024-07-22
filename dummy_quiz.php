<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "quiz_app";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$set_id = isset($_POST['ques_set']) ? intval($_POST['ques_set']) : 0;

if ($set_id > 0) {
    $stmt = $conn->prepare("SELECT q.id AS question_id, q.question, q.question_image, a.id AS answer_id, a.answer
                            FROM questions q
                            JOIN answers a ON q.id = a.question_id
                            WHERE q.set_id = ?
                            ORDER BY q.id, a.id");
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
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

<!DOCTYPE html>
<html>
<head>
    <title>Quiz</title>
</head>
<body>
    <h2>Quiz</h2>
    <form action="submit_quiz.php" method="post">
        <input type="hidden" name="set_id" value="<?php echo $set_id; ?>">
        <?php if (!empty($questions)): ?>
            <?php foreach ($questions as $question_id => $question): ?>
                <div>
                    <p><?php echo htmlspecialchars($question['question']); ?></p>
                    <?php if (!empty($question['question_image'])): ?>
                        <img src="<?php echo htmlspecialchars($question['question_image']); ?>" alt="Question Image"><br>
                    <?php endif; ?>
                    <?php foreach ($question['answers'] as $answer): ?>
                        <label>
                            <input type="radio" name="answers[<?php echo $question_id; ?>]" value="<?php echo $answer['answer_id']; ?>" required>
                            <?php echo htmlspecialchars($answer['answer']); ?>
                        </label><br>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
            <input type="submit" value="Submit">
        <?php else: ?>
            <p>No questions found for this set.</p>
        <?php endif; ?>
    </form>
</body>
</html>
