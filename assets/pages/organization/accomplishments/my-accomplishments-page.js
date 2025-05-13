import { createNotyf } from "../../../components/notyf.js";
import { initializeFancybox } from "../../../components/fancybox.js";

// SUCCESS MESSAGE
const submissionStatus = localStorage.getItem("submissionStatus");

if (submissionStatus === "success") {
  const notyf = createNotyf();
  notyf.success("Accomplishment added successfully.");

  localStorage.removeItem("submissionStatus");
}

// PREVENT ACTIVITY MODAL FROM OPENING
document
  .getElementById("viewActivityModal")
  .addEventListener("show.bs.modal", function (e) {
    var button = e.relatedTarget;
    if (
      button.classList.contains("edit-btn") ||
      button.classList.contains("delete-btn")
    ) {
      e.preventDefault();
    }
  });

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

// CLEAVE
var cleave = new Cleave("#inputYear", {
  date: true,
  datePattern: ["Y"],
});

// GET ACCOMPLISHMENT YEAR
document.addEventListener("DOMContentLoaded", function () {
  document.querySelectorAll("#addActivityButton").forEach((button) => {
    button.addEventListener("click", function () {
      let year = this.closest(".content")
        .querySelector("h2")
        .textContent.trim();
      document.getElementById("year").value = year;
    });
  });
});

// CREATE ACCOMPLISHMENT
$(document).ready(function () {
  $("#addAccomplishmentForm, #addActivityForm").on("submit", function (e) {
    e.preventDefault();

    const formData = new FormData(this);

    $.ajax({
      url: "sql/create_accomplishment.php",
      type: "POST",
      data: formData,
      contentType: false,
      processData: false,
      cache: false,
      dataType: "json",
      success: function (data) {
        if (data.success) {
          let encodedRef = btoa(data.ref);

          window.location.href = "add_activity_page.php?ref=" + encodedRef;
        } else {
          alert(
            "Failed to create accomplishment report: " +
              (data.error || "Unknown error")
          );
        }
      },
      error: function (xhr) {
        console.error("AJAX Error:", xhr.responseText);
        alert("Request failed. Check console for details.");
      },
    });
  });
});

// OPTION BAR
document.addEventListener("DOMContentLoaded", function () {
  document.querySelectorAll(".addEvent").forEach((button) => {
    button.addEventListener("click", function () {
      console.log("Add Event Clicked");
    });
  });
});

// GENERATE PDF
document.addEventListener("DOMContentLoaded", function () {
  const { jsPDF } = window.jspdf;

  document.querySelectorAll(".generatePDF").forEach((button) => {
    button.addEventListener("click", async function () {
      const month = this.getAttribute("data-month");
      const year = this.getAttribute("data-year");

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

        // Fetch accomplishment data
        const response = await fetch(
          `sql/fetch_accomplishment.php?month=${month}&year=${year}`
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
          orientation: "landscape",
          unit: "cm",
          format: "a4",
        });

        doc.setFontSize(12);
        doc.setFont("times", "normal");

        const pageWidth = doc.internal.pageSize.width;
        let pageHeight = doc.internal.pageSize.height;

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
    });
  });
});

tippy(".addEvent", {
  theme: "light",
  content: "Add Event",
  placement: "top",
});
tippy(".generatePDF", {
  theme: "light",
  content: "Generate Report",
  placement: "top",
});
tippy(".readEvents", {
  theme: "light",
  content: "Read Events",
  placement: "top",
});

// FANCYBOX
initializeFancybox();

const pageBody = document.querySelector(".page-body");

// VIEW ACTIVITY
pageBody.addEventListener("click", async function (e) {
  const li = e.target.closest(".accordion-accomplishments .accordion-body li");

  if (li) {
    const publicKey = li.dataset.id;

    try {
      const response = await fetch("sql/fetch_activity.php", {
        method: "POST",
        body: JSON.stringify({
          publicKey,
        }),
      });

      const data = await response.json();

      if (data.success) {
        const { start_date, end_date, start_time, end_time } = data.result;

        const formatDate = (dateStr) =>
          new Date(dateStr).toLocaleDateString("en-US", {
            year: "numeric",
            month: "long",
            day: "2-digit",
          });

        const formatTime = (timeStr) => {
          const [hour, minute] = timeStr.split(":");
          const date = new Date();
          date.setHours(+hour, +minute);
          return date.toLocaleTimeString([], {
            hour: "numeric",
            minute: "2-digit",
            hour12: true,
          });
        };

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

          objectives: "activityObjectives",
          challengesSolutions: "activityChallengesSolutions",
          lessonLearned: "activityLessonLearned",
          suggestions: "activitySuggestions",
          remarks: "activityRemarks",

          budgetUtilized: "activityBudgetUtilized",
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

        const galleryContainer = document.querySelector(".activity-gallery");

        if (data.gallery && data.gallery.length > 0) {
          galleryContainer.innerHTML = "";

          if (galleryContainer.classList.contains("single-col")) {
            galleryContainer.classList.remove("single-col");
          }

          data.gallery.forEach((galleryPath) => {
            const galleryItemHTML = `
              <div class="gallery-item">
                <a href="${galleryPath}" data-fancybox="gallery">
                  <img src="${galleryPath}" alt="Activity Image" />
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
      console.log(error);
    }

    const deleteBtn = e.target.closest(".delete-btn");
    const editBtn = e.target.closest(".edit-btn");

    if (deleteBtn) {
      console.log("delete-btn");
    }

    if (editBtn) {
      console.log("edit-btn");
    }
  }
});

// ACTIVITY GALLERY AUTO SCROLL
document.addEventListener("DOMContentLoaded", function () {
  const collapseGallery = document.getElementById("collapseGallery");

  collapseGallery.addEventListener("shown.bs.collapse", function () {
    collapseGallery.scrollIntoView({ behavior: "smooth", block: "start" });
  });
});
