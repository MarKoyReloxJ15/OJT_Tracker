<?php
require_once("config/config.php");

// Check if the form is submitted for update
if (isset($_POST["Submit"])) { // corrected to match the button name
    // Establish a database connection
    $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
    // Check the connection
    if ($conn->connect_error) {
        die("Couldn't connect to the database: " . $conn->connect_error);
    }

    // Retrieve form data
    $id = $_POST['id'];
    $date = $_POST['date'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    $ampm = $_POST['ampm'];
    $notes = $_POST['notes'];

    // Calculate difference between start and end time in minutes
    $start_timestamp = strtotime($date . ' ' . $start_time);
    $end_timestamp = strtotime($date . ' ' . $end_time);
    $minutes_difference = ($end_timestamp - $start_timestamp) / 60;

    // Calculate difference between start and end time in hours
//    $hours_difference = (double)round($minutes_difference / 60, 2);

    // Prepare and execute the SQL query
    $query = "UPDATE main_db SET Date = ?, start_time = ?, end_time = ?, Am_Pm = ?, Notes = ?, minute_total = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    // Check if the statement was prepared successfully
    if ($stmt === false) {
        echo "Error preparing statement: " . $conn->error;
    } else {
        // Bind parameters
        $stmt->bind_param("sssssii", $date, $start_time, $end_time, $ampm, $notes, $minutes_difference, $id); // Corrected bind parameters
        // Execute the statement
        if ($stmt->execute()) {
            // Display success message and redirect
            echo "<script>alert('Record updated successfully!'); window.location='allList.php';</script>";
        } else {
            // Display error message
            echo "Error updating event: " . $stmt->error;
        }
        // Close the statement
        $stmt->close();
    }
    // Close the connection
    $conn->close();
}
?>
