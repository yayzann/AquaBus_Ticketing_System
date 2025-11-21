<?php
$servername = "localhost";
$username = "root"; // your username
$password = "root"; // your password
$database = "boattrip";
$valid = false;

// server site input available trip and display it to user
// Getting values
$TripID = $_POST["TripID"];
$BoatID = $_POST["BoatID"];
$BoatName = $_POST["BoatName"];
$DepartDockID = $_POST["FromDock"];
$ArriveDockID = $_POST["ToDock"];
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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Boat Trip</title>
</head>
<body>
    <div class="container">
        <h1>Boat Trip</h1>
        <form method="post">
            <div class="row">
                <div class="col-md-6">
                    <label class="form-label" for="tripId">Trip ID</label>
                    <input class="form-control" type="text" id="tripId" name="TripID" required />
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="boatId">Boat ID</label>
                    <input class="form-control" type="text" id="boatId" name="BoatID" required />
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="boatName">Boat Name</label>
                    <input class="form-control" type="text" id="boatName" name="BoatName" required />
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="fromDock">From Dock</label>
                    <input class="form-control" type="text" id="fromDock" name="FromDock" required />
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="toDock">To Dock</label>
                    <input class="form-control" type="text" id="toDock" name="ToDock" required />
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="date">Date</label>
                    <input class="form-control" type="date" id="date" name="Date" required />
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="departTime">Departure Time</label>
                    <input class="form-control" type="time" id="departTime" name="DepartTime" required />
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="arriveTime">Arrival Time</label>
                    <input class="form-control" type="time" id="arriveTime" name="ArriveTime" required />
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="fare">Fare</label>
                    <input class="form-control" type="number" id="fare" name="Fare" required />
                </div>
            </div>
            <button type="submit">Submit</button>
        </form>
    </div>
</body>
</html>

