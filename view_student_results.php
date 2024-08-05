<?php 
session_start();
include "conn.php";

$set_name_query = "SELECT set_name FROM question_sets";
$set_name_result = $con->query($set_name_query);

$quiz_name_query = "SELECT quiz_topic FROM quiz_topics";
$quiz_name_result = $con->query($quiz_name_query);
?>
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
        .filter-container {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            justify-content: center;
            margin-bottom: 20px;
        }
        .filter-container .form-group {
            flex: 1;
            min-width: 200px;
        }
        .filter-container .form-group label {
            display: none;
        }
        .form-group input, .form-group select {
            width: 100%;
        }
        .form-group .form-control {
            border-radius: 0.25rem;
            box-shadow: none;
        }
        .form-inline {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            justify-content: center;
            align-items: center;
        }
        .btn-custom {
            background-color: #007bff;
            color: #fff;
            border-color: #007bff;
        }
        .btn-custom:hover {
            background-color: #0056b3;
            border-color: #004085;
        }
        .form-control::placeholder {
            color: #6c757d;
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
                        student_name_search: $('#student_name_search').val(),
                        set_name_search: $('#set_name_search').val(),
                        quiz_name_search: $('#quiz_name_search').val(),
                        result_search: $('#result_search').val(),
                        submitted_at_search: $('#submitted_at_search').val(),
                        from_date: $('#from_date').val(),
                        to_date: $('#to_date').val()
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
                                    '<td>' + result.batch_code + '</td>' +
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
                            tableBody.append('<tr><td colspan="10" class="text-center">No results found</td></tr>');
                        }
                    }
                });
            }

            // Function to populate dropdowns
            function populateDropdowns() {
                // Populate the student dropdown with random 5 students (this should be fetched from the server)
                $.ajax({
                    type: "GET",
                    url: "search_results.php",
                    dataType: "json",
                    success: function(response) {
                        var studentDropdown = $('#student_name_search');
                        studentDropdown.empty().append('<option value="">Select Student</option>');
                        $.each(response, function(index, student) {
                            studentDropdown.append('<option value="' + student.user_name + '">' + student.user_name + '</option>');
                        });
                    }
                });

                // Populate other dropdowns similarly with server-side data
                // Example for set_name_search, quiz_name_search, etc.
            }

            // Trigger fetchResults on input change
            $('#ic_number_search, #student_name_search, #set_name_search, #quiz_name_search, #result_search, #submitted_at_search, #from_date, #to_date').on('input change', fetchResults);

            // Initial fetch
            fetchResults();

            // Populate dropdowns on page load
            populateDropdowns();

            // Handle "Submitted At" custom range
            $(document).ready(function() {
    // Function to handle "Submitted At" selection
    $('#submitted_at_search').change(function() {
        var value = $(this).val();
        if (value === 'custom') {
            $('#date-range').show();
        } else {
            $('#date-range').hide();
            // Set default dates based on selection
            var currentDate = new Date();
            if (value === 'year') {
                var pastDate = new Date();
                pastDate.setFullYear(currentDate.getFullYear() - 1);
                $('#from_date').val(pastDate.toISOString().split('T')[0]);
                $('#to_date').val(currentDate.toISOString().split('T')[0]);
            } else if (value === 'month') {
                var pastDate = new Date();
                pastDate.setMonth(currentDate.getMonth() - 1);
                $('#from_date').val(pastDate.toISOString().split('T')[0]);
                $('#to_date').val(currentDate.toISOString().split('T')[0]);
            } else if (value === 'week') {
                var pastDate = new Date();
                pastDate.setDate(currentDate.getDate() - 7);
                $('#from_date').val(pastDate.toISOString().split('T')[0]);
                $('#to_date').val(currentDate.toISOString().split('T')[0]);
            } else {
                // Clear dates if no specific range is selected
                $('#from_date').val('');
                $('#to_date').val('');
            }
        }
        fetchResults(); // Ensure results are updated based on the new range
    });

    // Initial setup to hide date range if not custom
    $('#date-range').hide();
});
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
                    <button id="generate_excel" class="btn btn-custom">Generate Excel File</button>
                    <!-- Search Form -->
                    <form method="POST" class="form-inline">
                        <div class="filter-container">
                            <div class="form-group">
                                <input type="text" class="form-control" id="ic_number_search" name="ic_number_search" placeholder="IC Number">
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control" id="student_name_search" name="student_name_search" placeholder="Student Name">
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control" id="batch_code_search" name="batch_code_search" placeholder="Batch Code">
                            </div>
                            <div class="form-group">
                                <input type="text" id="set_name_search_input" class="form-control" placeholder="Search Set Name" autocomplete="off">
                                <select class="form-control" id="set_name_search" name="set_name_search" size="5" style="display: none;">
                                    <option value="">Select Set</option>
                                    <?php
                                    if ($set_name_result->num_rows > 0) {
                                        while ($row = $set_name_result->fetch_assoc()) {
                                            $set_name = $row['set_name'];
                                            echo "<option value=\"$set_name\">$set_name</option>";
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                            <input type="text" id="quiz_name_search_input" class="form-control" placeholder="Search Quiz Name" autocomplete="off">
                            <select class="form-control" id="quiz_name_search" name="quiz_name_search" size="5" style="display: none;">
                                <option value="">Select Quiz</option>
                                <?php
                                if ($quiz_name_result->num_rows > 0) {
                                    while ($row = $quiz_name_result->fetch_assoc()) {
                                        $quiz_name = $row['quiz_topic'];
                                        echo "<option value=\"$quiz_name\">$quiz_name</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>
                            <div class="form-group">
                                <select class="form-control" id="result_search" name="result_search">
                                    <option value="">Select Result</option>
                                    <option value="1">Pass</option>
                                    <option value="0">Fail</option>
                                </select>
                            </div>
                            <div class="form-group">
                            <select class="form-control" id="submitted_at_search" name="submitted_at_search">
                                <option value="">Submitted At</option>
                                <option value="year">Since a year ago</option>
                                <option value="month">Since a month ago</option>
                                <option value="week">Since a week ago</option>
                                <option value="custom">Custom Range</option>
                            </select>
                        </div>
                        <div class="form-group date-range-container" style="display: none;" id="date-range">
                            <div class="form-group">
                            <label for="from_date">
                                From date
                            </label>
                                <input type="date" class="form-control" id="from_date" name="from_date" placeholder="From Date">
                            </div>
                            <label for="to_date">
                                To date
                            </label>
                            <div class="form-group">
                                <input type="date" class="form-control" id="to_date" name="to_date" placeholder="To Date">
                            </div>
                        </div>
                            
                        </div>
                    </form>
                    
                    <div class="table-responsive">
                        <table class="table table-bordered" id="results_table">
                            <thead>
                                <tr>
                                    <th>IC Number</th>
                                    <th>Student Name</th>
                                    <th>Batch Code</th>
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

    <script>
    document.getElementById('set_name_search_input').addEventListener('input', function() {
        var input = this.value.toLowerCase();
        var select = document.getElementById('set_name_search');
        var options = select.getElementsByTagName('option');
        var optionFound = false;

        for (var i = 1; i < options.length; i++) { // Skip the first option (placeholder)
            var txtValue = options[i].textContent || options[i].innerText;
            if (txtValue.toLowerCase().indexOf(input) > -1) {
                options[i].style.display = "";
                optionFound = true;
            } else {
                options[i].style.display = "none";
            }
        }

        select.style.display = optionFound ? "block" : "none";
    });

    document.getElementById('set_name_search_input').addEventListener('focus', function() {
        var select = document.getElementById('set_name_search');
        select.style.display = "block";
    });

    document.getElementById('set_name_search').addEventListener('change', function() {
        var input = document.getElementById('set_name_search_input');
        var selectedOption = this.options[this.selectedIndex];
        input.value = selectedOption.text;
        this.style.display = "none";
    });

    document.addEventListener('click', function(event) {
        var input = document.getElementById('set_name_search_input');
        var select = document.getElementById('set_name_search');
        if (!input.contains(event.target) && !select.contains(event.target)) {
            select.style.display = "none";
        }
    });

    //search for quiz name in drop down 
    document.getElementById('quiz_name_search_input').addEventListener('input', function() {
    var input = this.value.toLowerCase();
    var select = document.getElementById('quiz_name_search');
    var options = select.getElementsByTagName('option');
    var optionFound = false;

    for (var i = 1; i < options.length; i++) { // Skip the first option (placeholder)
        var txtValue = options[i].textContent || options[i].innerText;
        if (txtValue.toLowerCase().indexOf(input) > -1) {
            options[i].style.display = "";
            optionFound = true;
        } else {
            options[i].style.display = "none";
        }
    }

    select.style.display = optionFound ? "block" : "none";
});

document.getElementById('quiz_name_search_input').addEventListener('focus', function() {
    var select = document.getElementById('quiz_name_search');
    select.style.display = "block";
});

document.getElementById('quiz_name_search').addEventListener('change', function() {
    var input = document.getElementById('quiz_name_search_input');
    var selectedOption = this.options[this.selectedIndex];
    input.value = selectedOption.text;
    this.style.display = "none";
});

document.addEventListener('click', function(event) {
    var input = document.getElementById('quiz_name_search_input');
    var select = document.getElementById('quiz_name_search');
    if (!input.contains(event.target) && !select.contains(event.target)) {
        select.style.display = "none";
    }
});
</script>
</body>
</html>
