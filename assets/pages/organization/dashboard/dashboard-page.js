import { generateFileName } from "../../../components/fileNameGenerator.js";

import {
  onShow,
  onHide,
} from "../../../components/sweetalert2/alertAnimation.js";

const getColor = (varName) =>
  getComputedStyle(document.documentElement).getPropertyValue(varName);

const primaryText = getColor("--primary-text");

const green = getColor("--success");
const blue = getColor("--info");
const yellow = getColor("--warning");
const red = getColor("--danger");

const pink = getColor("--pink");
const purple = getColor("--purple");

// CHART.JS
const stateChart = document.getElementById("memberStateChart");
const statusChart = document.getElementById("memberStatusChart");
const genderChart = document.getElementById("memberGenderChart");
const collegeChart = document.getElementById("memberCollegeChart");

Chart.register(ChartDataLabels);

let stateChartInstance;
let statusChartInstance;
let genderChartInstance;
let collegeChartInstance;

let reportChartInstance;

async function generateStateChart() {
  try {
    const response = await fetch("sql/display_lm_state.php");

    if (!response.ok) {
      console.error("Failed to fetch data");
      return;
    }

    const data = await response.json();

    if (data.success) {
      // State Chart
      stateChartInstance = new Chart(stateChart, {
        type: "bar",
        data: {
          labels: ["Active", "Inactive", "Exited", "Terminated"],
          datasets: [
            {
              label: "Total Number",
              data: [
                data.result.active_count,
                data.result.inactive_count,
                data.result.exited_count,
                data.result.terminated_count,
              ],
              backgroundColor: [green, blue, yellow, red],
            },
          ],
        },
        options: {
          plugins: {
            title: {
              display: true,
              text: "Member State",
              padding: {
                bottom: 24,
              },
              font: {
                size: 16,
              },
            },
            legend: {
              display: false,
            },
            datalabels: {
              color: "white",
              font: {
                size: 14,
              },
            },
          },
          maintainAspectRatio: false,
        },
      });
    } else {
      console.log(data.message);
    }
  } catch (error) {
    console.error("Error generating state chart:", error);
  }
}

async function generateStatusChart() {
  try {
    const response = await fetch("sql/display_lm_status.php");

    if (!response.ok) {
      console.error("Failed to fetch data");
      return;
    }

    const data = await response.json();

    if (data.success) {
      // Status Chart
      statusChartInstance = new Chart(statusChart, {
        type: "pie",
        data: {
          labels: ["Trainee", "Junior", "Senior", "Alumni"],
          datasets: [
            {
              label: "Total Number",
              data: [
                data.result.trainee_count,
                data.result.junior_count,
                data.result.senior_count,
                data.result.alumni_count,
              ],
              backgroundColor: [green, blue, yellow, purple],
            },
          ],
        },
        options: {
          plugins: {
            title: {
              display: true,
              font: {
                size: 16,
              },
              text: "Member Status",
            },
            legend: {
              display: true,
              position: "bottom",
              labels: {
                font: {
                  size: 12,
                },
              },
            },
            datalabels: {
              color: "white",
              font: {
                size: 18,
              },
              formatter: (value, context) => {
                const data = context.chart.data.datasets[0].data;
                const total = data.reduce((sum, val) => sum + val, 0);
                const percentage = ((value / total) * 100).toFixed(1);
                return percentage + "%";
              },
            },
          },
        },
      });
    } else {
      console.log(data.message);
    }
  } catch (error) {
    console.error("Error generating status chart:", error);
  }
}

async function generateGenderChart() {
  try {
    const response = await fetch("sql/display_lm_gender.php");

    if (!response.ok) {
      console.error("Failed to fetch data");
      return;
    }

    const data = await response.json();

    if (data.success) {
      // Gender Chart
      genderChartInstance = new Chart(genderChart, {
        type: "pie",
        data: {
          labels: ["Male", "Female", "LGBTQ"],
          datasets: [
            {
              label: "Total Number",
              data: [data.result.male, data.result.female, data.result.lgbt],
              backgroundColor: [blue, pink, purple],
            },
          ],
        },
        options: {
          plugins: {
            title: {
              display: true,
              font: {
                size: 16,
              },
              text: "Gender",
            },
            legend: {
              display: true,
              position: "bottom",
              labels: {
                font: {
                  size: 12,
                },
              },
            },
            datalabels: {
              color: "white",
              font: {
                size: 18,
              },
              formatter: (value, context) => {
                const data = context.chart.data.datasets[0].data;
                const total = data.reduce((sum, val) => sum + val, 0);
                const percentage = ((value / total) * 100).toFixed(1);
                return percentage + "%";
              },
            },
          },
        },
      });
    } else {
      console.log(data.message);
    }
  } catch (error) {
    console.error("Error generating gender chart:", error);
  }
}

