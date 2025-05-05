let confirmedSubmit = false;

// RELOAD PAGE WARNING
function beforeUnloadHandler(event) {
  event.preventDefault();
  event.returnValue = "Changes you made may not be saved.";
}

window.addEventListener("beforeunload", beforeUnloadHandler);

window.addEventListener("pagehide", function () {
  if (!confirmedSubmit) {
    navigator.sendBeacon("sql/kill_session.php");
  }
});

// DISABLE WARNING ON SUBMIT
document.querySelector("form").addEventListener("submit", function () {
  window.removeEventListener("beforeunload", beforeUnloadHandler);
});

if (window.performance.getEntriesByType("navigation")[0]?.type === "reload") {
  window.location.href = "my_accomplishments_page.php";
}

document
  .getElementById("selectEvent")
  .addEventListener("change", async function () {
    const public_key = this.value;

    if (public_key !== "0") {
      try {
        const response = await fetch(
          "sql/fetch_event_details.php?public_key=" + public_key
        );
        const data = await response.json();

        if (data.success) {
          const fields = [
            { id: "inputTitle", eventKey: "title" },
            { id: "inputDescription", eventKey: "description" },
            { id: "inputLocation", eventKey: "location" },
            { id: "inputStartDate", eventKey: "start" },
            { id: "inputEndDate", eventKey: "end" },
          ];

          fields.forEach((field) => {
            const inputElement = document.getElementById(field.id);
            inputElement.value = data.event[field.eventKey];
            inputElement.disabled = true;
          });
        } else {
          alert("Error: " + data.message);
        }
      } catch (error) {
        console.error("There was an error with the fetch operation:", error);
      }
    } else {
      const fields = [
        "inputTitle",
        "inputDescription",
        "inputLocation",
        "inputStartDate",
        "inputEndDate",
      ];

      fields.forEach((field) => {
        const inputElement = document.getElementById(field);
        inputElement.value = "";
        inputElement.disabled = false;
      });
    }
  });

let selectedFiles = [];

// SPLIDE
document.addEventListener("DOMContentLoaded", function () {
  var splide = new Splide(".splide", {
    type: "slide",
    autoWidth: true,
    height: "15rem",
    focus: "center",
    perPage: 3,
    gap: "1rem",
    pagination: true,
  });
  splide.mount();

  // INPUT IMAGE FUNCTIONALITY
  const inputImageButton = document.getElementById("inputImageButton");
  const inputImage = document.getElementById("storeImgGallery");
  const list = document.querySelector(".splide__list");

  // Button click triggers file input
  inputImageButton.addEventListener("click", function (event) {
    event.preventDefault();
    inputImage.click();
  });

  // Display Selected File
  inputImage.addEventListener("change", function (event) {
    const files = Array.from(event.target.files);

    files.forEach((file) => {
      if (
        !selectedFiles.some(
          (f) => f.name === file.name && f.lastModified === file.lastModified
        )
      ) {
        selectedFiles.push(file);

        if (file.type.startsWith("image/")) {
          const reader = new FileReader();
          reader.onload = function (e) {
            const li = document.createElement("li");
            li.classList.add("splide__slide");

            li.innerHTML = `
              <img src="${e.target.result}" alt="Selected Image">
              <div class="image-overlay">
                  <button type="button" class="remove-img-btn no-style-btn">Remove Image</button>
              </div>
            `;
            li.setAttribute("data-file-name", file.name);
            list.appendChild(li);

            splide.add(li);
          };
          reader.readAsDataURL(file);
        }
      }
    });
  });

  list.addEventListener("click", function (event) {
    if (event.target.classList.contains("remove-img-btn")) {
      const li = event.target.closest("li");
      const fileName = li.getAttribute("data-file-name");

      const fileIndex = selectedFiles.findIndex(
        (file) => file.name === fileName
      );
      if (fileIndex !== -1) {
        selectedFiles.splice(fileIndex, 1);
      }

      splide.remove(li);
      li.remove();
      splide.refresh();
    }
  });
});

// CLEAVE
var cleave = new Cleave("#inputBudgetUtilized", {
  numeral: true,
  numeralThousandsGroupStyle: "thousand",
});

// ORGANIZE CHECKED AND UNCHECKED ITEMS IN PARTICIPANTS TABLE
let recognitionCounter = 1;

