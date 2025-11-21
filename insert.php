<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "aquabus";

$TripID = $_POST["TripID"] ?? null;
$BoatID = $_POST["BoatID"] ?? null;
$BoatName = $_POST["BoatName"] ?? null;
$DepartDockID = $_POST["DepartDockID"] ?? null;
$ArriveDockID = $_POST["ArriveDockID"] ?? null;
$Date = $_POST["Date"] ?? null;
$DepartTime = $_POST["DepartTime"] ?? null;
$ArriveTime = $_POST["ArriveTime"] ?? null;
$Fare = $_POST["Fare"] ?? null;

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$TripID = intval($TripID);
$BoatID = intval($BoatID);
$DepartDockID = intval($DepartDockID);
$ArriveDockID = intval($ArriveDockID);
$Fare = floatval($Fare);

$query = "INSERT INTO Trip 
(TripID, Date, DepartTime, ArriveTime, Fare, BoatID, FromDock, ToDock) 
VALUES ('$TripID','$Date','$DepartTime','$ArriveTime','$Fare','$BoatID','$DepartDockID','$ArriveDockID')";

if ($conn->query($query) === TRUE) {
    $msg = "Trip #$TripID added successfully!";
} else {
    $msg = "Error: " . $conn->error;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Add Trip • Aquabus</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-5">
<div class="container">
    <div class="alert alert-info"><?= $msg ?></div>
    <a href="insert.html" class="btn btn-primary">Back to Add Trip</a>
</div>
</body>
</html>
