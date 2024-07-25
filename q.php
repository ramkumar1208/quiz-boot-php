<?php
session_start();
include "conn.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_quiz_by_id'])) {
    $questions = isset($_POST['questions']) ? $_POST['questions'] : [];
    $answers = isset($_POST['answers']) ? $_POST['answers'] : [];
    $correct_answers = isset($_POST['correct_answer']) ? $_POST['correct_answer'] : [];
    $q_id = mysqli_real_escape_string($con, $_POST['set_id']);
    $set_name = mysqli_real_escape_string($con, $_POST['set_name']);
    $uploaded_images = [];
    $new_questions_count = 0;
    $removed_questions = isset($_POST['removed_questions']) ? array_filter(explode(',', $_POST['removed_questions'])) : [];

    // Handle file uploads
    $target_dir = "uploads/";
    foreach ($_FILES['question_images']['name'] as $index => $name) {
        if (!empty($name)) {
            $target_file = $target_dir . basename($name);
            if (move_uploaded_file($_FILES['question_images']['tmp_name'][$index], $target_file)) {
                $uploaded_images[$index] = mysqli_real_escape_string($con, $target_file);
            } else {
                echo "Error uploading file: " . $_FILES['question_images']['error'][$index];
                exit();
            }
        } else {
            $uploaded_images[$index] = null; // No image uploaded
        }
    }

    // Remove deleted questions and their answers
    foreach ($removed_questions as $question_id) {
        if (!empty($question_id)) {
            $question_id = mysqli_real_escape_string($con, $question_id);
            $delete_answers_query = "DELETE FROM answers WHERE question_id='$question_id'";
            if (!mysqli_query($con, $delete_answers_query)) {
                echo "Error deleting answers: " . mysqli_error($con);
                exit();
            }

            $delete_question_query = "DELETE FROM questions WHERE id='$question_id'";
            if (!mysqli_query($con, $delete_question_query)) {
                echo "Error deleting question: " . mysqli_error($con);
                exit();
            }
        }
    }

    foreach ($questions as $q_index => $question) {
        $question_id = isset($_POST['question_ids'][$q_index]) ? mysqli_real_escape_string($con, $_POST['question_ids'][$q_index]) : null;
        $question_image = isset($uploaded_images[$q_index]) ? $uploaded_images[$q_index] : null;
        $question = mysqli_real_escape_string($con, $question);

        if ($question_id) {
            // Fetch existing image path if no new image is uploaded
            if ($question_image === null) {
                $query = "SELECT question_image FROM questions WHERE id='$question_id'";
                $result = mysqli_query($con, $query);
                $row = mysqli_fetch_assoc($result);
                $question_image = $row['question_image'];
            }
            // Update existing question
            $update_question_query = "UPDATE questions SET question='$question', question_image='$question_image' WHERE id='$question_id'";
            if (!mysqli_query($con, $update_question_query)) {
                echo "Error updating question: " . mysqli_error($con);
                exit();
            }
        } else {
            // Add new question
            $insert_question_query = "INSERT INTO questions (set_id, question, question_image, set_name) VALUES ('$q_id', '$question', '$question_image', '$set_name')";
            if (!mysqli_query($con, $insert_question_query)) {
                echo "Error inserting question: " . mysqli_error($con);
                exit();
            }
            $question_id = mysqli_insert_id($con); // Get the inserted question ID
            $new_questions_count++;
        }

        if (isset($answers[$q_index]) && is_array($answers[$q_index])) {
            foreach ($answers[$q_index] as $a_index => $answer) {
                $answer_id = isset($_POST['answer_ids'][$q_index][$a_index]) ? mysqli_real_escape_string($con, $_POST['answer_ids'][$q_index][$a_index]) : null;
                $answer = mysqli_real_escape_string($con, $answer);
                $is_correct = (isset($correct_answers[$q_index]) && $a_index == $correct_answers[$q_index]) ? 1 : 0;

                if ($answer_id) {
                    // Update existing answer
                    $update_answer_query = "UPDATE answers SET answer='$answer', is_correct='$is_correct' WHERE id='$answer_id'";
                    if (!mysqli_query($con, $update_answer_query)) {
                        echo "Error updating answer: " . mysqli_error($con);
                        exit();
                    }
                } else {
                    // Add new answer
                    $insert_answer_query = "INSERT INTO answers (question_id, answer, is_correct) VALUES ('$question_id', '$answer', '$is_correct')";
                    if (!mysqli_query($con, $insert_answer_query)) {
                        echo "Error inserting answer: " . mysqli_error($con);
                        exit();
                    }
                }
            }
        }
    }

    // Update total questions and set name in question_sets
    $total_questions = mysqli_real_escape_string($con, $_POST['total_question']);
    $remaining_questions = $total_questions - count($removed_questions) + $new_questions_count; // Adjust the count based on removals and additions

    $update_question_sets_query = "UPDATE question_sets SET total_questions='$remaining_questions', set_name='$set_name' WHERE set_id='$q_id'";
    if (!mysqli_query($con, $update_question_sets_query)) {
        echo "Error updating question set: " . mysqli_error($con);
        exit();
    }

    // Optional: Redirect or show success message
    echo "Questions and answers updated successfully.";
}
?>
