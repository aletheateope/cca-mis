// CLEAVE
var cleaveStartYear = new Cleave("#inputStartYear", {
  date: true,
  datePattern: ["Y"],
});

var cleaveEndYear = new Cleave("#inputEndYear", {
  date: true,
  datePattern: ["Y"],
});

document
  .getElementById("addRecordForm")
  .addEventListener("submit", async function (event) {
    event.preventDefault(); // Prevent default form submission

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
