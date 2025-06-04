import { createNotyf } from "../../../components/alerts/notyf.js";
import { onShow, onHide } from "../../../components/alerts/sweetalert2/swal.js";

const notyf = createNotyf();

const cleave = new Cleave("#inputBudgetAmount", {
  numeral: true,
  numeralThousandsGroupStyle: "thousand",
});

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
      right: "dayGridMonth,listYear budgetApprovals",
    },
    customButtons: {
      budgetApprovals: {
        text: "Budget Approvals",
        click: function () {
          const modal = new bootstrap.Modal(
            document.getElementById("budgetApprovalModal")
          );

          modal.show();
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

const tableBody = document.querySelector(".budget-approval-modal tbody");

async function fetchEventInfo(publicKey) {
  try {
    const response = await fetch("sql/fetch_event_info.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ id: publicKey }),
    });

    if (!response.ok) {
      throw new Error("Network response was not ok");
    }

    const result = await response.json();

    if (result.success) {
      return {
        title: result.title,
        organization: result.organization,
        amountRequested: result.amount_requested,
      };
    } else {
      console.log("result.message");
      return null;
    }
  } catch (error) {
    console.error("Error fetching budget requests:", error);
  }
}

tableBody.addEventListener("click", async function (e) {
  const publicKey = e.target.closest("tr").dataset.id;
  const row = e.target.closest("tr");

  const approveBtn = e.target.closest(".approve-btn");
  const rejectBtn = e.target.closest(".reject-btn");

  const changeAmountBtn = e.target.closest(".change-amount-btn");

  if (approveBtn) {
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
      const response = await fetch("sql/approve_budget_request.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ id: publicKey }),
      });

      if (!response.ok) {
        throw new Error("Network response was not ok");
      }

      const result = await response.json();

      Swal.close();
      if (result.success) {
        row.remove();
        notyf.success("Event approved successfully.");
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
      console.error(error);
    }
  }

  if (rejectBtn) {
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
      const response = await fetch("sql/reject_budget_request.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ id: publicKey }),
      });

      if (!response.ok) {
        throw new Error("Network response was not ok");
      }

      const result = await response.json();

      Swal.close();
      if (result.success) {
        row.remove();
        notyf.success("Event rejected successfully.");
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
      console.error(error);
    }
  }

  if (changeAmountBtn) {
    const eventInfo = await fetchEventInfo(publicKey);

    const inputPublicKey = document.getElementById("inputPublicKey");

    const title = document.getElementById("eventTitle");
    const organization = document.getElementById("eventOrganization");
    const inputAmount = document.getElementById("inputBudgetAmount");

    inputPublicKey.value = publicKey;

    title.textContent = eventInfo.title;
    organization.textContent = eventInfo.organization;
    inputAmount.value = eventInfo.amountRequested;
  }
});

// Change Budget Amount
document
  .getElementById("changeBudgetAmountForm")
  .addEventListener("submit", async function (e) {
    e.preventDefault();

    const formData = new FormData(this);

    const modalElement = document.getElementById("changeBudgetAmountModal");
    const thisModal = bootstrap.Modal.getInstance(modalElement);

    const budgetApprovalModal = new bootstrap.Modal("#budgetApprovalModal");

    const publicKey = document.getElementById("inputPublicKey").value;
    const row = document.querySelector(
      `.budget-approval-modal tbody tr[data-id="${publicKey}"]`
    );

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
      const response = await fetch("sql/approve_budget_request.php", {
        method: "POST",
        body: formData,
      });

      if (!response.ok) {
        throw new Error("Network response was not ok");
      }

      const result = await response.json();

      Swal.close();
      if (result.success) {
        row.remove();

        thisModal.hide();

        modalElement.addEventListener(
          "hidden.bs.modal",
          function () {
            budgetApprovalModal.show();
          },
          { once: true }
        );

        this.reset();

        notyf.success("Event approved successfully.");
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
      console.error(error);
    }
  });
