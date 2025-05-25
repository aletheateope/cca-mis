import { initializeFancybox } from "../../../components/fancybox.js";
import { formatDate, formatTime } from "../../../components/formatDate.js";

// FANCYBOX
initializeFancybox();

const splide = new Splide(".splide", {
  height: "25rem",
  perPage: 3,
  gap: "1rem",
}).mount();

// ACCORDION
document.querySelectorAll(".accordion-collapse").forEach((collapse) => {
  collapse.addEventListener("show.bs.collapse", function (e) {
    const btn = e.target.closest(".delete-btn");

    if (this.closest(".accordion").id === "activityGalleryAccordion") {
      return;
    }

    document.querySelectorAll(".accordion-collapse").forEach((item) => {
      if (item !== this) {
        new bootstrap.Collapse(item, { toggle: false }).hide();
      }
    });
  });
});

// PREVENT ACTIVITY MODAL FROM OPENING
document
  .getElementById("viewActivityModal")
  .addEventListener("show.bs.modal", function (e) {
    var button = e.relatedTarget;
    if (button.classList.contains("generatePDF")) {
      e.preventDefault();
    }
  });

// GENERATE PDF
document.addEventListener("DOMContentLoaded", function () {
  const { jsPDF } = window.jspdf;

  document
    .querySelector(".page-body")
    .addEventListener("click", async function (event) {
      const generatePDFBtn = event.target.closest(".generatePDF");

      if (generatePDFBtn) {
        const month = generatePDFBtn.getAttribute("data-month");
        const year = generatePDFBtn.getAttribute("data-year");
        const organization = generatePDFBtn
          .closest("li")
          .getAttribute("data-id");

        try {
          // Fetch organization name
          const orgResponse = await fetch(
            "/cca/assets/sql/pub_get_organization.php",
            {
              method: "POST",
              headers: {
                "Content-Type": "application/json",
              },
              body: JSON.stringify({ public_key: organization }),
            }
          );

          const orgData = await orgResponse.json();

          if (orgData.error) {
            alert("Failed to fetch organization name.");
            return;
          }

          const organizationID = orgData.organization_id;
          const organizationName = orgData.organization_name;

          const response = await fetch("sql/fetch_accomplishment.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ month, year, organizationID }),
          });

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
            orientation: "landscape",
            unit: "cm",
            format: "a4",
          });

          doc.setFontSize(12);
          doc.setFont("times", "normal");

          const pageWidth = doc.internal.pageSize.width;
          const pageHeight = doc.internal.pageSize.height;

          const margin = 1.5;

          const monthNames = {
            1: "January",
            2: "February",
            3: "March",
            4: "April",
            5: "May",
            6: "June",
            7: "July",
            8: "August",
            9: "September",
            10: "October",
            11: "November",
            12: "December",
          };

          const monthName = monthNames[month] || "Unknown";

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
            getOrganizationImage(organizationID),
            "/cca/assets/img/cca/icon-logo.png",
            "/cca/assets/img/osa/icon-osa.png",
            "/cca/assets/img/plmun.png",
          ];

          const base64Images = await Promise.all(
            imagePaths.map((path) => getBase64Image(path))
          );

          function header() {
            const tableWidth = 20.5; // Table width sum of column widths

            // Calculate the left margin to center the table
            const leftMargin = (pageWidth - tableWidth) / 2;

            doc.autoTable({
              head: [["", "", "", "", ""]], // Header row with placeholders for images
              startY: 0.5,
              theme: "plain",
              styles: {
                halign: "center",
              },
              margin: { left: leftMargin },
              didParseCell: function (data) {
                if ([0, 1, 3, 4].includes(data.column.index)) {
                  switch (data.column.index) {
                    case 0:
                      data.cell.styles.cellWidth = 2.5;
                      break;
                    case 1:
                      data.cell.styles.cellWidth = 2.5;
                      break;
                    case 3:
                      data.cell.styles.cellWidth = 2.5;
                      break;
                    case 4:
                      data.cell.styles.cellWidth = 2.5;
                      break;
                  }
                }
                // Modify Column 3 (index 2) text
                if (data.row.index === 0 && data.column.index === 2) {
                  data.cell.text = [
                    "PAMANTASAN NG LUNGSOD NG MUNTINLUPA",
                    "OFFICE OF STUDENT AFFAIRS",
                    "CENTER FOR CULTURE AND THE ARTS",
                    "",
                    "MONTHLY ACCOMPLISHMENT REPORT",
                  ];
                  data.cell.styles.font = "times";
                  data.cell.styles.fontStyle = "bold";
                  data.cell.styles.fontSize = 12; // Keep font size at 12
                  data.cell.styles.valign = "middle"; // Center vertically
                  data.cell.styles.halign = "center"; // Center horizontally
                  data.cell.styles.cellWidth = 10.5;
                }
              },
              didDrawCell: function (data) {
                if (
                  data.row.index === 0 &&
                  [0, 1, 3, 4].includes(data.column.index)
                ) {
                  const imgIndex = [0, 1, 3, 4].indexOf(data.column.index);
                  if (base64Images[imgIndex]) {
                    const imgCellWidth = data.cell.width;
                    const imgCellHeight = data.cell.height;

                    const imgSize = imgIndex === 0 || imgIndex === 1 ? 1.9 : 2;

                    const imgX = data.cell.x + (imgCellWidth - imgSize) / 2; // Center horizontally
                    const imgY = data.cell.y + (imgCellHeight - imgSize) / 2; // Center vertically

                    doc.addImage(
                      base64Images[imgIndex],
                      "PNG",
                      imgX,
                      imgY,
                      imgSize,
                      imgSize
                    );
                  }
                }
              },
            });
          }

          function footer() {
            doc.text(
              `Name of the Performing Group: ${organizationName}`,
              margin,
              pageHeight - 1.5
            );
            doc.text(
              `Period of Accomplishment: ${monthName}, ${year}`,
              margin,
              pageHeight - 1
            );
          }

          function pageNumber() {
            const pageCount = doc.internal.getNumberOfPages();

            for (let i = 1; i <= pageCount; i++) {
              doc.setPage(i);
              doc.text(
                `Page ${i} of ${pageCount}`,
                pageWidth - margin,
                pageHeight - 1.5,
                {
                  align: "right",
                }
              );
            }
          }

          function formatDate(dateString) {
            const date = new Date(dateString); // Convert to Date object
            const months = [
              "January",
              "February",
              "March",
              "April",
              "May",
              "June",
              "July",
              "August",
              "September",
              "October",
              "November",
              "December",
            ];
            const month = months[date.getMonth()]; // Get full month name
            const day = date.getDate().toString().padStart(2, "0"); // Get day (pad with 0 if needed)
            const year = date.getFullYear(); // Get year
            return `${month} ${day}, ${year}`; // Return in Month DD, YYYY format
          }

          // TABLE DATA
          let tableData = data.map((item) => [
            item.title,
            formatDate(item.start_date),
            formatDate(item.end_date),
            item.target_participants +
              (item.target_participants > 1 ? " members of " : " member of ") +
              organizationName,
            item.actual_participants +
              (item.actual_participants > 1 ? " members of " : " member of ") +
              organizationName,
            item.budget_utilized,
            item.remark,
          ]);

          function generateTable() {
            const rowsPerPage = 11;
            let currentPage = 0;

            while (currentPage * rowsPerPage < tableData.length) {
              let start = currentPage * rowsPerPage;
              let end = start + rowsPerPage;
              let bodyData = tableData.slice(start, end);

              doc.autoTable({
                head: [
                  [
                    {
                      title: "Program/Project/Activity\nDescription",
                      rowSpan: 2,
                    },
                    { title: "Schedule of Implementation", colSpan: 2 },
                    { title: "Expected\nOutputs/Targets", rowSpan: 2 },
                    { title: "Actual Accomplishment", colSpan: 2 },
                    { title: "Remarks", rowSpan: 2 },
                  ],
                  [
                    "Start Date",
                    "Completion Date",
                    "Quantity",
                    "Budget Utilized",
                  ],
                ],
                body: bodyData,
                didDrawPage: function (data) {
                  header();
                  footer();
                },
                theme: "plain",
                styles: {
                  font: "times",
                  lineColor: [0, 0, 0],
                  lineWidth: 0.01,
                  halign: "center",
                  valign: "middle",
                },
                margin: { top: 3.5 },
              });

              currentPage++;

              if (end < tableData.length) {
                doc.addPage(); // Add new page for the next batch of rows
              }
            }
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
      }
    });
});

