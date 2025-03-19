let activeTippy = null;

// FULL CALENDAR
document.addEventListener("DOMContentLoaded", function () {
  const calendarEl = document.getElementById("calendar");
  const calendar = new FullCalendar.Calendar(calendarEl, {
    initialView: "dayGridMonth",
    height: "86vh",
    headerToolbar: {
      left: "prev,next today",
      center: "title",
      right: "dayGridMonth,listYear",
    },
    dayMaxEventRows: true,
    views: {
      dayGridMonth: { buttonText: "Month" },
      listYear: { buttonText: "Schedules" },
    },
    eventDidMount: function (info) {
      if (info.view.type === "listYear") {
        let scheduledBy = info.event.extendedProps.scheduled_by || "Unknown";
        let titleEl = info.el.querySelector(".fc-list-event-title");

        if (!info.el.querySelector(".scheduled-by")) {
          let scheduledByEl = document.createElement("div");
          scheduledByEl.classList.add("scheduled-by");
          scheduledByEl.innerHTML = `<h5><strong>Scheduled by:</strong> ${scheduledBy}</h5>`;

          titleEl.appendChild(scheduledByEl);
        }
      }
    },
    events: "sql/events.php",
    eventClick: function (info) {
      info.jsEvent.preventDefault();

      if (activeTippy && activeTippy.reference === info.el) {
        activeTippy.hide();
        activeTippy = null;
        return;
      }

      tippy.hideAll();

      activeTippy = tippy(info.el, {
        theme: "light",
        content: `
            <strong>${info.event.title}</strong><br>
            🕒 ${info.event.start.toLocaleString()} <br>
            📌 ${info.event.extendedProps.description || "No details"}
        `,
        allowHTML: true,
        interactive: true,
        trigger: "manual",
        placement: "right",
        onHidden(instance) {
          if (activeTippy === instance) {
            activeTippy = null;
          }
        },
      });

      activeTippy.show();
    },
  });
  calendar.render();
});

// TOGGLE EVENT TIME
document.addEventListener("DOMContentLoaded", function () {
  const allDayCheckbox = document.getElementById("allDay");
  const eventTimeDiv = document.getElementById("eventTime");
  const startTimeInput = document.getElementById("inputStartTime");
  const endTimeInput = document.getElementById("inputEndTime");

  function toggleEventTime() {
    if (allDayCheckbox.checked) {
      eventTimeDiv.style.display = "none";
      startTimeInput.value = "";
      endTimeInput.value = "";
      startTimeInput.removeAttribute("required");
      endTimeInput.removeAttribute("required");
    } else {
      eventTimeDiv.style.display = "flex";
      startTimeInput.required = true;
      endTimeInput.required = true;
    }
  }

  // Initial check on page load
  toggleEventTime();

  // Add event listener
  allDayCheckbox.addEventListener("change", toggleEventTime);
});

// ADD EVENT
document
  .getElementById("addEventForm")
  .addEventListener("submit", async function (e) {
    e.preventDefault();

    const formData = new FormData(this);

    try {
      const response = await fetch("sql/add-event.php", {
        method: "POST",
        body: formData,
      });

      const result = await response.text(); // Assuming response is plain text (adjust if JSON)
      alert(result);
    } catch (error) {
      console.error("Error details:", error);
      alert("Error: " + error.message);
    }
  });

// APPROVE EVENT
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
