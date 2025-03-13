// FULL CALENDAR
document.addEventListener("DOMContentLoaded", function () {
  const calendarEl = document.getElementById("calendar");
  const calendar = new FullCalendar.Calendar(calendarEl, {
    initialView: "dayGridMonth",
    height: "86vh",
  });
  calendar.render();
});

// TIPPY
tippy(".icon-approve", {
  content: "Approve",
  theme: "light",
  placement: "top",
});

tippy(".icon-reject", {
  content: "Reject",
  theme: "light",
  placement: "top",
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
