window.addEventListener("pagehide", function () {
  navigator.sendBeacon("sql/unset-month.php");
});

// CLEAVE
var cleaveStartYear = new Cleave("#inputStartYear", {
  date: true,
  datePattern: ["Y"],
});

var cleaveEndYear = new Cleave("#inputEndYear", {
  date: true,
  datePattern: ["Y"],
});

// COLORS
const blue = getComputedStyle(document.documentElement)
  .getPropertyValue("--info")
  .trim();
const red = getComputedStyle(document.documentElement)
  .getPropertyValue("--danger")
  .trim();
const green = getComputedStyle(document.documentElement)
  .getPropertyValue("--success")
  .trim();

//   HORIZONTAL WATERFALL CHART
const ctx = document.getElementById("horizontalWaterfall").getContext("2d");

new Chart(ctx, {
  type: "bar",
  data: {
    labels: ["Credit", "Expense", "Final Balance"],
    datasets: [
      {
        label: "Hidden Base",
        data: [0, 20, 0],
        backgroundColor: "rgba(0, 0, 0, 0)",
      },
      {
        label: "Values",
        data: [100, 80, 20],
        backgroundColor: [green, red, blue],
      },
    ],
  },
  options: {
    indexAxis: "y",
    scales: {
      x: {
        max: 100,
        stacked: true,
        reverse: true,
      },
      y: { stacked: true },
    },
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
      legend: { display: false },
      tooltip: { enabled: false },
    },
  },
});

const startYearInput = document.getElementById("inputStartYear");
const endYearInput = document.getElementById("inputEndYear");

// CHECK ACADEMIC YEAR
document.addEventListener("DOMContentLoaded", function () {
  const monthDropdown = document.getElementById("month");

  // SET SESSION MONTH
  const setSessionMonth = async (monthValue) => {
    let sessionMonth = new FormData();
    sessionMonth.append("nextMonth", monthValue);

    try {
      const sessionResponse = await fetch("sql/month-set.php", {
        method: "POST",
        body: sessionMonth,
      });

      const sessionData = await sessionResponse.json();

      if (sessionData.success) {
      } else {
        console.error(
          "Error setting session month:",
          sessionData.error || "No error message received"
        );
      }
    } catch (error) {
      console.error("Error:", error);
    }
  };

  const checkAcademicYear = async () => {
    try {
      if (!startYearInput.value || !endYearInput.value) return;

      // Ensure both years are entered and are numbers
      let formData = new FormData();
      formData.append("startYear", startYearInput.value);
      formData.append("endYear", endYearInput.value);

      const response = await fetch("sql/check-academic-year.php", {
        method: "POST",
        body: formData,
      });

      const data = await response.json();

      if (data.exists) {
        monthDropdown.value = data.nextMonth;
        monthDropdown.disabled = true;
        setSessionMonth(data.nextMonth);
      } else {
        monthDropdown.disabled = false;
        monthDropdown.value = "1";
        setSessionMonth("1");
      }
    } catch (error) {
      console.error("Error:", error);
    }
  };

  monthDropdown.addEventListener("change", function () {
    setSessionMonth(monthDropdown.value);
  });

  [startYearInput, endYearInput].forEach((input) =>
    input.addEventListener("input", checkAcademicYear)
  );
});

// ADD RECORD
document
  .getElementById("addRecordForm")
  .addEventListener("submit", async function (event) {
    event.preventDefault(); // Prevent default form submission

    const startYear = parseInt(startYearInput.value);
    const endYear = parseInt(endYearInput.value);

    if (endYear < startYear) {
      alert("End year cannot be less than the start year.");
      startYearInput.value = "";
      endYearInput.value = "";
      return; // Stop from submitting the form
    }

    if (endYear > startYear + 1) {
      alert("The academic year cannot be a span of two years or more.");
      endYearInput.value = "";
      return;
    }

    // Get form data
    const formData = new FormData(this);

    try {
      const response = await fetch("sql/add-record.php", {
        method: "POST",
        body: formData,
      });

      const result = await response.json();
      console.log(result);

      if (result.success) {
        window.location.href = "add-record-page.php";
      } else {
        alert("Error: " + result.error);
      }
    } catch (error) {
      console.error("Error submitting form:", error);
    }
  });
