document.addEventListener("DOMContentLoaded", function () {
    let docks = [];
    let boats = [];
    let trips = [];
    const bookings = [];

    const fromSel = document.getElementById("fromDock");
    const toSel = document.getElementById("toDock");
    const searchForm = document.getElementById("searchForm");
    const resultsBody = document.getElementById("resultsBody");
    const resultCount = document.getElementById("resultCount");
    const bookingForm = document.getElementById("bookingForm");
    const modalTripId = document.getElementById("modalTripId");
    const modalTripHidden = document.getElementById("modalTripHidden");
    const confirmBookingBtn = document.getElementById("confirmBookingBtn");

    const bookingModal = new bootstrap.Modal(document.getElementById("bookingModal"));
    const toastEl = document.getElementById("toast");
    const toastMsg = document.getElementById("toastMsg");
    const toast = new bootstrap.Toast(toastEl);

    async function initializeData() {
        try {
            const response = await fetch('DisplayTrip.php');
            if (!response.ok) throw new Error('HTTP error ' + response.status);
            const data = await response.json();

            docks = data.docks;
            boats = data.boats;
            trips = data.trips;

            populateDockSelects();
            renderTrips();
        } catch (err) {
            console.error("Could not fetch trip data:", err);
            resultsBody.innerHTML = `<tr>
                <td colspan="8" class="text-center text-danger py-4">
                    Error loading trip data.
                </td>
            </tr>`;
        }
    }

    function addOption(select, value, label) {
        const opt = document.createElement("option");
        opt.value = value;
        opt.textContent = label;
        select.appendChild(opt);
    }

    function populateDockSelects() {
        fromSel.innerHTML = "";
        toSel.innerHTML = "";
        addOption(fromSel, "", "Any dock");
        addOption(toSel, "", "Any dock");

        docks.forEach(d => {
            addOption(fromSel, d.id, d.name);
            addOption(toSel, d.id, d.name);
        });

        // default from Granville Island
        fromSel.value = "D01";
    }

    function dockName(id) {
        const d = docks.find(d => d.id === id);
        return d ? d.name : id;
    }

    function boatName(id) {
        const b = boats.find(b => b.id === id);
        return b ? b.name : id;
    }

    function fareForTrip(t) {
        const order = {"D01": 0,"D02":1,"D03":2,"D04":3,"D05":4};
        const fromPos = order[t.from_id] ?? 0;
        const toPos = order[t.to_id] ?? 1;
        const stops = Math.abs(toPos - fromPos) || 1;
        return stops * 4;
    }

    function renderTrips() {
        const from = fromSel.value || null;
        const to = toSel.value || null;

        const matched = trips.filter(t => {
            if (from && t.from_id !== from) return false;
            if (to && t.to_id !== to) return false;
            return true;
        });

        resultsBody.innerHTML = "";
        if (matched.length === 0) {
            resultsBody.innerHTML = `<tr>
                <td colspan="8" class="text-center text-secondary py-4">
                    No trips match those filters.
                </td>
            </tr>`;
        } else {
            matched.forEach(t => {
                const fare = fareForTrip(t).toFixed(2);
                const tr = document.createElement("tr");
                tr.innerHTML = `
                    <td>#${t.trip_id}</td>
                    <td>${boatName(t.boat_id)}</td>
                    <td><span class="dock-dot"></span>${dockName(t.from_id)} → ${dockName(t.to_id)}</td>
                    <td>${t.date}</td>
                    <td>${t.depart}</td>
                    <td>${t.arrive}</td>
                    <td>$${fare}</td>
                    <td class="text-end">
                        <button class="btn btn-sm btn-primary" data-trip-id="${t.trip_id}">
                            <i class="bi bi-ticket-perforated me-1"></i>Book
                        </button>
                    </td>
                `;
                resultsBody.appendChild(tr);
            });
        }

        resultCount.textContent = matched.length + " result" + (matched.length === 1 ? "" : "s");
    }

    function findTripById(id) {
        return trips.find(t => t.trip_id === id);
    }

    resultsBody.addEventListener("click", e => {
        const btn = e.target.closest("button[data-trip-id]");
        if (!btn) return;
        const tripId = btn.getAttribute("data-trip-id");
        modalTripId.textContent = "#" + tripId;
        modalTripHidden.value = tripId;
        bookingForm.reset();
        bookingModal.show();
    });

    confirmBookingBtn.addEventListener("click", () => {
        const tripId = modalTripHidden.value;
        const trip = findTripById(tripId);
        const name = document.getElementById("passengerName").value.trim();
        const phone = document.getElementById("passengerPhone").value.trim();
        if (!name) { alert("Please enter passenger name"); return; }
        const fare = trip ? fareForTrip(trip) : 0;
        bookings.push({ trip_id: tripId, passenger_name: name, passenger_phone: phone, fare });
        bookingModal.hide();
        toastMsg.textContent = `Booked trip #${tripId} for ${name} ($${fare.toFixed(2)}).`;
        toast.show();
        console.log("Current demo bookings:", bookings);
    });

    searchForm.addEventListener("submit", e => { e.preventDefault(); renderTrips(); });

    initializeData();
});