async function generateCollegeChart() {
  try {
    const response = await fetch("sql/display_lm_college.php");

    if (!response.ok) {
      console.error("Failed to fetch data");
      return;
    }

    const data = await response.json();

    if (data.success) {
      const counts = data.data.map((row) => parseInt(row.member_count));
      const colleges = data.data.map((row) => row.college);

      // College Chart
      collegeChartInstance = new Chart(collegeChart, {
        type: "bar",
        data: {
          labels: colleges,
          datasets: [
            {
              label: "Total Number",
              data: counts,
            },
          ],
        },
        options: {
          indexAxis: "y",
          plugins: {
            title: {
              display: true,
              text: "College",
              padding: {
                bottom: 24,
              },
              font: {
                size: 16,
              },
            },
            legend: {
              display: false,
            },
          },
        },
      });
    } else {
      console.log(data.message);
    }
  } catch (error) {
    console.error("Error generating college chart:", error);
  }
}

generateStateChart();
generateStatusChart();
generateGenderChart();
generateCollegeChart();

// REPORT CHART
function getClonedConfig(chartInstance, onCompleteCallback) {
  const config = chartInstance.config;

  const clonedConfig = {
    type: config.type,
    data: JSON.parse(JSON.stringify(config.data)),
    options: JSON.parse(JSON.stringify(config.options)),
    plugins: config.plugins || [],
  };

  // Add formatter if needed
  if (config.options?.plugins?.datalabels?.formatter) {
    clonedConfig.options.plugins.datalabels = {
      ...clonedConfig.options.plugins.datalabels,
      formatter: config.options.plugins.datalabels.formatter,
    };
  }

  // Set title font size
  if (clonedConfig.options?.plugins?.title?.font) {
    clonedConfig.options.plugins.title.font.size = 22;
  }

  // ✅ Add the onComplete animation callback
  clonedConfig.options.animation = {
    ...clonedConfig.options.animation,
    onComplete: onCompleteCallback,
  };

  return clonedConfig;
}

function renderReportChart(sourceChartInstance, onRendered) {
  const canvas = document.getElementById("reportChart");
  const ctx = canvas.getContext("2d");

  if (reportChartInstance) {
    reportChartInstance.destroy();
  }

  const clonedConfig = getClonedConfig(sourceChartInstance, onRendered);

  const type = clonedConfig.type;

  if (type === "bar" && clonedConfig.options?.indexAxis !== "y") {
    canvas.parentElement.style.height = "450px";
    canvas.style.height = "100%";
  } else {
    canvas.parentElement.style.height = "auto";
    canvas.style.height = "";
  }

  reportChartInstance = new Chart(ctx, clonedConfig);
}

function generateReportTable(labels, data) {
  const tableContainer = document.getElementById("reportTable");

  const total = data.reduce((sum, val) => sum + val, 0);

  tableContainer.innerHTML = "";

  const tableHTML = `
    <table class="table">
      <thead>
        <tr>
          <th>Category</th>
          <th class="text-center">Count</th>
        </tr>
      </thead>
      <tbody>
        ${labels
          .map(
            (label, index) => `
          <tr>
            <td>${label}</td>
            <td class="text-center">${data[index]}</td>
          </tr>
        `
          )
          .join("")}
        <tr class="fw-bold">
          <td>Total Of</td>
          <td class="text-center">${total}</td>
        </tr>
      </tbody>
    </table>
  `;

  // Insert the generated table HTML into the container
  tableContainer.innerHTML = tableHTML;
}

function displayChartDataInTable(chartInstance) {
  const labels = chartInstance.data.labels;
  const data = chartInstance.data.datasets[0].data;

  // Generate and display the table
  generateReportTable(labels, data);
}

// HTML2CANVAS DOWNLOAD
function triggerDownload(dataURL, filename) {
  const link = document.createElement("a");
  link.href = dataURL;
  link.download = filename;
  document.body.appendChild(link);
  link.click();
  document.body.removeChild(link);
}

const container = document.querySelectorAll(".page-body .container");

container.forEach((element) => {
  element.addEventListener("click", function (e) {
    const saveReportBtn = e.target.closest(".save-report-btn");

    if (saveReportBtn) {
      let chartToCopy = null;

      if (element.querySelector("#memberStateChart")) {
        chartToCopy = stateChartInstance;
      } else if (element.querySelector("#memberStatusChart")) {
        chartToCopy = statusChartInstance;
      } else if (element.querySelector("#memberGenderChart")) {
        chartToCopy = genderChartInstance;
      } else if (element.querySelector("#memberCollegeChart")) {
        chartToCopy = collegeChartInstance;
      }

      if (chartToCopy) {
        Swal.fire({
          title: "Processing...",
          text: "Please wait while we generate the report.",
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

        renderReportChart(chartToCopy, async () => {
          displayChartDataInTable(chartToCopy);

          const chartElement = document.getElementById("captureChart");
          const tableElement = document.getElementById("reportTable");

          try {
            const [chartCanvas, tableCanvas] = await Promise.all([
              html2canvas(chartElement, { scale: 2 }),
              html2canvas(tableElement, { scale: 2 }),
            ]);

            const chartImage = chartCanvas.toDataURL("image/jpeg", 0.95);
            const tableImage = tableCanvas.toDataURL("image/jpeg", 0.95);

            triggerDownload(chartImage, `${generateFileName(10)}.jpg`);
            triggerDownload(tableImage, `${generateFileName(10)}.jpg`);
          } catch (error) {
            console.error("Error capturing images:", error);
            Swal.fire("Error", "Failed to generate report images.", "error");
            return;
          }

          // Close the loading modal after everything is done
          Swal.close();
        });
      }
    }
  });
});
