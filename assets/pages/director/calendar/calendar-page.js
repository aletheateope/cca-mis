// FULL CALENDAR
document.addEventListener("DOMContentLoaded", function () {
  const calendarEl = document.getElementById("calendar");
  const calendar = new FullCalendar.Calendar(calendarEl, {
    initialView: "dayGridMonth",
    height: 800,
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
