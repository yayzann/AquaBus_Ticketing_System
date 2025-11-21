<?php
$servername = "localhost";
$username = "root"; // your username
$password = ""; //your password
$database = "boattrip";


$Name=$_POST["name"];
$Email=$_POST["email"];
$Message=$_POST["message"];


$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
die("Connection failed: " . $conn->connect_error);
}
else {
echo "Connection Succesful! <br>";
}

$query = "INSERT INTO messages (Name, Email, Message) VALUES('$Name','$Email','$Message')";

if ($conn->query($query) === TRUE) {
echo "New record created successfully!";
} else {
echo "Error: " . $conn->error;
} $conn->close();
?>


