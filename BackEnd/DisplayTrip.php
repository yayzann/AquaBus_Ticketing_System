<?php

function getConnection(){
  $servername = "localhost";
$username = "root";
$password = "";
$dbname = "boattrip";


$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
die("Connection failed: " . $conn->connect_error);
}
return $conn;
}

function DisplayTrip(){

$conn =  getConnection();

$FromDock=$_POST["FromDock"] ?? "All";
$ToDock=$_POST["ToDock"] ?? "All";

if($ToDock == "All"){
  $query = "SELECT TripID , BoatName , FromDock , ToDock , `Date`
, DepartTime , ArriveTime , Fare FROM AvailableTrip";
}
else{
  $query = "SELECT TripID , BoatName , FromDock , ToDock , `Date`
  , DepartTime , ArriveTime , Fare FROM AvailableTrip 
  WHERE ToDock = '$ToDock' AND FromDock = '$FromDock'";
}

$result = $conn->query($query);
if ($result->num_rows > 0) {
echo '
    <table class="table align-middle">
        <thead>
          <tr>
            <th>Choose</th>
            <th>Trip</th>
            <th>Boat</th>
            <th>From → To</th>
            <th>Date</th>
            <th>Depart</th>
            <th>Arrive</th>
            <th>Fare</th>
          </tr>
        </thead>
        <tbody id="resultsBody">';
// print out the table after input the dock
while($row = $result->fetch_assoc()) {
echo "<tr>";
echo  '<td> <input type="radio" name="TripID" value="'.$row["TripID"].'"></td>';
echo  "<td>".$row['TripID']."</td>";
echo   "<td>".$row['BoatName']."</td>";
echo   "<td>";
echo   "<span class='dock-dot'></span>".$row['FromDock']."→".$row['ToDock'];
echo   "</td>";
echo   "<td>".$row['Date']."</td>";
echo   "<td>".$row['DepartTime']."</td>";
echo   "<td>".$row['ArriveTime']."</td>";
echo   "<td>".$row['Fare']."</td>";
echo   "</tr>";}
echo   '</tbody> </table>';   
} else {
echo "No Available Trip";
}
$conn->close();}


function displayOption($opt){
  $conn =  getConnection();
  $query = "SELECT FromDock , ToDock FROM AvailableTrip";
  $result = $conn->query($query);
if ($result->num_rows > 0){
  while($row = $result->fetch_assoc()) {
    if($opt === "from"){
      echo'<option value="'.$row['FromDock'].'">'.$row['FromDock'].'</option>';
    }else if($opt === "to"){
      echo'<option value="'.$row['ToDock'].'">'.$row['ToDock'].'</option>';
    }
  }

}
}


?>

