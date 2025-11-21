document.addEventListener("DOMContentLoaded", function () {
  let docks = [];
  let boats = [];
  let trips = [];

  const bookings = [];

  // elements
  const fromSel = document.getElementById("fromDock");
  const toSel = document.getElementById("toDock");
  const searchForm = document.getElementById("searchForm");

  const resultsBody = document.getElementById("resultsBody");
  const resultCount = document.getElementById("resultCount");

  const bookingForm = document.getElementById("bookingForm");
  const modalTripId = document.getElementById("modalTripId");
  const modalTripHidden = document.getElementById("modalTripHidden");
  const confirmBookingBtn = document.getElementById("confirmBookingBtn");

  const toastEl = document.getElementById("toast");
  const toastMsg = document.getElementById("toastMsg");

  const bookingModal = new bootstrap.Modal(document.getElementById("bookingModal"));
  const toast = new bootstrap.Toast(toastEl);

  async function initializeData() {
    try {
      const response = await fetch('DisplayTrip.php');

      if (!response.ok) {
        throw new Error('HTTP error status: ' + response.status);
      }

      const data = await response.json();

      // Populate with fetched data
      docks = data.docks;
      boats = data.boats;
      trips = data.trips;

      populateDockSelects();
      renderTrips();

    } catch (error) {
      console.error("Could not fetch trip data:", error);
      resultsBody.innerHTML = `
        <tr>
          <td colspan="8" class="text-center text-danger py-4">
            Error loading trip data.
          </td>
        </tr>`;
    }
  }


  // add one option
  function addOption(select, value, label) {
    const opt = document.createElement("option");
    opt.value = value;
    opt.textContent = label;
    select.appendChild(opt);
  }

  // fill dropdowns
  function populateDockSelects() {
    fromSel.innerHTML = "";
    toSel.innerHTML = "";

    addOption(fromSel, "", "Any dock");
    addOption(toSel, "", "Any dock");

    docks.forEach(function (d) {
      addOption(fromSel, d.id, d.name);
      addOption(toSel, d.id, d.name);
    });

    // default from Granville Island
    fromSel.value = "2";
  }

  // get dock name
  function dockName(id) {
    const d = docks.find(function (dock) {
      return dock.id === id;
    });
    return d ? d.name : id;
  }

  // fare rule: Hornby = 4, The Village = 8 (2 stops)
  function fareForTrip(t) {
    // positions from Granville Island
    const order = {"Granville Island": 0, "Hornby": 1, "The Village": 2  };
    const fromPos = order[t.from_id] ?? 0;
    const toPos = order[t.to_id] ?? 1;
    const stops = Math.abs(toPos - fromPos) || 1;
    return stops * 4;
  }
  function boatName(id) {
    const b = Array.isArray(boats) ? boats.find(b => b.id === id) : boats[id];
    return b ? b.name : id;
  }

  // draw the table
  function renderTrips() {
    const from = Number(fromSel.value) || null;
    const to = Number(toSel.value) || null;

    const matched = trips.filter(function (t) {
      if (from && t.from_id !== from) return false;
      if (to && t.to_id !== to) return false;
      return true;
    });

    resultsBody.innerHTML = "";

    if (matched.length === 0) {
      resultsBody.innerHTML = `
        <tr>
          <td colspan="8" class="text-center text-secondary py-4">
            No trips match those filters.
          </td>
        </tr>`;
    } else {
      matched.forEach(function (t) {
        const fare = fareForTrip(t).toFixed(2);
        const tr = document.createElement("tr");
        tr.innerHTML = `
          <td>#${t.trip_id}</td>
          <td>${boatName(t.boat_id)}</td>
          <td>
            <span class="dock-dot"></span>
            ${dockName(t.from_id)} → ${dockName(t.to_id)}
          </td>
          <td>${t.date}</td>
          <td>${t.depart}</td>
          <td>${t.arrive}</td>
          <td>$${fare}</td>
          <td class="text-end">
            <button class="btn btn-sm btn-primary" data-trip-id="${t.trip_id}">
              <i class="bi bi-ticket-perforated me-1"></i>
              Book
            </button>
          </td>
        `;
        resultsBody.appendChild(tr);
      });
    }

    const n = matched.length;
    resultCount.textContent = n + " result" + (n === 1 ? "" : "s");
  }

  // find trip by id
  function findTripById(id) {
    return trips.find(function (t) {
      return t.trip_id === id;
    });
  }

  // click on Book
  resultsBody.addEventListener("click", function (event) {
    const btn = event.target.closest("button[data-trip-id]");
    if (!btn) return;

    const tripId = Number(btn.getAttribute("data-trip-id"));
    modalTripId.textContent = "#" + tripId;
    modalTripHidden.value = tripId;

    bookingForm.reset();
    bookingModal.show();
  });

  // confirm booking
  confirmBookingBtn.addEventListener("click", function () {
    const tripId = Number(modalTripHidden.value);
    const trip = findTripById(tripId);
    const name = document.getElementById("passengerName").value.trim();
    const phone = document.getElementById("passengerPhone").value.trim();

    if (!name) {
      alert("Please enter passenger name");
      return;
    }

    const fare = trip ? fareForTrip(trip) : 0;

    bookings.push({
      trip_id: tripId,
      passenger_name: name,
      passenger_phone: phone,
      fare: fare
    });

    bookingModal.hide();

    toastMsg.textContent = "Booked trip #" + tripId + " for " + name + " ($" + fare.toFixed(2) + ").";
    toast.show();

    console.log("Current demo bookings:", bookings);
  });

  

  // filter form
  searchForm.addEventListener("submit", function (e) {
    e.preventDefault();
    renderTrips();
  });

  // Contact form handling
  document.getElementById("contactForm").addEventListener("submit", function (e) {
    e.preventDefault();

    const name = document.getElementById("contactName").value.trim();
    const email = document.getElementById("contactEmail").value.trim();
    const message = document.getElementById("contactMessage").value.trim();

    if (!name || !email || !message) {
      alert("Please fill all fields with valid info.");
      return;
    }

    // Show success toast
    document.getElementById("toast").textContent = "Thank you. Message sent.";
    toast.show();

    // Reset form
    document.getElementById("contactForm").reset();
  });

  // init
  initializeData();
  populateDockSelects();
  renderTrips(); 
});
