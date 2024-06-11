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
  <a href="allList.php">All list</a>
  <?php

  require_once("config/config.php");

  $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
  if ($conn->connect_error) {
    die("Couldn't connect to the database: " . $conn->connect_error);
  }


  ?>
  <div class="outer-div">
    <?php
    $query1 = "SELECT SUM(minute_total) AS total_minutes FROM main_db;";
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
    $totalofTime = ($total_minutes / (486 * 60)) * 100;
    $totalHours =  ($total_minutes / 60);
    ?>


    <div class="inner-div" style="width: <?php echo "$totalofTime"; ?>%;"></div>



    <?php

    ?>
    <p class="loadingBarP"><?php echo number_format($totalofTime, 3); ?> % and  <?php echo number_format($totalHours); ?> hours or to be exact <?php echo floor($totalHours); ?> Hrs <?php echo ($total_minutes - (floor($totalHours)*60)); ?>min</p>


  </div>

  <?php
  require_once("config/config.php");

  $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
  if ($conn->connect_error) {
    die("Couldn't connect to the database: " . $conn->connect_error);
  }

  $query = "SELECT * FROM main_db";
  $stmt = $conn->prepare($query);
  $stmt->execute();
  $result = $stmt->get_result();

  // Create an associative array to store merged data by date
  $merged_data = array();

  // Loop through the result and merge rows by date
  while ($row = $result->fetch_assoc()) {
    $date = $row['Date'];
    if (!isset($merged_data[$date])) {
      // Initialize merged data for this date
      $merged_data[$date] = array(
        'morning' => array(),
        'afternoon' => array(),
        'total_minutes' => 0,
        'total_hours' => 0
      );
    }

    // Determine whether it's morning or afternoon based on Am_Pm
    $time_slot = ($row['Am_Pm'] == 'AM') ? 'morning' : 'afternoon';

    // Add start and end time to the corresponding time slot
    $time_range = date('g:i A', strtotime($row['start_time'])) . ' - ' . date('g:i A', strtotime($row['end_time']));
    $merged_data[$date][$time_slot][] = $time_range;

    // Increment total minutes and hours
    $merged_data[$date]['total_minutes'] += $row['minute_total'];
    $merged_data[$date]['total_hours'] += $row['hour_total'];
  }

  ?>

  <table>
    <thead>
      <tr>
        <th>Date</th>
        <th>Morning</th>
        <th>Afternoon</th>
        <th>Total minutes</th>
        <th>Total hours</th>
        <th>Time readable format</th>
        <th>hrs</th>
        <th>min</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $i = 0;
      // Loop through merged data and display in table format
      foreach ($merged_data as $date => $data) {
        $i++;
      ?>
        <tr>
          <td><?php echo $i . " | ";
              echo date('F j, Y', strtotime($date)); ?></td>
          <td><?php echo implode('<br>', $data['morning']); ?></td>
          <td><?php echo implode('<br>', $data['afternoon']); ?></td>
          <td><?php echo $data['total_minutes']; ?></td>
          <td><?php echo number_format(($data['total_minutes'] / 60), 2); ?> | <?php echo number_format(($data['total_minutes'] / 60), 0); ?> | <?php echo number_format(($data['total_minutes'] / (60 * 468) * 100), 0); ?>%</td>
          <td><?php echo floor($data['total_minutes'] / 60);?> hours <?php echo  ($data['total_minutes']- ((floor($data['total_minutes'] / 60))*60)) ;?>min</td>
        <td> <?php echo floor($data['total_minutes'] / 60);?> </td>
        <td> <?php echo  ($data['total_minutes']- ((floor($data['total_minutes'] / 60))*60)) ;?> </td>
       
        </tr>
      <?php
      }
      ?>
    </tbody>
  </table>

</body>

</html>