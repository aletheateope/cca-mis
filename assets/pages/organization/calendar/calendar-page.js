// FULL CALENDAR
document.addEventListener("DOMContentLoaded", function () {
  const calendarEl = document.getElementById("calendar");
  const calendar = new FullCalendar.Calendar(calendarEl, {
    initialView: "dayGridMonth",
    height: 800,
  });
  calendar.render();
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
