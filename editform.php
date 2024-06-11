<?php
  // Assuming you have established a database connection
  // Replace DB_HOST, DB_USERNAME, DB_PASSWORD, and DB_NAME with your actual database credentials
  $mysqli = new mysqli("localhost", "root", "", "ojttrackerdb");

  // Check connection
  if($mysqli === false){
    die("ERROR: Could not connect. " . $mysqli->connect_error);
  }

  // Check if ID parameter is set in URL
  if(isset($_GET['id'])) {
    // Retrieve the ID from the URL parameter
    $id = $_GET['id'];

    // Prepare a select statement
    $sql = "SELECT * FROM main_db WHERE id = ?";

    if($stmt = $mysqli->prepare($sql)){
      // Bind variables to the prepared statement as parameters
      $stmt->bind_param("i", $id);

      // Attempt to execute the prepared statement
      if($stmt->execute()){
        // Store result
        $result = $stmt->get_result();

        // Check if a record was found
        if($result->num_rows == 1){
          // Fetch result row as an associative array
          $row = $result->fetch_assoc();

          // Retrieve data from the fetched row
          $date = $row['Date'];
          $start_time = $row['start_time'];
          $end_time = $row['end_time'];
          $ampm = $row['Am_Pm'];
          $notes = $row['Notes'];
        } else{
          // No records found with the given ID
          echo "No records found with that ID.";
        }
      } else{
        echo "ERROR: Could not execute $sql. " . $mysqli->error;
      }
    }

    // Close statement
    $stmt->close();

    // Close connection
    $mysqli->close();
  } else {
    // ID parameter is not set
    echo "No ID specified.";
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Event Form</title>
<style>
  body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    margin: 0;
    padding: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
  }

  form {
    background-color: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
    width: 300px;
  }

  h2 {
    text-align: center;
    margin-bottom: 20px;
  }

  label {
    font-weight: bold;
  }

  input[type="date"],
  input[type="time"],
  select,
  textarea {
    width: calc(100% - 6px);
    padding: 8px;
    margin: 6px 0;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
  }

  input[type="submit"] {
    width: 100%;
    background-color: #4CAF50;
    color: white;
    padding: 10px 20px;
    margin-top: 10px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 16px;
  }

  input[type="submit"]:hover {
    background-color: #45a049;
  }
</style>
</head>
<body>
<form action="update.php" method="post">
  <a href="allList.php">Go Back</a><br><br> 
  <input type="hidden" name="id" value="<?php echo $id; ?>">
  <label for="date">Date:</label><br>
  <input type="date" id="date" name="date" value="<?php echo htmlspecialchars($date); ?>" required><br>

  <label for="start_time">Start Time:</label><br>
  <input type="time" id="start_time" name="start_time" value="<?php echo date('H:i:s', strtotime($start_time)); ?>" required><br>

  <label for="end_time">End Time:</label><br>
  <input type="time" id="end_time" name="end_time" value="<?php echo date('H:i:s', strtotime($end_time)); ?>" required><br>

  <label for="ampm">AM/PM:</label><br>
  <select id="ampm" name="ampm" required>
    <option value="AM" <?php if ($ampm == 'AM') echo 'selected'; ?>>AM</option>
    <option value="PM" <?php if ($ampm == 'PM') echo 'selected'; ?>>PM</option>
  </select><br>

  <label for="notes">Notes:</label><br>
  <textarea id="notes" name="notes" rows="4" cols="50"><?php echo htmlspecialchars($notes); ?></textarea><br>

  <input type="submit" value="Submit" name="Submit">
</form>

</form>
</body>
</html>
