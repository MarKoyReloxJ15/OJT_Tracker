<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Loading Bar</title>
  <link rel="stylesheet" href="styles/style.css">
  <style>
    
  </style>
</head>
<body>
  <a href="addForm.php">Add Form</a>
  <a href="index.php">Home</a>
<?php

require_once("config/config.php");

$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
if ($conn->connect_error) {
    die("Couldn't connect to the database: " . $conn->connect_error);
}

// Handle delete request
if(isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $delete_query = "DELETE FROM main_db WHERE id = ?";
    $stmt = $conn->prepare($delete_query);
    $stmt->bind_param("i", $delete_id);
    if($stmt->execute()) {
        echo "Record deleted successfully.";
    } else {
        echo "Error deleting record: " . $conn->error;
    }
}

$query1 = "SELECT SUM(minute_total) AS total_minutes 
FROM main_db 
ORDER BY Date ASC, 
         CASE WHEN Am_Pm = 'AM' THEN 0 ELSE 1 END ASC;
;";
$stmt = $conn->prepare($query1);
$stmt->execute();
$result = $stmt->get_result();

$total_minutes = 0;

// Check if there are any results
if ($result->num_rows > 0) {
    // Fetch the total minutes
    $row = $result->fetch_assoc();
    $total_minutes = $row['total_minutes'];
    // echo "Total minutes: " . $total_minutes;
} else {
    echo "No records found";
}
$totalofTime = ($total_minutes / (486 * 60))* 100;
?>


<div class="outer-div">
  <div class="inner-div" style="width: <?php echo $totalofTime; ?>%;"></div>
  <p class="loadingBarP"><?php echo number_format($totalofTime, 3); ?> %</p>
</div>

<table>
  <thead>
    <tr>
      <th>Date</th>
      <th>Start Time</th>
      <th>End Time</th>
      <th>It is</th>
      <th>Total minutes</th>
      <th>Total hours</th>
      <th>Edit</th>
      <th>Delete</th>
    </tr>
  </thead>
  <tbody>
    <?php
    $query = "SELECT * FROM main_db";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $result = $stmt->get_result();

    // Loop through the result and display in table format
    while ($row = $result->fetch_assoc()) {
    ?>
      <tr>
      <td><?php echo date('F j, Y', strtotime($row['Date'])); ?></td>
<td><?php echo date('g:iA', strtotime($row['start_time'])); ?></td>
<td><?php echo date('g:iA', strtotime($row['end_time'])); ?></td>
<td><?php echo ($row['Am_Pm'] == 'AM') ? 'Morning' : 'Afternoon'; ?></td>

          <td><?php echo $row['minute_total']; ?></td>
          <td><?php echo $row['hour_total']; ?></td>

          <td>
            <!-- <form action="editform.php" method="post">
              <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
              <input type="submit" value="edit">
            </form> -->
            <?php echo "<a href='editform.php?id=". $row['id'] ."' title='View Record' data-toggle='tooltip'>Edit</span></a>"; ?>

        </td>


          <td><a href="?delete_id=<?php echo $row['id']; ?>">Delete</a></td>
      </tr>
    <?php
    }
    ?>
  </tbody>
</table>

</body>
</html>
