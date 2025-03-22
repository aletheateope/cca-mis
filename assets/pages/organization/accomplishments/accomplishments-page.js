document.querySelectorAll(".accordion-collapse").forEach((collapse) => {
  collapse.addEventListener("show.bs.collapse", function () {
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
          window.location.href =
            "add-activity-page.php?report_id=" + data.report_id;
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
      let month = this.getAttribute("data-month");
      let year = this.getAttribute("data-year");

      try {
        // Fetch organization name
        const orgResponse = await fetch("sql/get-organization.php");
        const orgData = await orgResponse.json();

        if (orgData.error) {
          alert("Failed to fetch organization name.");
          return;
        }
        const organizationID = orgData.organization_id;
        const organizationName = orgData.organization_name;

        // Fetch accomplishment data
        const response = await fetch(
          `sql/fetch-accomplishment.php?month=${month}&year=${year}`
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
        let pageHieght = doc.internal.pageSize.height;

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
            6: "icon-kultura-teknika.png",
          };

          return `/cca/assets/img/organization/${
            organizationImages[organizationID] ||
            "/cca/assets/img/icon-white.png"
          }`;
        }

        async function getBase64Image(url) {
          return new Promise((resolve) => {
            const img = new Image();
            img.crossOrigin = "Anonymous"; // Prevents CORS issues
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
                    data.cell.styles.cellWidth = 2.5; // Set width for column 0
                    break;
                  case 1:
                    data.cell.styles.cellWidth = 2.5; // Set width for column 1
                    break;
                  case 3:
                    data.cell.styles.cellWidth = 2.5; // Set width for column 3
                    break;
                  case 4:
                    data.cell.styles.cellWidth = 2.5; // Set width for column 4
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
                  const imCellWidth = data.cell.width;
                  const imgCellHeight = data.cell.height;

                  const imgSize = imgIndex === 0 || imgIndex === 1 ? 1.9 : 2;

                  const imgX = data.cell.x + (imCellWidth - imgSize) / 2; // Center horizontally
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
            pageHieght - 1.5
          );
          doc.text(
            `Period of Accomplishment: ${monthName}, ${year}`,
            margin,
            pageHieght - 1
          );
        }

        let tableData = data.map((item) => [
          item.title,
          item.start_date,
          item.end_date,
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
                lineColor: [0, 0, 0], // Black border color
                lineWidth: 0.01, // Border width
                halign: "center", // Centering text horizontally
                valign: "middle",
              },
              margin: { top: 3.5 },
            });

            currentPage++;

            if (end < tableData.length) {
              doc.addPage(); // Add new page for the next batch of rows
            }
          }
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