$(document).ready(function () {
  let membersContainer = $("#membersContainer");

  $(".member").each(function (index) {
    $(this).data("original-index", index);
  });

  $(".member-checkbox").on("change", function () {
    sortMembers();
  });

  function sortMembers() {
    let checkedMembers = $(".member-checkbox:checked").closest(".member").get();
    let uncheckedMembers = $(".member-checkbox:not(:checked)")
      .closest(".member")
      .get();

    checkedMembers.sort((a, b) =>
      $(a)
        .find("label")
        .text()
        .trim()
        .localeCompare($(b).find("label").text().trim())
    );

    uncheckedMembers.sort(
      (a, b) => $(a).data("original-index") - $(b).data("original-index")
    );

    membersContainer.append([...checkedMembers, ...uncheckedMembers]);
  }
});

// TOGGLE ADD RECOGNITION
$(document).ready(function () {
  // Show add recognition button only if checkbox is checked
  $(".member-checkbox").on("change", function () {
    const member = $(this).closest(".member");
    const addRecognitionBtn = member.find(".add-recognition");
    const labelName = member.find(".form-check label").text();

    addRecognitionBtn.toggle(this.checked);

    // If unchecked, remove any existing recognition form related to this participant
    if (!this.checked) {
      $(".recognition-form").each(function () {
        if ($(this).find("label").text() === labelName) {
          $(this).remove();
        }
      });

      // If there are no more recognition forms, hide the container
      if ($(".recognition-form").length === 0) {
        $(".recognition-container").hide();
      }

      // Remove empty row-gap if it exists
      $(".recognition-row").each(function () {
        if ($(this).children(".recognition-form").length === 0) {
          $(this).remove();
        }
      });
    }
  });

  // When add recognition button is clicked
  $(document).on("click", ".add-recognition", function () {
    var $checkbox = $(this)
      .siblings(".form-check")
      .find("input.member-checkbox");
    var participantId = $checkbox.attr("id"); // e.g., "participant-1"
    var labelName = $(this).siblings(".form-check").find("label").text(); // Get participant name
    var uniqueId = `recognition-${recognitionCounter++}`;

    // Create a new recognition form
    var newForm = `
            <div class="col recognition-form">
                <div class="row">
                    <div class="col">
                        <label for="${uniqueId}" class="form-label">${labelName}</label>
                        <div class="remove-recognition-btn"><i class="bi bi-x"></i></div>
                    </div>
                </div>
                <input type="text" name="recognition[]" class="form-control" id="${uniqueId}" data-from="${participantId}">
            </div>
        `;

    // Find the last row-gap inside the recognition-container
    var lastRow = $(".recognition-container .recognition-row").last();

    // If no row exists or the last row has 2 forms, create a new row inside .content
    if (
      lastRow.length === 0 ||
      lastRow.children(".recognition-form").length >= 2
    ) {
      lastRow = $('<div class="row row-gap recognition-row"></div>');
      $(".recognition-container > .col").append(lastRow);
    }

    // Append the new form to the last row-gap
    lastRow.append(newForm);

    // Show the recognition container
    $(".recognition-container").show();

    // Destroy previous tooltips to prevent duplication
    $(".remove-recognition-btn").each(function () {
      if (this._tippy) {
        this._tippy.destroy();
      }
    });

    // Reinitialize tippy
    tippy(".remove-recognition-btn", {
      content: "Remove",
      theme: "light",
      placement: "top",
    });
  });

  // Remove recognition form when clicking remove button
  $(document).on("click", ".remove-recognition-btn", function () {
    var formToRemove = $(this).closest(".recognition-form");
    var parentRow = formToRemove.closest(".recognition-row");

    formToRemove.remove(); // Remove the specific form

    // If the row is empty after removal, remove the row
    if (parentRow.children(".recognition-form").length === 0) {
      parentRow.remove();
    }

    // Hide the recognition container if no forms exist
    if ($(".recognition-form").length === 0) {
      $(".recognition-container").hide();
    }
  });
});

