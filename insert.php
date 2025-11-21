<?php
$servername = "localhost";
$username = "root"; // your username
$password = "root"; // your password
$database = "aquabus";
$valid = false;

// server site input available trip and display it to user
// Getting values
$TripID = $_POST["TripID"];
$BoatID = $_POST["BoatID"];
$BoatName = $_POST["BoatName"];
$DepartDockID = $_POST["DepartDockID"];
$ArriveDockID = $_POST["ArriveDockID"];
$Date = $_POST["Date"];
$DepartTime = $_POST["DepartTime"];
$ArriveTime = $_POST["ArriveTime"];
$Fare = $_POST["Fare"];

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
} else {
  echo "Connection Succesful! <br>";
}

// construct the query
$query = "INSERT INTO Trip (TripID, Date, DepartTime, ArriveTime, Fare, BoatID, DepartDockID, ArriveDockID) VALUES('$TripID','$Date','$DepartTime','$ArriveTime','$Fare','$BoatID','$DepartDockID','$ArriveDockID')";

// Execute the query
if ($conn->query($query) === TRUE) {
  echo "New record created successfully!";
} else {
  echo "Error: " . $conn->error;
}

$conn->close();
?>