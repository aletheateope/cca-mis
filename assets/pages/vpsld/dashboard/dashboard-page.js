const eventApproval = document.getElementById("chartEventApproval");
const totalEventsSubmitted = document.getElementById(
  "chartTotalEventsSubmitted"
);
const organizationEvents = document.getElementById("chartOrganizationEvents");

new Chart(eventApproval, {
  type: "bar",
  data: {
    labels: ["2021", "2022", "2023", "2024"],
    datasets: [
      {
        label: "Approved Events",
        data: [100, 80, 75, 120],
        backgroundColor: "rgb(34, 190, 190)",
        borderColor: "rgb(34, 190, 190)",
      },
      {
        label: "Rejected Events",
        data: [40, 60, 30, 50],
        backgroundColor: "rgb(255, 100, 100)",
        borderColor: "rgb(255, 100, 100)",
      },
    ],
  },
});

new Chart(totalEventsSubmitted, {
  type: "line",
  data: {
    labels: ["2021", "2022", "2023", "2024"],
    datasets: [
      {
        label: "Total Events Submitted",
        data: [140, 140, 105, 170],
        borderColor: "rgb(34, 190, 190)",
      },
    ],
  },
});

new Chart(organizationEvents, {
  type: "bar",
  data: {
    labels: ["2021", "2022", "2023", "2024"],
    datasets: [
      {
        label: "Blck Mvmnt",
        data: [18, 22, 19, 28],
        backgroundColor: "#003f5c",
        borderColor: "#003f5c",
      },
      {
        label: "Chorale",
        data: [29, 25, 17, 33],
        backgroundColor: "#444e86",
        borderColor: "#444e86",
      },
      {
        label: "Dulangsining",
        data: [24, 21, 16, 26],
        backgroundColor: "#955196",
        borderColor: "#955196",
      },
      {
        label: "Euphoria",
        data: [19, 23, 18, 27],
        backgroundColor: "#dd5182",
        borderColor: "#dd5182",
      },
      {
        label: "FDC",
        data: [22, 24, 17, 29],
        backgroundColor: "#ff6e54",
        borderColor: "#ff6e54",
      },
      {
        label: "Kultura Teknika",
        data: [28, 25, 18, 27],
        backgroundColor: "#ffa600",
        borderColor: "#ffa600",
      },
    ],
  },
  options: {
    plugins: {
      title: {
        display: true,
        text: "Organization Events",
        font: {
          size: 16,
        },
      },
    },
  },
});
