<?php
// quick db connection, prob should be in seperate file 
$conn = new mysqli("localhost", "root", "root", "aquabus");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// small helper so we dont echo unsafe stuff
function h($s) { 
    return htmlspecialchars($s ?? "", ENT_QUOTES); 
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Aquabus • Admin View</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <!-- NAVBAR, copy/paste from other files so everything looks same  -->
  <nav class="navbar navbar-expand-lg bg-white border-bottom sticky-top">
    <div class="container">
      <a class="navbar-brand d-flex align-items-center gap-2" href="Aquabus.html">
        <i class="bi bi-boat"></i>
        <span class="brand-badge">Aquabus</span>
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav" aria-controls="nav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="nav">
        <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
          <li class="nav-item"><a class="nav-link" href="Aquabus.html#about">About</a></li>
          <li class="nav-item"><a class="nav-link" href="Aquabus.html#search">Find Trips</a></li>
          <li class="nav-item"><a class="nav-link" href="Aquabus.html#contact">Contact</a></li>
          <li class="nav-item"><a class="nav-link" href="insert.html">Add Trip</a></li>
          <li class="nav-item"><a class="nav-link active" href="reports.php">Admin view</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <main class="py-5 bg-light">
    <div class="container">
      <!-- main title for this page -->
      <h1 class="h3 mb-4">
        <i class="bi bi-bar-chart me-2"></i>Admin view
      </h1>
      <p class="text-muted mb-4">
        Very simple admin style page, each card is one of the queries we need:
        projection, selection, join, division, aggregations, nested aggregation, delete, update.
      </p>

      <!-- 1. PROJECTION QUERY (just show one column from Boat) -->
      <div class="card mb-4">
        <div class="card-body">
          <h2 class="h5">1. Projection – choose one column from Boat</h2>
          <!-- form so user can pick which column to project -->
          <form class="row g-2 mb-3" method="get">
            <input type="hidden" name="q" value="projection">
            <div class="col-sm-4">
              <label class="form-label">Column to show</label>
              <select name="proj_field" class="form-select">
                <?php
                  // little map so we dont allow random columns
                  $allowedProj = ["BoatName" => "Boat Name", "Colour" => "Colour", "Size" => "Size"];
                  $sel = $_GET['proj_field'] ?? 'BoatName';
                  foreach ($allowedProj as $val => $label) {
                      $selected = ($sel === $val) ? 'selected' : '';
                      echo "<option value=\"".h($val)."\" $selected>".h($label)."</option>";
                  }
                ?>
              </select>
            </div>
            <div class="col-sm-3 align-self-end">
              <button class="btn btn-primary" type="submit">Run projection</button>
            </div>
          </form>
          <?php
          // when they click the button we run the projection query
          if (($_GET['q'] ?? '') === 'projection') {
              $field = $_GET['proj_field'] ?? 'BoatName';
              if (!array_key_exists($field, $allowedProj)) { 
                  // if something weird happens just fallback
                  $field = 'BoatName'; 
              }
              $sql = "SELECT $field FROM Boat";
              $res = $conn->query($sql);
              if ($res && $res->num_rows > 0) {
                  echo "<table class='table table-sm'><thead><tr><th>".h($allowedProj[$field])."</th></tr></thead><tbody>";
                  while ($row = $res->fetch_assoc()) {
                      echo "<tr><td>".h($row[$field])."</td></tr>";
                  }
                  echo "</tbody></table>";
              } else {
                  echo "<p class='text-muted'>No rows.</p>";
              }
          }
          ?>
        </div>
      </div>

      <!-- 2. SELECTION QUERY (trips with fare >= some value) -->
      <div class="card mb-4">
        <div class="card-body">
          <h2 class="h5">2. Selection – trips with fare ≥ given value</h2>
          <!-- form to type minimum fare -->
          <form class="row g-2 mb-3" method="get">
            <input type="hidden" name="q" value="selection">
            <div class="col-sm-4">
              <label class="form-label">Minimum fare</label>
              <input type="number" step="1" min="0" name="minFare"
                     value="<?php echo h($_GET['minFare'] ?? '10'); ?>" class="form-control">
            </div>
            <div class="col-sm-3 align-self-end">
              <button class="btn btn-primary" type="submit">Run selection</button>
            </div>
          </form>
          <?php
          // selection query only when q=selection
          if (($_GET['q'] ?? '') === 'selection') {
              $minFare = floatval($_GET['minFare'] ?? 0);
              $sql = "SELECT TripID, Date, DepartTime, ArriveTime, Fare
                      FROM Trip
                      WHERE Fare >= $minFare";
              $res = $conn->query($sql);
              if ($res && $res->num_rows > 0) {
                  echo "<table class='table table-sm'><thead>
                          <tr><th>TripID</th><th>Date</th><th>Depart</th><th>Arrive</th><th>Fare</th></tr>
                        </thead><tbody>";
                  while ($row = $res->fetch_assoc()) {
                      echo "<tr>
                              <td>".h($row['TripID'])."</td>
                              <td>".h($row['Date'])."</td>
                              <td>".h($row['DepartTime'])."</td>
                              <td>".h($row['ArriveTime'])."</td>
                              <td>".h($row['Fare'])."</td>
                            </tr>";
                  }
                  echo "</tbody></table>";
              } else {
                  echo "<p class='text-muted'>No trips match that condition.</p>";
              }
          }
          ?>
        </div>
      </div>

      <!-- 3. JOIN QUERY (trip with dock names instead of just ids) -->
      <div class="card mb-4">
        <div class="card-body">
          <h2 class="h5">3. Join – trips with full dock names</h2>
          <!-- simple button, no inputs needed for this one -->
          <form class="mb-3" method="get">
            <input type="hidden" name="q" value="join">
            <button class="btn btn-primary" type="submit">Run join query</button>
          </form>
          <?php
          if (($_GET['q'] ?? '') === 'join') {
              // join trip with dock twice (from and to)
              $sql = "SELECT t.TripID,
                             d1.DockName AS FromDock,
                             d2.DockName AS ToDock,
                             t.Date, t.DepartTime, t.ArriveTime, t.Fare
                      FROM Trip t
                      JOIN Dock d1 ON t.FromDock = d1.DockID
                      JOIN Dock d2 ON t.ToDock   = d2.DockID";
              $res = $conn->query($sql);
              if ($res && $res->num_rows > 0) {
                  echo "<table class='table table-sm'><thead>
                          <tr><th>TripID</th><th>From</th><th>To</th><th>Date</th><th>Depart</th><th>Arrive</th><th>Fare</th></tr>
                        </thead><tbody>";
                  while ($row = $res->fetch_assoc()) {
                      echo "<tr>
                              <td>".h($row['TripID'])."</td>
                              <td>".h($row['FromDock'])."</td>
                              <td>".h($row['ToDock'])."</td>
                              <td>".h($row['Date'])."</td>
                              <td>".h($row['DepartTime'])."</td>
                              <td>".h($row['ArriveTime'])."</td>
                              <td>".h($row['Fare'])."</td>
                            </tr>";
                  }
                  echo "</tbody></table>";
              } else {
                  echo "<p class='text-muted'>No rows.</p>";
              }
          }
          ?>
        </div>
      </div>

      <!-- 4. DIVISION QUERY (passengers who booked ALL trips on a date) -->
      <div class="card mb-4">
        <div class="card-body">
          <h2 class="h5">4. Division – passengers who booked all trips on a date</h2>
          <!-- pick a date, default is the sample date from our data -->
          <form class="row g-2 mb-3" method="get">
            <input type="hidden" name="q" value="division">
            <div class="col-sm-4">
              <label class="form-label">Date</label>
              <input type="date" name="divDate"
                     value="<?php echo h($_GET['divDate'] ?? '2025-10-20'); ?>" class="form-control">
            </div>
            <div class="col-sm-3 align-self-end">
              <button class="btn btn-primary" type="submit">Run division query</button>
            </div>
          </form>
          <?php
          if (($_GET['q'] ?? '') === 'division') {
              $divDate = $conn->real_escape_string($_GET['divDate'] ?? '2025-10-20');
              // relational division style: passenger who dont miss any trip for that date
              $sql = "
                SELECT p.FullName
                FROM Passenger p
                WHERE NOT EXISTS (
                  SELECT t.TripID
                  FROM Trip t
                  WHERE t.Date = '$divDate'
                    AND NOT EXISTS (
                      SELECT 1
                      FROM Booking b
                      WHERE b.TripID = t.TripID
                        AND b.BookingID = p.BookingID
                    )
                )";
              $res = $conn->query($sql);
              if ($res && $res->num_rows > 0) {
                  echo "<table class='table table-sm'><thead><tr><th>Passenger</th></tr></thead><tbody>";
                  while ($row = $res->fetch_assoc()) {
                      echo "<tr><td>".h($row['FullName'])."</td></tr>";
                  }
                  echo "</tbody></table>";
              } else {
                  echo "<p class='text-muted'>No passenger booked all trips for that date.</p>";
              }
          }
          ?>
        </div>
      </div>

      <!-- 5. AGGREGATION QUERIES (two simple group by/avg examples) -->
      <div class="card mb-4">
        <div class="card-body">
          <h2 class="h5">5. Aggregations – on bookings</h2>
          <!-- just a button, we show both aggs together -->
          <form class="mb-3" method="get">
            <input type="hidden" name="q" value="agg">
            <button class="btn btn-primary" type="submit">Show aggregation stats</button>
          </form>
          <?php
          if (($_GET['q'] ?? '') === 'agg') {
              // agg 1: count bookings per trip
              $sql1 = "SELECT TripID, COUNT(*) AS NumBookings
                       FROM Booking
                       GROUP BY TripID";
              $res1 = $conn->query($sql1);
              echo "<h3 class='h6'>Bookings per trip</h3>";
              if ($res1 && $res1->num_rows > 0) {
                  echo "<table class='table table-sm mb-3'><thead>
                          <tr><th>TripID</th><th>#Bookings</th></tr>
                        </thead><tbody>";
                  while ($row = $res1->fetch_assoc()) {
                      echo "<tr><td>".h($row['TripID'])."</td><td>".h($row['NumBookings'])."</td></tr>";
                  }
                  echo "</tbody></table>";
              } else {
                  echo "<p class='text-muted'>No bookings.</p>";
              }

              // agg 2: average booking price
              $sql2 = "SELECT AVG(Price) AS AvgPrice FROM Booking";
              $res2 = $conn->query($sql2);
              echo "<h3 class='h6 mt-3'>Average booking price</h3>";
              if ($res2 && $row = $res2->fetch_assoc()) {
                  echo "<p>Average price: <strong>".h($row['AvgPrice'])."</strong></p>";
              } else {
                  echo "<p class='text-muted'>No data.</p>";
              }
          }
          ?>
        </div>
      </div>

      <!-- 6. NESTED AGGREGATION WITH GROUP BY (avg bookings per passenger per agegroup) -->
      <div class="card mb-4">
        <div class="card-body">
          <h2 class="h5">6. Nested aggregation – average bookings per passenger by age group</h2>
          <!-- again just a button, query is fixed -->
          <form class="mb-3" method="get">
            <input type="hidden" name="q" value="nested">
            <button class="btn btn-primary" type="submit">Run nested aggregation</button>
          </form>
          <?php
          if (($_GET['q'] ?? '') === 'nested') {
              // inner query does count(*), outer query does avg() per age group
              $sql = "
                SELECT AgeGroup,
                       AVG(BookingCount) AS AvgBookingsPerPassenger
                FROM (
                    SELECT p.AgeGroup,
                           p.PassengerID,
                           COUNT(*) AS BookingCount
                    FROM Passenger p
                    JOIN Booking b ON p.BookingID = b.BookingID
                    GROUP BY p.AgeGroup, p.PassengerID
                ) AS x
                GROUP BY AgeGroup
              ";
              $res = $conn->query($sql);
              if ($res && $res->num_rows > 0) {
                  echo "<table class='table table-sm'><thead>
                          <tr><th>Age Group</th><th>Avg #Bookings / Passenger</th></tr>
                        </thead><tbody>";
                  while ($row = $res->fetch_assoc()) {
                      echo "<tr>
                              <td>".h($row['AgeGroup'])."</td>
                              <td>".h($row['AvgBookingsPerPassenger'])."</td>
                            </tr>";
                  }
                  echo "</tbody></table>";
              } else {
                  echo "<p class='text-muted'>No data.</p>";
              }
          }
          ?>
        </div>
      </div>

      <!-- 7. DELETE OPERATION (demonstrate ON DELETE CASCADE) -->
      <div class="card mb-4">
        <div class="card-body">
          <h2 class="h5">7. Delete – remove a booking (cascade to Express/RoundTrip)</h2>
          <?php
          // little message area for the delete result
          $deleteMsg = "";
          if (($_POST['q'] ?? '') === 'delete') {
              $bid = $conn->real_escape_string($_POST['delBookingID'] ?? '');
              if ($bid !== "") {
                  $sql = "DELETE FROM Booking WHERE BookingID = '$bid'";
                  if ($conn->query($sql)) {
                      $deleteMsg = "Deleted booking $bid (related ExpressBooking / RoundTripBooking rows removed by ON DELETE CASCADE).";
                  } else {
                      $deleteMsg = "Error deleting: " . $conn->error;
                  }
              } else {
                  $deleteMsg = "Please enter a BookingID.";
              }
          }
          if ($deleteMsg !== "") {
              echo "<div class='alert alert-info py-2'>".h($deleteMsg)."</div>";
          }
          ?>
          <!-- simple form to type the booking id to delete -->
          <form class="row g-2" method="post">
            <input type="hidden" name="q" value="delete">
            <div class="col-sm-4">
              <label class="form-label">BookingID to delete</label>
              <input type="text" name="delBookingID" class="form-control" placeholder="e.g. B01">
            </div>
            <div class="col-sm-3 align-self-end">
              <button class="btn btn-danger" type="submit">Delete booking</button>
            </div>
          </form>
        </div>
      </div>
      <!-- 8. UPDATE OPERATION (change fare for a trip) -->
      <div class="card mb-4">
        <div class="card-body">
          <h2 class="h5">8. Update – change fare for a trip</h2>
          <?php
          // message for update feedback
          $updateMsg = "";
          if (($_POST['q'] ?? '') === 'update') {
              $tid = $conn->real_escape_string($_POST['updTripID'] ?? '');
              $newFare = floatval($_POST['updFare'] ?? 0);
              if ($tid !== "") {
                  $sql = "UPDATE Trip SET Fare = $newFare WHERE TripID = '$tid'";
                  if ($conn->query($sql)) {
                      $updateMsg = "Updated fare for trip $tid to $newFare.";
                  } else {
                      $updateMsg = "Error updating: " . $conn->error;
                  }
              } else {
                  $updateMsg = "Please enter a TripID.";
              }
          }
          if ($updateMsg !== "") {
              echo "<div class='alert alert-info py-2'>".h($updateMsg)."</div>";
          }
          ?>
          <!-- form where admin types trip id and new fare -->
          <form class="row g-2" method="post">
            <input type="hidden" name="q" value="update">
            <div class="col-sm-3">
              <label class="form-label">TripID</label>
              <input type="text" name="updTripID" class="form-control" placeholder="e.g. T01">
            </div>
            <div class="col-sm-3">
              <label class="form-label">New fare</label>
              <input type="number" step="1" min="0" name="updFare" class="form-control" placeholder="e.g. 12">
            </div>
            <div class="col-sm-3 align-self-end">
              <button class="btn btn-primary" type="submit">Update fare</button>
            </div>
          </form>
        </div>
      </div>

    </div>
  </main>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
// close db connection at very end so we dont leak it
$conn->close();
?>

