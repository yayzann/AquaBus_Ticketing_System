<?php
include 'DisplayTrip.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Aquabus • Booking Demo (Bootstrap)</title>
  <!-- Bootstrap 5.3 via CDN -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    /*Primary colors*/
    :root {
      --bs-primary: #0ea5e9; 
      --bs-secondary: #0f172a; 
    }

    body { 
      background: #fbfbfb; 
    }

    /* hero with boat image in background */
    .hero {
      position: relative;
      color: #fff;
      background-image:
        linear-gradient(135deg, rgba(14,165,233,0.85), rgba(3,105,161,0.85)),
        url("images/Aquabus.jpg");
      background-size: cover;
      background-position: center;
    }

    .brand-badge {
      letter-spacing: .02em;
      font-weight: 700;
    }

    .dock-dot { 
      width: .65rem; 
      height: .65rem; 
      display: inline-block;
      border-radius: 50%; 
      background: var(--bs-primary); 
      margin-right: .35rem;
    }

    .table thead th { 
      white-space: nowrap; 
    }

    .required::after { 
      content:" *"; 
      color: #e11d48; 
    }
  </style>
</head>
<body>
    <!--NOT AFFECTED-->
  <!-- NAVBAR -->
  <nav class="navbar navbar-expand-lg bg-white border-bottom sticky-top">
    <div class="container">
      <a class="navbar-brand d-flex align-items-center gap-2" href="#">
        <i class="bi bi-boat"></i>
        <span class="brand-badge">Aquabus</span>
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav" aria-controls="nav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="nav">
        <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
          <li class="nav-item"><a class="nav-link" href="#about">About</a></li>
          <li class="nav-item"><a class="nav-link active" href="#search">Find Trips</a></li>
        </ul>
      </div>
    </div>
  </nav>
<!--NOT AFFECTED-->
  <!-- HERO -->
  <header class="hero py-5">
    <div class="container">
      <div class="row align-items-center g-4">
        <div class="col-lg-7">
          <h1 class="display-5 fw-bold mb-3">Book your False Creek trip</h1>
          <p class="lead mb-4">Granville Island departures to Hornby and The Village.</p>
          <a class="btn btn-light btn-lg" href="#search">
            <i class="bi bi-search me-2"></i>See trips
          </a>
        </div>
      </div>
    </div>
  </header>
<!--NOT AFFECTED-->
  <!-- ABOUT SECTION -->
  <section class="py-5 bg-white" id="about">
    <div class="container">
      <div class="row g-4 align-items-center">
        <div class="col-lg-6">
          <h2 class="h3 mb-3">About Aquabus</h2>
          <p>
            Aquabus connects key spots along False Creek with small passenger ferries.
            This demo shows simple sample trips leaving from Granville Island.
          </p>
        </div>
        <div class="col-lg-6">
          <ul class="list-unstyled mb-0">
            <li class="mb-2"><i class="bi bi-water me-2"></i>Short crossings</li>
            <li class="mb-2"><i class="bi bi-geo-alt me-2"></i>Granville Island to Hornby and The Village</li>
            <li class="mb-2"><i class="bi bi-ticket-perforated me-2"></i>Flat pricing per stop</li>
          </ul>
        </div>
      </div>
    </div>
  </section>



  <!-- TRIP LIST -->
  <main class="py-5" id="search">
    <div class="container">
      <div class="card shadow-sm mb-4">
        <div class="card-body">
          <h2 class="h4 mb-2">
            <i class="bi bi-compass me-2"></i>Find trips
          </h2>
          <p class="">
            Trips are shown for Granville Island departures.  
            Price to Hornby is $4. Each extra stop from Granville is +$4.
          </p>

          <form id="searchForm" class="row g-3 align-items-end" method="post" action="">
            <div class="col-sm-6 col-md-4">
              <label class="form-label" for="fromDock">From</label>
              <select class="form-select" id="fromDock" name="FromDock">
                <!-- options --><?php displayOption("from") ?>
              </select>
            </div>
            <div class="col-sm-6 col-md-4">
              <label class="form-label" for="toDock">To</label>
              <select class="form-select" id="toDock" name="ToDock">
                <!-- options  --><?php displayOption("to")?>
              </select>
            </div>
            <div class="col-sm-6 col-md-4 d-grid">
              <button type="submit" class="btn btn-primary">
                <i class="bi bi-search me-2"></i>Filter trips
              </button>
            </div>
          </form>
        </div>
      </div>


      
      <!-- RESULTS -->
      <div class="card">
  <div class="card-body">
    <div class="d-flex align-items-center justify-content-between mb-3">
      <h3 class="h5 mb-0"><i class="bi bi-table me-2"></i>Available trips</h3>
      <span class="text-secondary" id="resultCount">0 results</span>
    </div>
    <div class="table-responsive">
    <form method="post" > <!--action="Booking.php"-->
    <?php
      DisplayTrip(); // prints table once, overwriting old output
  ?>
    <input type="submit" value="submit" id="bookingForm">
    </form>
       </div>
        </div>
      </div>
                <!--add php here-->
                
                  <!--<tr><td colspan="8" class="text-center text-secondary py-4">
                    Trips load automatically from Granville Island.
                  </td></tr>-->
                
              
    </div>
  </main>



