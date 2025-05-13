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
          alert("Custom button clicked!"); // Custom action
        },
      },
    },
    dayMaxEventRows: true,
    views: {
      dayGridMonth: { buttonText: "Month" },
      listYear: { buttonText: "Schedules" },
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

      const formatDate = (date, isAllDay) => {
        if (!date) return "N/A";

        const options = { year: "numeric", month: "long", day: "2-digit" };
        const formattedDate = date.toLocaleDateString("en-US", options);

        if (isAllDay) {
          return formattedDate;
        }

        const timeOptions = {
          hour: "numeric",
          minute: "2-digit",
          hour12: true,
        };
        const formattedTime = date.toLocaleTimeString("en-US", timeOptions);

        return `${formattedDate} - ${formattedTime}`;
      };
      const formattedStart = formatDate(startDate, isAllDay);
      const formattedEnd = formatDate(endDate, isAllDay);

      eventTippy = tippy(info.el, {
        theme: "light",
        content: `
              <div class="container-fluid">
                <div class="row tippy-event-details">
                  <div class="col">
                    <div class="row header">
                      <div class="col">
                        <h3>Event Details</h3>
                      </div>
                    </div>
                    <div class="row title">
                      <div class="col">
                        <h4>${info.event.title}</h4>
                        <h6>${info.event.extendedProps.scheduled_by}</h6>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col">
                        <p>${info.event.extendedProps.description}</p>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col">
                        <p>${info.event.extendedProps.location}</p>
                      </div>
                    </div>
                    <div class="row date">
                      <div class="col">
                        <h4>Start Date</h4>
                        <p>${formattedStart}</p>
                      </div>
                      <div class="col">
                        <h4>End Date</h4>
                        <p>${formattedEnd}</p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            `,
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
