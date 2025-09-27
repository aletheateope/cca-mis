import { createNotyf } from "../../../components/alerts/notyf.js";
import { onShow, onHide } from "../../../components/alerts/sweetalert2/swal.js";
import {
  formatDate,
  formatTime,
} from "../../../components/formatter/formatDate.js";
import { getEventDetails } from "../../../components/fullcalendar/eventDetails.js";
import { formatNumber } from "../../../components/formatter/formatNumber.js";

const notyf = createNotyf();

// FULL CALENDAR
let eventTippy = null;

document.addEventListener("DOMContentLoaded", function () {
  const calendarEl = document.getElementById("calendar");

  const calendar = new FullCalendar.Calendar(calendarEl, {
    initialView: "dayGridMonth",
    height: "100%",
    headerToolbar: {
      left: "prev,next today",
      center: "title",
      right: "dayGridMonth,listYear others",
    },
    customButtons: {
      others: {
        icon: "bi bi-three-dots-vertical others",
        click: function () {
          const btn = document.querySelector(".fc-others-button");

          if (!btn._tippy) {
            tippy(btn, {
              content: `
                <div class="container-fluid tippy-selection">
                  <ul class="list-group">
                    <li class="list-group-item">
                      <button class="no-style-btn" data-bs-toggle="modal" data-bs-target="#eventRequestModal">My Requests</button>
                    </li>
                  </ul>
                </div>
              `,
              theme: "light",
              allowHTML: true,
              arrow: false,
              trigger: "manual",
              interactive: true,
              placement: "bottom-end",
              zIndex: 1050,
            });
          }
          btn._tippy.show();
        },
      },
    },
    dayMaxEventRows: true,
    views: {
      dayGridMonth: { buttonText: "Month" },
      listYear: { buttonText: "My Schedules" },
    },
    events: "/cca/assets/sql/events.php",
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
    eventClick: function (info) {
      // info.jsEvent.preventDefault();
      // info.jsEvent.stopPropagation();

      if (eventTippy && eventTippy.reference === info.el) {
        eventTippy.hide();
        eventTippy = null;
        return;
      }

      tippy.hideAll();

      const isAllDay = info.event.allDay;

      const startDate = info.event.start;
      const endDate = info.event.end ? info.event.end : startDate; // If endDate is null, use startDate

      const formatDateTime = (date, isAllDay) => {
        if (!date) return "N/A";

        if (isAllDay) {
          return formatDate(date);
        }

        return `${formatDate(date)} - ${formatTime(
          date.toTimeString().slice(0, 5)
        )}`;
      };

      const formattedStart = formatDateTime(startDate, isAllDay);
      const formattedEnd = formatDateTime(endDate, isAllDay);

      const admin = false;

      eventTippy = tippy(info.el, {
        theme: "light",
        content: getEventDetails(
          info,
          formattedStart,
          formattedEnd,
          formatNumber,
          admin
        ),
        arrow: false,
        maxWidth: 400,
        allowHTML: true,
        interactive: true,
        trigger: "manual",
        placement: "right",
        appendTo: () => document.body,
        zIndex: 1050,
        onMount(instance) {
          instance.popper.addEventListener("mousedown", function (evt) {
            evt.stopPropagation();
            evt.preventDefault();
          });
        },
        onHidden(instance) {
          eventTippy = null;
        },
        popperOptions: {
          modifiers: [
            {
              name: "flip",
              options: {
                fallbackPlacements: ["left", "bottom", "top"],
              },
            },
          ],
        },
      });

      eventTippy.show();
    },
  });
  calendar.render();
});

// TOGGLE EVENT TIME
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

// SUBMIT REQUEST EVENT
document
  .getElementById("requestEventForm")
  .addEventListener("submit", async function (e) {
    e.preventDefault();

    const startDate = document.getElementById("inputStartDate");
    const endDate = document.getElementById("inputEndDate");

    const startTime = document.getElementById("inputStartTime");
    const endTime = document.getElementById("inputEndTime");

    const allDay = document.getElementById("allDay");

    if (startDate.value > endDate.value) {
      const notyf = createNotyf();
      notyf.error("The end date must be after the start date.");
      return;
    }

    if (!allDay.checked && startDate.value === endDate.value) {
      if (startTime.value > endTime.value) {
        alert("The end time must be after the start time.");
        return;
      }
    }

    const formData = new FormData(this);

    Swal.fire({
      title: "Processing...",
      text: "Please wait while we submit the event.",
      allowOutsideClick: false,
      showClass: {
        popup: onShow,
      },
      hideClass: {
        popup: onHide,
      },
      didOpen: () => {
        Swal.showLoading();
      },
    });

    try {
      const response = await fetch("sql/submit_event_request.php", {
        method: "POST",
        body: formData,
      });

      if (!response.ok) {
        throw new Error(`HTTP error! Status: ${response.status}`);
      }

      const result = await response.json();

      Swal.close();

      if (result.success) {
        notyf.success("Event submitted successfully.");

        const modal = bootstrap.Modal.getInstance(
          document.getElementById("requestEventModal")
        );
        modal.hide();

        this.reset();

        toggleEventTime();
      } else {
        alert("Error: " + result.message);
      }
    } catch (error) {
      Swal.close();
      console.error("Error:", error);
      alert("Error: " + error.message);
    }
  });
