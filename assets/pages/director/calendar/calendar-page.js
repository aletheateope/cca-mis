import { onShow, onHide } from "../../../components/alerts/sweetalert2/swal.js";
import { createNotyf } from "../../../components/alerts/notyf.js";

const notyf = createNotyf();

FilePond.registerPlugin(FilePondPluginPdfPreview);
FilePond.registerPlugin(FilePondPluginFileValidateType);

const inputElement = document.getElementById("letterUpload");
const pond = FilePond.create(inputElement, {
  acceptedFileTypes: ["application/pdf"],
});

const cleave = new Cleave("#inputBudgetAmount", {
  numeral: true,
  numeralThousandsGroupStyle: "thousand",
});

let eventTippy = null;
let calendar;

// FULL CALENDAR
document.addEventListener("DOMContentLoaded", function () {
  const calendarEl = document.getElementById("calendar");

  calendar = new FullCalendar.Calendar(calendarEl, {
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
                    <div class="action-group">
                      <button class="no-style-btn edit-btn">
                        <i class="bi bi-pencil-square"></i>
                      </button>
                      <button class="no-style-btn delete-btn">
                        <i class="bi bi-trash-fill"></i>
                      </button>
                      <button class="no-style-btn options-btn">
                        <i class="bi bi-three-dots-vertical"></i>
                      </button>
                    </div>
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
                    <h5>Start Date</h5>
                    <p>${formattedStart}</p>
                  </div>
                  <div class="col">
                    <h5>End Date</h5>
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

          instance.popper
            .querySelector(".delete-btn")
            .addEventListener("click", async function () {
              Swal.fire({
                title: `Are you sure you want to delete the event titled "${info.event.title}"?`,
                text: "This event will be permanently deleted. Do you want to continue?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes",
                cancelButtonText: "No",
                reverseButtons: true,
                showClass: {
                  popup: onShow,
                },
                hideClass: {
                  popup: onHide,
                },
                customClass: {
                  popup: "swal-container",
                },
              }).then(async (result) => {
                if (result.isConfirmed) {
                  Swal.fire({
                    title: "Processing...",
                    text: "Please wait while we delete the event.",
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
                    const response = await fetch("sql/delete_event.php", {
                      method: "DELETE",
                      headers: {
                        "content-type": "application/json",
                      },
                      body: JSON.stringify({
                        eventId: info.event.id,
                      }),
                    });

                    if (!response.ok) {
                      throw new Error(`HTTP error! Status: ${response.status}`);
                    }

                    const data = await response.json();

                    Swal.close();

                    if (data.success) {
                      info.event.remove();
                      notyf.success("Event deleted successfully.");
                    } else {
                      Swal.fire({
                        title: "Error!",
                        text: "Failed to delete event, please try again.",
                        icon: "error",
                        showClass: {
                          popup: onShow,
                        },
                        hideClass: {
                          popup: onHide,
                        },
                      });
                      console.log("Delete event error:", data.message);
                    }
                  } catch (error) {
                    Swal.close();
                    Swal.fire({
                      title: "Error!",
                      text: "Failed to delete event, please try again.",
                      icon: "error",
                      showClass: {
                        popup: onShow,
                      },
                      hideClass: {
                        popup: onHide,
                      },
                    });
                    console.error("Delete event error:", error);
                  }
                }
              });
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

      tippy(".options-btn", {
        theme: "light",
        content: `
          <div class="tippy-no-bullets tippy-options">
            <ul>
              <li><button class="no-style-btn cancel-event-btn">Cancel Event</button></li>
            </ul>
          </div>
        `,
        offset: [0, 5],
        allowHTML: true,
        arrow: false,
        trigger: "click",
        interactive: true,
        placement: "bottom",
        appendTo: () => document.body,
        onShow(instance) {
          eventTippy.setProps({ hideOnClick: false });
        },
        onMount(instance) {
          instance.popper.addEventListener("mousedown", function (evt) {
            console.log("clicked inside Tippy");
            evt.stopPropagation();
            evt.preventDefault();
          });
        },
        onHide(instance) {
          eventTippy.setProps({ hideOnClick: true });
        },
      });

      tippy(".delete-btn", {
        theme: "light",
        content: "Delete Event",
        placement: "top",
      });

      tippy(".edit-btn", {
        theme: "light",
        content: "Edit Event",
        placement: "top",
      });

      tippy(".options-btn", {
        theme: "light",
        content: "More",
        placement: "top",
      });
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

    const startDate = document.getElementById("inputStartDate");
    const endDate = document.getElementById("inputEndDate");

    const startTime = document.getElementById("inputStartTime");
    const endTime = document.getElementById("inputEndTime");

    const allDay = document.getElementById("allDay");

    if (startDate.value > endDate.value) {
      alert("The end date must be after the start date.");
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
      text: "Please wait while we add the event.",
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
      const response = await fetch("sql/add_event.php", {
        method: "POST",
        body: formData,
      });

      const data = await response.json();

      Swal.close();

      if (data.success) {
        notyf.success("The event has been added successfully.");

        calendar.addEvent(data.event);
      } else {
        Swal.fire({
          title: "Error!",
          text: "Failed to add the event. Please try again.",
          icon: "error",
          showClass: {
            popup: onShow,
          },
          hideClass: {
            popup: onHide,
          },
        });

        console.log("Error: " + data.message);
      }
    } catch (error) {
      Swal.close();
      Swal.fire({
        title: "Error!",
        text: "Failed to add the event. Please try again.",
        icon: "error",
        showClass: {
          popup: onShow,
        },
        hideClass: {
          popup: onHide,
        },
      });

      console.error("Error details:", error);
    }
  });

// EVENT REQUEST PANEL VISIBILITY
function eventRequestPanelVisibility() {
  const requestCards = document.querySelectorAll(".event-request-card");
  const panel = document.querySelector(".event-approval-panel");

  if (panel && requestCards.length === 0) {
    panel.classList.remove("show");

    setTimeout(() => {
      calendar.updateSize();
    }, 300); // small delay to let the DOM settle
  }
}

const eventApprovalPanel = document.querySelector(".event-approval-panel");

// FETCH REQUESTED EVENT TITLE
async function fetchEventRequestInfo(publicKey) {
  try {
    const response = await fetch("sql/event_request_info.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({ id: publicKey }),
    });

    if (!response.ok) {
      throw new Error(`HTTP error! Status: ${response.status}`);
    }

    const data = await response.json();
    if (data.error) {
      console.error("Error:", data.error);
      return null;
    }

    return {
      title: data.title,
      organization: data.organization,
    };
  } catch (error) {
    console.error("Error fetching event title:", error);
    return null;
  }
}

eventApprovalPanel.addEventListener("click", async function (e) {
  const publicKey = e.target.closest(".event-request-card").dataset.id;

  const approveBtn = e.target.closest(".approve-btn");
  const rejectBtn = e.target.closest(".reject-btn");
  const returnBtn = e.target.closest(".return-btn");

  const requestBudgetBtn = e.target.closest(".request-budget-btn");

  // APPROVE EVENT
  if (approveBtn) {
    Swal.fire({
      title: "Processing...",
      text: "Please wait while we approve the event.",
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
      const response = await fetch("sql/approve_event.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({ id: publicKey }),
      });

      if (!response.ok) {
        throw new Error(`HTTP error! Status: ${response.status}`);
      }

      const data = await response.json();

      Swal.close();

      if (data.success) {
        notyf.success("The requested event has been approved successfully.");

        e.target.closest(".event-request-card").remove();
        eventRequestPanelVisibility();

        calendar.addEvent(data.event);
      } else {
        Swal.fire({
          title: "Error!",
          text: "Failed to approve the requested event. Please try again.",
          icon: "error",
          showClass: {
            popup: onShow,
          },
          hideClass: {
            popup: onHide,
          },
        });
        console.log(
          "Error approving event: " + (data.error || "Unknown error")
        );
      }
    } catch (error) {
      Swal.close();
      Swal.fire({
        title: "Error!",
        text: "Failed to approve the requested event. Please try again.",
        icon: "error",
        showClass: {
          popup: onShow,
        },
        hideClass: {
          popup: onHide,
        },
      });
      console.error("Error:", error);
    }
  }

  if (requestBudgetBtn) {
    const eventInfo = await fetchEventRequestInfo(publicKey);

    const eventRequestTitle = document.getElementById(
      "eventRequestTitleBudget"
    );
    const eventRequestOrganization = document.getElementById(
      "eventRequestOrganizationBudget"
    );

    const inputPublicKey = document.getElementById(
      "eventRequestPublicKeyBudget"
    );

    if (!eventInfo) {
      console.log("Failed to fetch event request.");
      return;
    }

    inputPublicKey.value = publicKey;

    eventRequestTitle.textContent = eventInfo.title;
    eventRequestOrganization.textContent = eventInfo.organization;
  }

  // REJECT EVENT
  if (rejectBtn) {
    const eventInfo = await fetchEventRequestInfo(publicKey);

    if (!eventInfo) {
      Swal.fire({
        title: "Error",
        text: "Failed to fetch event title",
        icon: "error",
        showClass: {
          popup: onShow,
        },
        hideClass: {
          popup: onHide,
        },
        customClass: {
          popup: "swal-container",
        },
      });
      return;
    }

    Swal.fire({
      title: `Are you sure you want to reject the requested event titled "${eventInfo.title}"?`,
      text: "Rejecting this event will remove it from the request list. Continue?",
      icon: "warning",
      showCancelButton: true,
      confirmButtonText: "Yes",
      cancelButtonText: "No",
      reverseButtons: true,
      showClass: {
        popup: onShow,
      },
      hideClass: {
        popup: onHide,
      },
      customClass: {
        popup: "swal-container",
      },
    }).then(async (result) => {
      if (result.isConfirmed) {
        Swal.fire({
          title: "Processing...",
          text: "Please wait while we reject the requested event.",
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
          const response = await fetch("sql/reject_event.php", {
            method: "POST",
            headers: {
              "Content-Type": "application/json",
            },
            body: JSON.stringify({ id: publicKey }),
          });

          if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
          }

          const result = await response.json();

          Swal.close();

          if (result.success) {
            notyf.success("Requested event rejected successfully.");

            e.target.closest(".event-request-card").remove();

            eventRequestPanelVisibility();
          } else {
            swal.fire({
              title: "Error",
              text: "Failed to reject the requested event. Please try again later.",
              icon: "error",
              showClass: {
                popup: onShow,
              },
              hideClass: {
                popup: onHide,
              },
              customClass: {
                popup: "swal-container",
              },
            });
          }
        } catch (error) {
          console.error("Error details:", error);
          alert("Error: " + error.message);
        }
      }
    });
  }

  if (returnBtn) {
    const eventInfo = await fetchEventRequestInfo(publicKey);
    const inputPublicKey = document.getElementById("eventRequestPublicKey");

    if (!eventInfo) {
      return;
    }

    const title = document.getElementById("eventRequestTitle");
    const organization = document.getElementById("eventRequestOrganization");

    inputPublicKey.value = publicKey;
    title.textContent = eventInfo.title;
    organization.textContent = eventInfo.organization;
  }
});

document
  .getElementById("returnEventRequestForm")
  .addEventListener("submit", async function (e) {
    e.preventDefault();

    const formData = new FormData(this);
    const modalElement = document.getElementById("returnEventRequestModal");
    const modal = bootstrap.Modal.getInstance(modalElement);

    Swal.fire({
      title: "Processing...",
      text: "Please wait while we delete the event.",
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
      const response = await fetch("sql/return_event.php", {
        method: "POST",
        body: formData,
      });

      if (!response.ok) {
        throw new Error("HTTP error!");
      }

      const result = await response.json();

      Swal.close();
      if (result.success) {
        const publicKey = document.getElementById(
          "eventRequestPublicKey"
        ).value;

        const card = document.querySelector(
          `.event-request-card[data-id="${publicKey}"]`
        );

        if (card) card.remove();
        notyf.success("Event returned successfully.");
        modal.hide();
        this.reset();
        eventRequestPanelVisibility();
      } else {
        Swal.fire({
          title: "Error!",
          text: `${result.message}`,
          icon: "error",
          showClass: {
            popup: onShow,
          },
          hideClass: {
            popup: onHide,
          },
        });
      }
    } catch (error) {
      Swal.close();
      Swal.fire({
        title: "Error!",
        text: "Failed to return the event, please try again.",
        icon: "error",
        showClass: {
          popup: onShow,
        },
        hideClass: {
          popup: onHide,
        },
      });
    }
  });

tippy(".approve-plus-btn", {
  content: `
    <div class="container-fluid tippy-selection">
        <ul class="list-group">
            <li class="list-group-item">
                <button class="no-style-btn request-budget-btn" data-bs-toggle="modal" data-bs-target="#requestBudgetModal">Request Budget for this Event</button>
            </li>
        </ul>
    </div>
  `,
  placement: "bottom-end",
  theme: "light",
  arrow: false,
  allowHTML: true,
  interactive: true,
  zIndex: 1050,
  trigger: "click",
});

// SUBMIT BUDGET REQUEST
document
  .getElementById("requestBudgetForm")
  .addEventListener("submit", async function (e) {
    e.preventDefault();

    const formData = new FormData(this);

    pond.getFiles().forEach((fileItem) => {
      formData.append("req_letter[]", fileItem.file);
    });

    Swal.fire({
      title: "Processing...",
      text: "Please wait while we process your request.",
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
      const response = await fetch("sql/submit_event_budget_request.php", {
        method: "POST",
        body: formData,
      });

      if (!response.ok) {
        throw new Error("HTTP error!");
      }

      const submit = await response.json();

      Swal.close();
      if (submit.success) {
        const publicKey = document.getElementById(
          "eventRequestPublicKeyBudget"
        ).value;

        const card = document.querySelector(
          `.event-request-card[data-id="${publicKey}"]`
        );

        if (card) card.remove();

        eventRequestPanelVisibility();

        calendar.addEvent(submit.event);

        const modal = bootstrap.Modal.getInstance(
          document.getElementById("requestBudgetModal")
        );
        modal.hide();

        notyf.success("Budget request submitted successfully.");
        this.reset();
        pond.removeFiles();
      } else {
        alert(submit.message);
      }
    } catch (error) {
      Swal.close();
      console.error("Error details:", error);
    }
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