const pageBody = document.querySelector(".page-body");

const activityModal = document.getElementById("viewActivityModal");

const sliderContainer = activityModal.querySelector(".slider-container");
const sliderItems = sliderContainer.querySelectorAll(".slider-item");

function slideOne() {
  sliderContainer.style.transform = "translateX(0%)";
  const firstItemHeight = sliderItems[0].offsetHeight;
  sliderContainer.style.height = firstItemHeight + "px";
}

function slideTwo() {
  sliderContainer.style.transform = "translateX(-100%)";
  const secondItemHeight = sliderItems[1].offsetHeight;
  sliderContainer.style.height = secondItemHeight + "px";
}

slideOne();

// PAGE BODY
pageBody.addEventListener("click", async function (e) {
  const container = e.target.closest(".container");
  const accordion = e.target.closest(".accordion");

  const listItem = e.target.closest(".accordion-body li");

  const ul = document.querySelector(".view-activity-modal .modal-body ul");

  const title = activityModal.querySelector(".modal-title");

  if (listItem) {
    const organization = listItem.dataset.id;
    const month = accordion.dataset.month;
    const year = container.dataset.year;

    try {
      const response = await fetch("sql/modal_title.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ organization, month }),
      });

      const data = await response.json();

      if (data.success) {
        title.textContent = `${data.organization} - ${data.month}, ${year}`;
      }
    } catch (error) {
      console.error("Error fetching organization:", error);
    }

    try {
      const response = await fetch("sql/fetch_activity.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ month, year, organization }),
      });

      if (!response.ok) {
        throw new Error("Network response was not ok");
      }

      const data = await response.json();

      if (data.success) {
        const html = data.activities
          .map(
            (activity) => `
              <li class="list-group-item activity-list" data-id="${activity.public_key}">
                <div class="row">
                  <div class="col">
                    ${activity.title}
                  </div>
                  <div class="col-auto">
                    <i class="bi bi-chevron-right"></i>
                  </div>
                </div>
              </li>
            `
          )
          .join("");

        ul.innerHTML = html;

        activityModal.addEventListener(
          "shown.bs.modal",
          function () {
            requestAnimationFrame(() => {
              slideOne();
            });
          },
          {
            once: true,
          }
        );
      } else {
        console.log(data.message);
      }
    } catch (error) {
      console.error("Error fetching data:", error);
    }
  }
});

