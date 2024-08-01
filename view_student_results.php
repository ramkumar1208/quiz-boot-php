<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Results</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <style>
        .container-fluid {
            background-color: #f8f9fa; /* Light grey background */
            height: 100vh;
        }
        .table-responsive {
            margin-top: 20px;
        }
    </style>
    <script>
        $(document).ready(function() {
            function fetchResults() {
                $.ajax({
                    type: "POST",
                    url: "search_results.php",
                    data: {
                        ic_number_search: $('#ic_number_search').val(),
                        batch_code_search: $('#batch_code_search').val(),
                        // Additional filters can be added here
                    },
                    dataType: "json",
                    success: function(response) {
                        var tableBody = $('#results_table tbody');
                        tableBody.empty();
                        if (response.length > 0) {
                            $.each(response, function(index, result) {
                                var row = '<tr>' +
                                    '<td>' + result.ic_number + '</td>' +
                                    '<td>' + result.user_name + '</td>' +
                                    '<td>' + result.set_name + '</td>' +
                                    '<td>' + result.quiz_topic + '</td>' +
                                    '<td>' + (result.result == 1 ? "Pass" : "Fail") + '</td>' +
                                    '<td>' + result.total_questions + '</td>' +
                                    '<td>' + result.correct_answers + '</td>' +
                                    '<td>' + result.created_at + '</td>' +
                                    '<td>' +
                                        '<form action="complete_result.php" method="GET">' +
                                            '<input type="hidden" name="result_id" value="' + result.result_id + '"/>' +
                                            '<input type="submit" value="View" class="btn btn-primary"/>' +
                                        '</form>' +
                                    '</td>' +
                                    '</tr>';
                                tableBody.append(row);
                            });
                        } else {
                            tableBody.append('<tr><td colspan="9" class="text-center">No results found</td></tr>');
                        }
                    }
                });
            }

            // Trigger fetchResults on input change
            $('#ic_number_search, #batch_code_search').on('input', fetchResults);

            // Initial fetch
            fetchResults();

            // Generate Excel file on button click
            $('#generate_excel').click(function() {
                var tableData = [];
                var headers = [];
                $('#results_table thead th').each(function() {
                    headers.push($(this).text());
                });
                tableData.push(headers.join(","));
                
                $('#results_table tbody tr').each(function() {
                    var rowData = [];
                    $(this).find('td').each(function() {
                        rowData.push($(this).text());
                    });
                    tableData.push(rowData.join(","));
                });

                $.ajax({
                    type: "POST",
                    url: "generate_csv.php",
                    data: {
                        table_data: tableData.join("\n")
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response.status == 'success') {
                            window.location.href = response.file;
                        } else {
                            alert("Error generating CSV file");
                        }
                    }
                });
            });
        });
    </script>
</head>
<body>
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
                        <a class="nav-link" href="index.php">Home<span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="admin_quiz.php">View Quiz</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="viewmarks.php">Student Marks</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="student_management.php">Student Management</a>
                    </li>
                </ul>
                <div class="bs-example">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12 bg-light text-right">
                                <?php 
                                if (isset($_SESSION['admin'])) { 
                                    $user_email = $_SESSION['admin'];
                                    echo $user_email;  
                                    ?>
                                    <a href="logout.php"><button type="button" class="btn btn-primary">Log-out</button></a>
                                <?php } else { ?>        
                                    <a href="admin_login.php"><button type="button" class="btn btn-primary">Login</button></a>
                                <?php } ?>    
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="text-center my-4">Student Results</h2>

                    <!-- Search Form -->
                    <form method="POST" class="form-inline justify-content-center">
                        <div class="form-group mx-sm-3 mb-2">
                            <label for="ic_number_search" class="sr-only">IC Number</label>
                            <input type="text" class="form-control" id="ic_number_search" name="ic_number_search" placeholder="IC Number">
                        </div>
                        <div class="form-group mx-sm-3 mb-2">
                            <label for="batch_code_search" class="sr-only">Batch Code</label>
                            <input type="text" class="form-control" id="batch_code_search" name="batch_code_search" placeholder="Batch Code">
                        </div>
                        <button id="generate_excel" class="btn btn-success mb-2">Generate Excel File</button>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-bordered" id="results_table">
                            <thead>
                                <tr>
                                    <th>IC Number</th>
                                    <th>Student Name</th>
                                    <th>Set Name</th>
                                    <th>Quiz Name</th>
                                    <th>Result</th>
                                    <th>Total Questions</th>
                                    <th>Correct Answers</th>
                                    <th>Submitted At</th>
                                    <th>View Complete Result</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Results will be inserted here dynamically -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>   
</body>
</html>
