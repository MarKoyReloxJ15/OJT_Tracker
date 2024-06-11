<?php
// Retrieve form data
$date = $_POST['date'];
$start_time = $_POST['start_time'];
$end_time = $_POST['end_time'];
$ampm = $_POST['ampm'];
$notes = $_POST['notes'];
// echo"$start_time";

// // Concatenate start time with AM/PM for proper format
// $start_time = $_POST['start_time'] . ' ' . $_POST['ampm'];
// echo"$start_time";
// // Concatenate end time with AM/PM for proper format
// $end_time = $_POST['end_time'] . ' ' . $_POST['ampm'];

// Calculate the difference between start and end time in minutes
$start_timestamp = strtotime($date . ' ' . $start_time);
$end_timestamp = strtotime($date . ' ' . $end_time);
$minutes_difference = ($end_timestamp - $start_timestamp) / 60;

// Calculate the difference between start and end time in hours
//$hours_difference = (double)round($minutes_difference / 60, 2);

// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ojttrackerdb";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the record already exists
$sql_check = "SELECT * FROM main_db WHERE Date='$date' AND Am_Pm='$ampm'";
$result_check = $conn->query($sql_check);

if ($result_check->num_rows > 0) {
    echo "Record already exists for the selected date and AM/PM combination. Please choose a different date or AM/PM.";
    echo "<script>alert('Record already exists for the selected date and AM/PM combination. Please choose a different date or AM/PM.'); window.location='addForm.php';</script>";
} else {
    // Prepare SQL statement for insertion
    $sql_insert = "INSERT INTO main_db (Date, start_time, end_time, Am_Pm, Notes, minute_total)
                   VALUES ('$date', '$start_time', '$end_time', '$ampm', '$notes', '$minutes_difference')";

    // Execute insertion query
    if ($conn->query($sql_insert) === TRUE) {
        echo "<script>alert('Record inserted successfully!'); window.location='index.php';</script>";
    } else {
        echo "Error: " . $sql_insert . "<br>" . $conn->error;
    }
}

// Close connection
$conn->close();
?>
