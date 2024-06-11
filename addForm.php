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



<form action="submit.php" method="post">
  <a href="index.php">Go Back</a><br><br>
  <label for="date">Date:</label><br>
  <input type="date" id="date" name="date" required><br>

  <label for="start_time">Start Time:</label><br>
  <input type="time" id="start_time" name="start_time" required><br>

  <label for="end_time">End Time:</label><br>
  <input type="time" id="end_time" name="end_time" required><br>

  <label for="ampm">AM/PM:</label><br>
  <select id="ampm" name="ampm" required>
    <option value="AM">AM</option>
    <option value="PM">PM</option>
  </select><br>

  <label for="notes">Notes:</label><br>
  <textarea id="notes" name="notes" rows="4" cols="50"></textarea><br>

  <input type="submit" value="Submit">
</form>

</body>
</html>