// ACTIVITY MODAL
activityModal.addEventListener("click", async function (e) {
  const list = e.target.closest(".activity-list");
  const goBack = e.target.closest("#goBack");

  // VIEW ACTIVITY DETAILS
  if (list) {
    const publicKey = list.dataset.id;

    try {
      const response = await fetch("sql/fetch_activity_details.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ publicKey }),
      });

      if (!response.ok) {
        throw new Error("Network response was not ok");
      }

      const data = await response.json();

      if (data.success) {
        const { start_date, end_date, start_time, end_time } = data.result;

        const formattedStartDate = formatDate(start_date);
        const formattedEndDate = formatDate(end_date);

        let formattedStartTime = start_time ? formatTime(start_time) : "";
        let formattedEndTime = end_time ? formatTime(end_time) : "";

        let dateString = "";

        // Case 1: start and end dates are the same
        if (start_date === end_date) {
          if (start_time && end_time) {
            // Same date, with time
            dateString = `${formattedStartDate} · ${formattedStartTime} - ${formattedEndTime}`;
          } else {
            // Same date, no time
            dateString = `${formattedStartDate}`;
          }
        } else {
          // Different dates
          if (start_time && end_time) {
            dateString = `${formattedStartDate} · ${formattedStartTime} - ${formattedEndDate} · ${formattedEndTime}`;
          } else {
            dateString = `${formattedStartDate} - ${formattedEndDate}`;
          }
        }

        const elementIds = {
          title: "activityTitle",
          description: "activityDescription",
          location: "activityLocation",
          date: "activityDate",

          targetParticipants: "activityTargetParticipants",
          actualParticipants: "activityParticipatingMembers",

          budgetUtilized: "activityBudgetUtilized",

          objectives: "activityObjectives",
          challengesSolutions: "activityChallengesSolutions",
          lessonLearned: "activityLessonLearned",
          suggestions: "activitySuggestions",
          remarks: "activityRemarks",
        };

        const elements = Object.fromEntries(
          Object.entries(elementIds).map(([key, id]) => [
            key,
            document.getElementById(id),
          ])
        );

        function getValueOrDefault(value) {
          return value === null ? "---" : value;
        }

        const actualParticipants = data.result.actual_participants;

        // Update element values
        elements.title.textContent = data.result.title;
        elements.description.textContent = data.result.description;
        elements.location.textContent = data.result.location;
        elements.date.textContent = dateString;

        elements.targetParticipants.textContent =
          data.result.target_participants;

        if (actualParticipants > 0) {
          elements.actualParticipants.innerHTML = `
            ${actualParticipants} <button class="no-style-btn view-list-btn">[View List]</button>
          `;
        } else {
          elements.actualParticipants.innerHTML = `
            ${actualParticipants}
          `;
        }

        elements.objectives.textContent = getValueOrDefault(
          data.result.objectives
        );
        elements.challengesSolutions.textContent = getValueOrDefault(
          data.result.challenges_solutions
        );
        elements.lessonLearned.textContent = getValueOrDefault(
          data.result.lesson_learned
        );
        elements.suggestions.textContent = getValueOrDefault(
          data.result.suggestions
        );

        elements.remarks.innerHTML =
          data.result.remarks === 1
            ? `<i class="bi bi-emoji-smile remark-type-one"></i> Accomplished`
            : `<i class="bi bi-emoji-frown remark-type-two"></i> Accomplished but did not meet the target number of members.`;

        elements.budgetUtilized.textContent = data.result.budget_utilized;

        const galleryContainer = document.querySelector(".activity-gallery ul");

        if (data.gallery && data.gallery.length > 0) {
          splide.destroy();

          const galleryHTML = data.gallery
            .map(
              (galleryPath) => `
                <li class="splide__slide">
                  <a href="${galleryPath}" data-fancybox="gallery">
                    <img src="${galleryPath}" alt="Activity Image" />
                  </a>
                </li>
              `
            )
            .join("");

          galleryContainer.innerHTML = galleryHTML;

          splide.mount();
        } else {
        }
      } else {
        console.log(data.message);
      }
    } catch (error) {
      console.error("Error fetching data:", error);
    }
    slideTwo();
  }

  if (goBack) {
    slideOne();
  }
});

activityModal.addEventListener("hidden.bs.modal", function () {
  slideOne();
});
