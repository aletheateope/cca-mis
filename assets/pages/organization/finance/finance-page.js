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
        label: "nyaw",
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
        reverse: true,
      },
      y: {},
    },
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
      legend: { display: false },
      tooltip: { enabled: false },
    },
  },
});
