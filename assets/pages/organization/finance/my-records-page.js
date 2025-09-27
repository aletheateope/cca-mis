import { generateFileName } from "../../../components/formatter/fileNameGenerator.js";
import { formatDate } from "../../../components/formatter/formatDate.js";
import { formatNumber } from "../../../components/formatter/formatNumber.js";
import { createNotyf } from "../../../components/alerts/notyf.js";
import { downloadElement } from "../../../components/captureElement.js";
import { initializeFancybox } from "../../../components/fancybox.js";

// FANCYBOX
initializeFancybox();

// SUCCESS MESSAGE
const submissionStatus = localStorage.getItem("submissionStatus");

if (submissionStatus === "success") {
  const notyf = createNotyf();
  notyf.success("Accomplishment added successfully.");

  localStorage.removeItem("submissionStatus");
}

// PAGE HIDE
window.addEventListener("pagehide", function () {
  navigator.sendBeacon("sql/month_unset.php");
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

// ADD RECORDMODAL
document.addEventListener("DOMContentLoaded", async function () {
  try {
    const response = await fetch("sql/warning.php");
    const data = await response.json();

    if (data.error) {
      console.error("Error:", data.error);
      return;
    }

    if (data.exists) {
      const warningElement = document.getElementById("addRecordModalBody");
      if (warningElement) {
        warningElement.insertAdjacentHTML(
          "afterbegin",
          `
        <div class="row warning">
          <div class="col-auto">
            <i class="bi bi-exclamation-triangle"></i>
          </div>
          <div class="col">
            <h6>
              Creating a new record makes the previous one uneditable. <br />
              Review and confirm before Proceeding
            </h6>
          </div>
        </div> `
        );
      } else {
        console.warn("Warning element not found in the DOM.");
      }
    }
  } catch (error) {
    console.error("Error fetching data:", error);
  }
});

const startYearInput = document.getElementById("inputStartYear");
const endYearInput = document.getElementById("inputEndYear");

// CHECK ACADEMIC YEAR
document.addEventListener("DOMContentLoaded", function () {
  const monthDropdown = document.getElementById("month");

  // SET SESSION MONTH
  const setSessionMonth = async (monthValue) => {
    let sessionMonth = new FormData();
    sessionMonth.append("month", monthValue);

    try {
      const sessionResponse = await fetch("sql/month_set.php", {
        method: "POST",
        body: sessionMonth,
      });

      const sessionData = await sessionResponse.json();

      if (sessionData.success) {
        // console.log("Session month set successfully.", monthValue);
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

      const formData = new FormData();
      formData.append("startYear", startYearInput.value);
      formData.append("endYear", endYearInput.value);

      const response = await fetch("sql/check_academic_year.php", {
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
      return; // Stop from submitting the form
    }

    if (endYear > startYear + 1) {
      alert("The academic year cannot be a span of two years or more.");
      endYearInput.value = "";
      return;
    }

    // CHECK MONTHS
    try {
      const checkResponse = await fetch("sql/check_months.php", {
        method: "POST",
        body: JSON.stringify({ startYear, endYear }),
        headers: { "Content-Type": "application/json" },
      });

      const checkResult = await checkResponse.json();

      if (checkResult.error) {
        alert("Error: " + checkResult.error);
        return;
      }

      if (checkResult.totalMonths >= 12) {
        alert("This academic year already has 12 months recorded.");
        return;
      }
    } catch (error) {
      console.error("Error checking records:", error);
      return;
    }

    // Get form data
    const formData = new FormData(this);

    try {
      const response = await fetch("sql/record_add.php", {
        method: "POST",
        body: formData,
      });

      const result = await response.json();

      if (result.success) {
        let encodedRef = btoa(result.ref);

        window.location.href = "add_record_page.php?ref=" + encodedRef;
      } else {
        alert(result.message);
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
const ctx = document.getElementById("horizontalWaterfall");

async function generateChart() {
  try {
    const response = await fetch("sql/latest_fs_flow.php");

    if (!response.ok) {
      console.error("Failed to fetch data");
      return;
    }

    const data = await response.json();

    if (data.success) {
      new Chart(ctx, {
        type: "bar",
        data: {
          labels: ["Credit", "Expense", "Final Balance"],
          datasets: [
            {
              label: "Hidden Base",
              data: [0, data.finalFunding, 0],
              backgroundColor: "rgba(0, 0, 0, 0)",
            },
            {
              label: "Values",
              data: [data.totalCredit, data.expenses, data.finalFunding],
              backgroundColor: [blue, red, green],
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
            tooltip: {
              callbacks: {
                label: function (context) {
                  let value = context.parsed.x;
                  return value + "%";
                },
              },
              filter: function (tooltipItem) {
                return tooltipItem.dataset.label !== "Hidden Base";
              },
            },
          },
          animation: {
            duration: 0,
          },
        },
      });

      document.getElementById("fundsLeft").textContent =
        data.finalFunding + "% of Funds Left";
    } else {
      console.error(data.message);
    }
  } catch (error) {
    console.error("Error fetching data:", error);
  }
}

generateChart();

// JSPDF
document.addEventListener("DOMContentLoaded", function () {
  const { jsPDF } = window.jspdf;

  document.querySelectorAll(".generatePDF").forEach((button) => {
    button.addEventListener("click", async function () {
      const academicYear = this.getAttribute("data-year");

      try {
        // Fetch organization name
        const orgResponse = await fetch(
          "/cca/assets/sql/priv_get_organization.php"
        );
        const orgData = await orgResponse.json();

        if (orgData.error) {
          alert("Failed to fetch organization name.");
          return;
        }

        const organizationID = orgData.organization_id;
        const organizationName = orgData.organization_name;

        // Fetch financial data
        const response = await fetch(
          `sql/fetch_statement_report.php?academicYear=${academicYear}`
        );

        const data = await response.json();

        if (data.error) {
          alert("Unauthorized access");
          return;
        }

        if (data.length === 0) {
          alert("No data found for the selected period.");
          return;
        }

        // JSPDF
        const doc = new jsPDF({
          orientation: "portrait",
          unit: "cm",
          format: "a4",
        });

        doc.setFontSize(12);
        doc.setFont("times", "normal");

        const pageWidth = doc.internal.pageSize.width;
        let pageHeight = doc.internal.pageSize.height;

        const margin = 1.5;

        function getOrganizationImage(organizationID) {
          const organizationImages = {
            1: "icon-blckmvmnt.png",
            2: "icon-chorale.png",
            3: "icon-dulangsining.png",
            4: "icon-euphoria.png",
            5: "icon-fdc.png",
            6: "icon-kultura_teknika.png",
          };

          return `/cca/assets/img/organization/${
            organizationImages[organizationID] ||
            "/cca/assets/img/icon-white.png"
          }`;
        }

        async function getBase64Image(url) {
          return new Promise((resolve) => {
            const img = new Image();
            img.crossOrigin = "Anonymous";
            img.src = url;
            img.onload = function () {
              const canvas = document.createElement("canvas");
              canvas.width = img.width;
              canvas.height = img.height;
              const ctx = canvas.getContext("2d");
              ctx.drawImage(img, 0, 0);

              const dataURL = canvas.toDataURL("image/png");

              resolve(dataURL);
            };
          });
        }

        const imagePaths = [
          "/cca/assets/img/osa/icon-osa.png",
          "/cca/assets/img/plmun.png",
          getOrganizationImage(organizationID),
          "/cca/assets/img/cca/icon-logo.png",
        ];

        const base64Images = await Promise.all(
          imagePaths.map((path) => getBase64Image(path))
        );

        // HEADER COLUMNS
        const columns = [
          { row: 0, col: 0, imgIndex: 0 },
          { row: 0, col: 1, imgIndex: 1 },
          { row: 1, col: 0, imgIndex: 2 },
          { row: 1, col: 1, imgIndex: 3 },
        ];

        function header() {
          doc.autoTable({
            head: [
              [
                { title: "", colSpan: 1 },
                { title: "", colSpan: 1 },
                { title: "", rowSpan: 2 },
              ],
              [
                { title: "", colSpan: 1 },
                { title: "", colSpan: 1 },
              ],
            ],
            theme: "plain",
            styles: {
              font: "times",
              valign: "middle",
              minCellHeight: 1.8,
            },
            didParseCell: function (data) {
              if (data.row.index === 0 && data.column.index === 2) {
                data.cell.text = [
                  "PAMANTASAN NG LUNGSOD NG MUNTINLUPA",
                  "OFFICE OF STUDENT AFFAIRS",
                  "CENTER FOR CULTURE AND THE ARTS",
                  "",
                  "FINANCIAL STATEMENT REPORT",
                  `AY ${academicYear}`,
                ];
                data.cell.styles.cellWidth = "auto";
              }

              columns.forEach(function (column) {
                if (
                  data.row.index === column.row &&
                  data.column.index === column.col
                ) {
                  data.cell.styles.cellWidth = 2;
                }
              });
            },
            didDrawCell: function (data) {
              const rowIndex = data.row.index;
              const colIndex = data.column.index;

              const images = base64Images;

              const imgCellWidth = data.cell.width;
              const imgCellHeight = data.cell.height;

              const imgSize = 1.5;

              const imgX = data.cell.x + (imgCellWidth - imgSize) / 2;
              const imgY = data.cell.y + (imgCellHeight - imgSize) / 2;

              columns.forEach((condition) => {
                if (rowIndex === condition.row && colIndex === condition.col) {
                  doc.addImage(
                    images[condition.imgIndex],
                    "PNG",
                    imgX,
                    imgY,
                    imgSize,
                    imgSize
                  );
                }
              });
            },
          });
        }

        function footer() {
          doc.text(
            `Name of the Performing Group: ${organizationName}`,
            margin,
            pageHeight - 2
          );
        }

        function pageNumber() {
          const pageCount = doc.internal.getNumberOfPages();

          for (let i = 1; i <= pageCount; i++) {
            doc.setPage(i);
            doc.text(`Page ${i} of ${pageCount}`, margin, pageHeight - 1.5);
          }
        }

        // TABLE DATA
        const tableData = data.map((item) => [
          item.year,
          item.month,
          formatNumber(item.starting_fund),
          formatNumber(item.total_credit),
          formatNumber(item.total_expenses),
          formatNumber(item.final_funding),
        ]);

        function generateTable() {
          doc.autoTable({
            head: [
              [
                "Year",
                "Month",
                "Beginning Balance",
                "Total Credit",
                "Total Expenses",
                "Ending Balance",
              ],
            ],
            body: tableData,
            theme: "plain",
            styles: {
              cellPadding: 0.25,
              font: "times",
              halign: "center",
              valign: "middle",
              lineWidth: 0.01,
              lineColor: [0, 0, 0],
            },
            didDrawPage: function (data) {
              header();
              footer();
            },
            margin: { top: 6 },
          });
          pageNumber();
        }

        // ---------------

        await generateTable();

        // ---------------
        const pdfBlob = doc.output("blob");
        const pdfURL = URL.createObjectURL(pdfBlob);

        const newTab = window.open(pdfURL);

        // doc.output("dataurlnewwindow");

        setTimeout(() => {
          URL.revokeObjectURL(pdfURL);
          console.log("Blob URL revoked:", pdfURL);
        }, 300000); // 5 minutes
      } catch (error) {
        console.error("Error fetching data:", error);
      }
    });
  });
});

// VIEW RECORD
const pageBody = document.querySelector(".page-body");

pageBody.addEventListener("click", async function (e) {
  const li = e.target.closest(".financial-records .list-group li");
  const generateIMG = e.target.closest(".generateIMG");
  const prevRecord = e.target.closest("#prevRecord");

  async function fetchRecord(publicKey) {
    try {
      const response = await fetch("sql/fetch_record.php", {
        method: "POST",
        body: JSON.stringify({ publicKey }),
      });

      if (!response.ok) {
        console.error("Error fetching record:", response.statusText);
        return;
      }

      const data = await response.json();

      if (data.success) {
        const date = formatDate(data.result.date_updated);

        const fields = {
          recordDate: date,
          recordMonthYear: `${data.result.month}, ${data.result.year}`,
          recordStartingFund: data.result.starting_fund,
          recordWeeklyContribution: data.result.weekly_contribution,
          recordInternalProjects: data.result.internal_projects,
          recordExternalProjects: data.result.external_projects,
          recordInternalInitiativeFunding: data.result.initiative_funding,
          recordDonationsSponsorships: data.result.donations_sponsorships,
          recordAdviserCredit: data.result.adviser_credit,
          recordCarriCredit: data.result.carri_credit,
          recordFinalFunding: data.result.final_funding,
        };

        for (const [id, value] of Object.entries(fields)) {
          const formatted = isNaN(value) ? value : formatNumber(value);
          document.getElementById(id).textContent = formatted;
        }

        const elementsMap = {
          ".recordTotalCredit": data.result.total_credit,
          ".recordTotalExpenses": data.result.total_expenses,
        };

        for (const [selector, value] of Object.entries(elementsMap)) {
          const formatted = formatNumber(value);
          document.querySelectorAll(selector).forEach((el) => {
            el.textContent = formatted;
          });
        }

        const galleryContainer = document.querySelector(".receipts-gallery");

        if (data.receipt && data.receipt.length > 0) {
          galleryContainer.innerHTML = "";

          if (galleryContainer.classList.contains("single-col")) {
            galleryContainer.classList.remove("single-col");
          }

          data.receipt.forEach((receipt) => {
            const galleryItemHTML = `
              <div class="gallery-item">
                <a
                  href="${receipt.path}"
                  data-fancybox="gallery"
                  data-caption="${receipt.file_name}"
                  data-download-filename="${generateFileName(10)}.jpg"
                >
                  <img src="${receipt.path}" alt="Receipt Image" />
                </a>
              </div>
            `;

            galleryContainer.innerHTML += galleryItemHTML;
          });
        } else {
          galleryContainer.classList.add("single-col");

          galleryContainer.innerHTML = "<p class='text-center'>Empty</p>";
        }
      } else {
        console.log(data.message);
      }
    } catch (error) {
      console.error("Error fetching record:", error);
    }
  }

  if (prevRecord) {
    const publicKey = prevRecord.dataset.id;

    fetchRecord(publicKey);
  }

  if (generateIMG) {
    console.log("generateIMG");
    const month = generateIMG.getAttribute("data-month");
    const year = generateIMG.getAttribute("data-year");

    try {
      const response = await fetch(
        `sql/fetch_statement.php?month=${month}&year=${year}`
      );

      const data = await response.json();

      if (!data.error) {
        document.getElementById("date").textContent = new Date(
          data.date_updated
        ).toLocaleDateString("en-US");
        document.querySelectorAll(".academicYear").forEach((el) => {
          el.textContent = data.academic_year;
        });

        const fieldMap = {
          startingFund: data.starting_fund,
          weeklyContribution: data.weekly_contribution,
          internalProjects: data.internal_projects,
          externalProjects: data.external_projects,
          internalInitiativeFunding: data.initiative_funding,
          donationsSponsorships: data.donations_sponsorships,
          adviserCredit: data.adviser_credit,
          carriCredit: data.carri_credit,
          finalFunding: data.final_funding,
        };

        for (const [id, value] of Object.entries(fieldMap)) {
          document.getElementById(id).textContent = formatNumber(value);
        }

        const multiFieldMap = {
          ".totalCredit": data.total_credit,
          ".totalExpenses": data.total_expenses,
        };

        for (const [selector, value] of Object.entries(multiFieldMap)) {
          document
            .querySelectorAll(selector)
            .forEach((el) => (el.textContent = formatNumber(value)));
        }
      } else {
        console.log("Error:", data.error);
      }
    } catch (error) {
      console.error("Error:", error);
    }
  }

  if (li) {
    const publicKey = li.dataset.id;

    fetchRecord(publicKey);
  }
});

downloadElement({
  triggerId: "download",
  captureId: "capture",
  generateFileName,
});
