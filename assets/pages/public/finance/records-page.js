import { generateFileName } from "../../../components/formatter/fileNameGenerator.js";
import { formatDate } from "../../../components/formatter/formatDate.js";
import { formatNumber } from "../../../components/formatter/formatNumber.js";
import { initializeFancybox } from "../../../components/fancybox.js";
import { downloadElement } from "../../../components/captureElement.js";

// FANCYBOX
initializeFancybox();

// ACCORDION
document.querySelectorAll(".accordion-collapse").forEach((collapse) => {
  collapse.addEventListener("show.bs.collapse", function () {
    document.querySelectorAll(".accordion-collapse").forEach((item) => {
      if (item !== this) {
        new bootstrap.Collapse(item, { toggle: false }).hide();
      }
    });
  });
});

// ORGANIZATION LIST (REPORT)
document.addEventListener("DOMContentLoaded", function () {
  const academicYearSelect = document.getElementById("selectAcademicYear");
  const orgList = document.querySelector(".organization-list");

  academicYearSelect.addEventListener("change", async function () {
    const selectedYear = this.value;
    try {
      const response = await fetch("sql/report.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ academic_year: selectedYear }),
      });

      if (!response.ok) {
        throw new Error(`HTTP error! Status: ${response.status}`);
      }

      const data = await response.json();

      orgList.innerHTML = "";

      if (data.success) {
        data.organizations.forEach((org, index) => {
          const orgId = `organization-${index + 1}`;

          const li = document.createElement("li");
          li.className = "list-group-item";

          li.innerHTML = `
            <div class="form-check">
              <label class="form-check-label" for="${orgId}">
                ${org.name}
              </label>
              <input
                class="form-check-input organization-checkbox"
                type="checkbox"
                value="${org.public_key}"
                id="${orgId}"
              />
            </div>
          `;
          orgList.appendChild(li);
        });
      } else {
        alert(data.message);
        orgList.innerHTML =
          '<li class="list-group-item">No organizations found for the selected academic year.</li>';
      }
    } catch (error) {
      console.error("Error fetching data:", error);
    }
  });
});

// JSPDF
document.addEventListener("DOMContentLoaded", function () {
  const { jsPDF } = window.jspdf;

  document
    .getElementById("generatePDF")
    .addEventListener("click", async function () {
      const academicYear = document.getElementById("selectAcademicYear").value;
      const selectedOrganizations = document.querySelectorAll(
        ".organization-checkbox:checked"
      );

      for (const org of selectedOrganizations) {
        const publicKey = org.value;

        try {
          const orgResponse = await fetch(
            "/cca/assets/sql/pub_get_organization.php",
            {
              method: "POST",
              headers: { "Content-Type": "application/json" },
              body: JSON.stringify({ public_key: publicKey }),
            }
          );

          const orgData = await orgResponse.json();

          if (orgData.error) {
            alert(orgData.error);
            continue;
          }

          const organizationID = orgData.organization_id;
          const organizationName = orgData.organization_name;

          const response = await fetch("sql/fetch_statement_report.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ academicYear, organizationID }),
          });

          const data = await response.json();

          if (data.error) {
            alert(data.error);
            continue;
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
                  if (
                    rowIndex === condition.row &&
                    colIndex === condition.col
                  ) {
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
          const tableData = data.data.map((item) => [
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
          const filename = `${organizationName.replace(
            /\s+/g,
            "_"
          )}_${academicYear}.pdf`;
          doc.save(filename);
        } catch (error) {
          console.error("Error fetching data:", error);
        }
      }
    });
});

// FINANCIAL STATEMENT (SUMMARY)
document
  .querySelector(".page-body")
  .addEventListener("click", async function (e) {
    const accordion = e.target.closest(".accordion-finance");
    const li = e.target.closest(".accordion-finance .accordion-body li");
    const generateBtn = e.target.closest(".fetchStatementBtn");

    if (li) {
      const publicKey = li.getAttribute("data-id");
      const year = accordion.getAttribute("data-year");
      const month = accordion.getAttribute("data-month");

      try {
        const response = await fetch("sql/fetch_record.php", {
          method: "POST",
          body: JSON.stringify({ publicKey, year, month }),
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

    if (generateBtn) {
      const year = accordion.getAttribute("data-year");
      const month = accordion.getAttribute("data-month");
      const organization = li.getAttribute("data-id");

      try {
        const response = await fetch("sql/fetch_financial_statement.php", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({ year, month, organization }),
        });

        if (!response.ok) {
          throw new Error(`HTTP error! Status: ${response.status}`);
        }

        const result = await response.json();

        if (result.success) {
          const fields = {
            date: new Date(result.data.date_updated).toLocaleDateString(
              "en-US"
            ),
            startingFund: result.data.starting_fund,
            weeklyContribution: result.data.weekly_contribution,
            internalProjects: result.data.internal_projects,
            externalProjects: result.data.external_projects,
            internalInitiativeFunding: result.data.initiative_funding,
            donationsSponsorships: result.data.donations_sponsorships,
            adviserCredit: result.data.adviser_credit,
            carriCredit: result.data.carri_credit,
            finalFunding: result.data.final_funding,
          };

          for (const [id, value] of Object.entries(fields)) {
            const formatted = isNaN(value) ? value : formatNumber(value);
            const el = document.getElementById(id);
            if (el) el.textContent = formatted;
          }

          document.querySelectorAll(".academicYear").forEach((el) => {
            el.textContent = result.data.academic_year;
          });

          document.querySelectorAll(".totalCredit").forEach((el) => {
            el.textContent = formatNumber(result.data.total_credit);
          });

          document.querySelectorAll(".totalExpenses").forEach((el) => {
            el.textContent = formatNumber(result.data.total_expenses);
          });
        } else {
          alert(result.message);
        }
      } catch (error) {
        console.error("Error fetching data:", error);
      }
    }
  });

downloadElement({ triggerId: "download", captureId: "capture" });
