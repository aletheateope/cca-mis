// FULL CALENDAR
document.addEventListener("DOMContentLoaded", function () {
  const calendarEl = document.getElementById("calendar");
  const calendar = new FullCalendar.Calendar(calendarEl, {
    initialView: "dayGridMonth",
    height: 800,
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

// SUBMIT REQUEST EVENT
$(document).ready(function () {
  $("#requestEventForm").on("submit", function (e) {
    e.preventDefault();

    const formData = new FormData(this);

    $.ajax({
      url: "sql/submit-event-request.php",
      type: "POST",
      data: formData,
      contentType: false,
      processData: false,
      success: function (response) {
        alert(response);
      },
      error: function (xhr, status, error) {
        console.log("Error details:", xhr.responseText);
        alert("Error: " + error);
      },
    });
  });
});
