<?php
$servername = "localhost";
$username = "root"; // your username
$password = "root"; //your password
$database = "message";
$valid = false;

//server site input available trip and display it to user
// Getting values
$Name=$_POST["Name"];
$Email=$_POST["Email"];
$Message=$_POST["Message"];

// Create connection
$conn = new mysqli($servername, $username, $password, $database);
// Check connection
if ($conn->connect_error) {
die("Connection failed: " . $conn->connect_error);
}
else {
echo "Connection Succesful! <br>";
}
//construct the query
$query = "INSERT INTO `Message` VALUES('$Name','$Email','$Message')";
//Execute the query
if ($conn->query($query) === TRUE) {
echo "New record created successfully!";
} else {
echo "Error: " . $conn->error;
} $conn->close();
?>