// MEMBER SELECTION
document.addEventListener("DOMContentLoaded", function () {
  const checkboxes = document.querySelectorAll(".member-checkbox");
  const countCheckedMembers = document.getElementById("inputMembersAttended");
  const selectAllCheckbox = document.getElementById("checkboxSelectAll");

  function updateCount() {
    const checkedCount = document.querySelectorAll(
      ".member-checkbox:checked"
    ).length;

    if (checkedCount > 0) {
      countCheckedMembers.value = checkedCount;
    } else {
      countCheckedMembers.value = "";
      countCheckedMembers.placeholder =
        "Select participants below to start counting...";
    }

    // If all checkboxes are selected, check "Select All", otherwise uncheck it
    selectAllCheckbox.checked = checkedCount === checkboxes.length;
  }

  // Select All Functionality
  selectAllCheckbox.addEventListener("change", function () {
    checkboxes.forEach((checkbox) => {
      checkbox.checked = this.checked;
      $(checkbox).trigger("change"); // Trigger change event to show/hide button
    });
    updateCount(); // Update count after selecting all
  });

  // Individual Checkbox Change
  checkboxes.forEach((checkbox) =>
    checkbox.addEventListener("change", updateCount)
  );
});

// TIPPY
tippy("#addRecognition", {
  content: "Add Recognition",
  theme: "light",
  placement: "top",
});

// SUBMIT ACTIVITY
document
  .getElementById("submitActivityForm")
  .addEventListener("submit", async function (event) {
    event.preventDefault();

    const formData = new FormData(this);

    formData.delete("activity_gallery[]");

    selectedFiles.forEach((file) => {
      formData.append("activity_gallery[]", file);
    });

    document
      .querySelectorAll(".member-checkbox:checked")
      .forEach((checkbox) => {
        const public_keys = checkbox.value;
        const participantId = checkbox.id;
        formData.append("public_keys[]", public_keys);

        // Find recognition inputs with matching label name
        document.querySelectorAll(".recognition-form").forEach((form) => {
          const input = form.querySelector('input[name="recognition[]"]');

          if (!input) {
            console.log("recognition input not found");
            return;
          }
          const recognitionText = input.value.trim();
          const recognitionParticipantId = input.getAttribute("data-from");

          if (
            recognitionParticipantId === participantId &&
            recognitionText !== ""
          ) {
            // Append the recognition value and its corresponding public key to formData
            formData.append(`recognition[${public_keys}][]`, recognitionText);
            console.log(
              `Recognition for ${public_keys} (Participant ID: ${participantId}): ${recognitionText}`
            );
          }
        });
      });

    try {
      const response = await fetch("sql/submit_activity.php", {
        method: "POST",
        body: formData,
      });

      if (!response.ok) {
        throw new Error(`HTTP error! Status: ${response.status}`);
      }

      const result = await response.json();

      if (result.success) {
        confirmedSubmit = true;
        localStorage.setItem("submissionStatus", "success");
        window.location.href = "my_accomplishments_page.php";
      } else {
        console.log("Error response:", result.message);
        alert("Error: " + result.message);
      }
    } catch (error) {
      console.log("Error details:", error);
      alert("Error: " + error.message);
    }
  });

// SUBMIT BUTTON
// $(document).ready(function () {
//   $("#submitActivityForm").on("submit", function (e) {
//     e.preventDefault();

//     const formData = new FormData(this);

//     formData.delete("activity_gallery[]");

//     selectedFiles.forEach((file) => {
//       formData.append("activity_gallery[]", file);
//     });

//     $(".member-checkbox:checked").each(function () {
//       let studentNumber = $(this).val();
//       let memberName = $(this).siblings("label").text().trim(); // Get the name of the selected member

//       formData.append("student_numbers[]", studentNumber);
//       console.log(`Student Number: ${studentNumber}, Name: ${memberName}`);

//       // Find recognition inputs with matching label name
//       $(".recognition-form").each(function () {
//         let recognitionLabel = $(this).find("label").text().trim(); // Get recognition label

//         if (recognitionLabel === memberName) {
//           let recognitionValue = $(this)
//             .find("input[name='recognition[]']")
//             .val()
//             .trim();

//           if (recognitionValue !== "") {
//             formData.append(
//               `recognition[${studentNumber}][]`,
//               recognitionValue
//             );
//             console.log(
//               `Recognition for ${studentNumber}: ${recognitionValue}`
//             );
//           }
//         }
//       });
//     });

//     $.ajax({
//       url: "sql/submit-activity.php",
//       type: "POST",
//       data: formData,
//       contentType: false,
//       processData: false,
//       success: function (response) {
//         console.log("Server response:", response);
//         alert(response);
//       },

//       error: function (xhr, status, error) {
//         console.log("Error details:", xhr.responseText);
//         alert("Error: " + error);
//       },
//     });
//   });
// });
