<?php
header('Content-Type: application/json');

function getConnection() {
  $servername = "localhost";
  $username = "root";
  $password = "";
  $dbname = "boattrip";

  $conn = new mysqli($servername, $username, $password, $dbname);

  if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
  }
  return $conn;
}

function getTrips() {
  $conn = getConnection();

  $query = "SELECT TripID, BoatName, FromDock, ToDock, `Date`, DepartTime, ArriveTime, Fare FROM AvailableTrip";
  $result = $conn->query($query);

  $trips = [];
  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      $trips[] = [
        "trip_id" => $row["TripID"],
        "boat_id" => $row["BoatName"],
        "from_id" => $row["FromDock"],
        "to_id" => $row["ToDock"],
        "date" => $row["Date"],
        "depart" => $row["DepartTime"],
        "arrive" => $row["ArriveTime"],
        "fare" => $row["Fare"]
      ];
    }
  }
  $conn->close();
  return $trips;
}

function getDocks() {
  $conn = getConnection();

  $query = "SELECT DockID, DockName FROM Dock";
  $result = $conn->query($query);

  $docks = [];
  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      $docks[] = [
        "id" => $row["DockID"],
        "name" => $row["DockName"]
      ];
    }
  }
  $conn->close();
  return $docks;
}

function getBoats() {
  $conn = getConnection();

  $query = "SELECT BoatID, BoatName FROM Boat";
  $result = $conn->query($query);

  $boats = [];
  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      $boats[] = [
        "id" => $row["BoatID"],
        "name" => $row["BoatName"]
      ];
    }
  }
  $conn->close();
  return $boats;
}

// Return all data as JSON
echo json_encode([
  "docks" => getDocks(),
  "boats" => getBoats(),
  "trips" => getTrips()
]);
?>