<!--NOT AFFECTED-->
  <!-- BOOKING MODAL -->
  <div class="modal fade" id="bookingModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Book trip <span id="modalTripId" class="text-secondary"></span></h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="bookingForm" class="row g-3">
            <input type="hidden" id="modalTripHidden" />
            <div class="col-12">
              <label class="form-label required" for="passengerName">Passenger name</label>
              <input class="form-control" id="passengerName" required placeholder="Alex Lee" />
            </div>
            <div class="col-md-6">
              <label class="form-label" for="passengerPhone">Phone</label>
              <input class="form-control" id="passengerPhone" placeholder="604-555-5555" />
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="button" id="confirmBookingBtn" class="btn btn-primary">Confirm Booking</button>
        </div>
      </div>
    </div>
  </div>
<!--NOT AFFECTED-->
  <!-- ABOUT OFFCANVAS:
   it's the bar that slides out when you click About. It looks like a cool feature
   -->
  <div class="offcanvas offcanvas-end" tabindex="-1" id="aboutPanel">
    <div class="offcanvas-header">
      <h5 class="offcanvas-title">About this demo</h5>
      <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body">
      <p>This is a front-end testerrr. Data is coded in JavaScript :0 for now</p>
      <p class="small text-secondary">Bootstrap parts: Navbar, Cards, Table, Modal, Offcanvas, Toast.</p>
    </div>
  </div>

  <section class="py-5" id="contact">
    <div class="container">
      <div class="card shadow-sm">
        <div class="card-body">
          <h2 class="h4 mb-3">Contact us</h2>
          <p class="mb-3">Feel free to drop us a message below!</p>
          <form id="contactForm" class="row g-3" method="Post" action="message.php">
            <div class="col-md-6">
              <label class="form-label" for="contactName">Name</label>
              <input class="form-control" id="contactName " name="name" placeholder="First, Last" />
            </div>
            <div class="col-md-6">
              <label class="form-label" for="contactEmail">Email</label>
              <input class="form-control" id="contactEmail" type="email" name="email"  placeholder="myemail@email.com" />
            </div>
            <div class="col-12">
              <label class="form-label" for="contactMessage">Message</label>
              <textarea class="form-control" id="contactMessage" rows="4" name="message" placeholder="Your message:"></textarea>
            </div>
            <div class="col-12 d-grid d-sm-block">
              <button type="submit" class="btn btn-primary">Send message</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </section>
















  <!--NOT AFFECTED-->
 <!-- feedback -->
 <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1080">
    <div id="toast" class="toast" role="status" aria-live="polite" aria-atomic="true">
      <div class="toast-header">
        <i class="bi bi-check-circle text-success me-2"></i>
        <strong class="me-auto">Success</strong>
        <small>just now</small>
        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
      </div>
      <div class="toast-body" id="toastMsg">Your action completed.</div>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>



