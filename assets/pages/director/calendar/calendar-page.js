// FULL CALENDAR
document.addEventListener("DOMContentLoaded", function () {
  const calendarEl = document.getElementById("calendar");
  const calendar = new FullCalendar.Calendar(calendarEl, {
    initialView: "dayGridMonth",
    height: "86vh",
    events: "sql/events.php",
    headerToolbar: {
      left: "prev,next today",
      center: "title",
      right: "dayGridMonth,listYear", // Buttons for Month View & List View
    },
    views: {
      listYear: { buttonText: "Schedules" }, // Custom label for list view
    },
    eventContent: function (arg) {
      let eventTitle = arg.event.title;
    },
  });
  calendar.render();
});

document.querySelectorAll(".approve-btn").forEach((button) => {
  button.addEventListener("click", function () {
    let eventRequestId = this.getAttribute("data-id");

    fetch("sql/approve-event.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({ id: eventRequestId }),
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          alert(data.message);
        } else {
          alert("Error approving event: " + (data.error || "Unknown error"));
        }
      })
      .catch((error) => console.error("Error:", error));
  });
});

document.querySelectorAll(".reject-btn").forEach((button) => {
  button.addEventListener("click", function () {
    let eventRequestId = this.getAttribute("data-id");
  });
});

// document.addEventListener("DOMContentLoaded", function () {
//   function adjustEventApprovalPanel() {
//     const panel = document.querySelector(".event-approval-panel");
//     const eventApprovalRow = document.querySelector(".row.event-approval");
//     const mainContent = document.querySelector(".main-content");

//     console.log("Window width:", window.innerWidth);

//     if (window.innerWidth < 768) {
//       if (!eventApprovalRow.contains(panel)) {
//         panel.classList.add("col");
//         panel.classList.remove("col-auto");
//         eventApprovalRow.appendChild(panel);
//       }
//     } else {
//       if (mainContent && !mainContent.contains(panel)) {
//         panel.classList.add("col-auto");
//         panel.classList.remove("col");
//         mainContent.parentNode.appendChild(panel);
//       }
//     }
//   }

//   // Run on load and on resize
//   adjustEventApprovalPanel();
//   window.addEventListener("resize", adjustEventApprovalPanel);
// });
