<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['table_data'])) {
    $table_data = $_POST['table_data'];

    // Set the filename
    $fileName = 'student_results_' . date('Ymd_His') . '.csv';

    // Save the CSV file to the server
    $filePath = 'generated_files/' . $fileName;
    file_put_contents($filePath, $table_data);

    // Return the file path in the response
    echo json_encode(['status' => 'success', 'file' => $filePath]);
    exit();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}
?>
