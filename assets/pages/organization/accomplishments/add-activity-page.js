let selectedFiles = [];

// SPLIDE
document.addEventListener("DOMContentLoaded", function () {
  var splide = new Splide(".splide", {
    autoWidth: true,
    height: "15rem",
    focus: "center",
    perPage: 3,
    gap: "1rem",
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
                  <button type="button">Remove Image</button>
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
    console.log(selectedFiles);
  });

  list.addEventListener("click", function (event) {
    if (event.target.classList.contains("remove-img-btn")) {
      const li = event.target.closest("li");
      const fileName = li.getAttribute("data-file-name");

      // Find the correct file in selectedFiles
      const fileIndex = selectedFiles.findIndex(
        (file) => file.name === fileName
      );
      if (fileIndex !== -1) {
        selectedFiles.splice(fileIndex, 1);
      }

      splide.remove(li); // Removes the slide from Splide
      li.remove();
      splide.refresh();
      console.log(
        `Image removed. Total remaining files: ${selectedFiles.length}`
      );
    }
  });
});

// CLEAVE
var cleave = new Cleave("#inputBudgetUtilized", {
  numeral: true,
  numeralThousandsGroupStyle: "thousand",
});

// ORGANIZE CHECKED AND UNCHECKED ITEMS IN PARTICIPANTS TABLE
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
  }

  // Select All Functionality
  selectAllCheckbox.addEventListener("change", function () {
    checkboxes.forEach((checkbox) => (checkbox.checked = this.checked));
    updateCount(); // Update count after selecting all
  });

  // Individual Checkbox Change
  checkboxes.forEach((checkbox) =>
    checkbox.addEventListener("change", updateCount)
  );
});

// SUBMIT BUTTON
$(document).ready(function () {
  $("#submitActivityForm").on("submit", function (e) {
    e.preventDefault();

    const formData = new FormData(this);

    formData.delete("activity_gallery[]");

    selectedFiles.forEach((file) => {
      formData.append("activity_gallery[]", file);
    });

    $.ajax({
      url: "sql/submit-activity.php",
      type: "POST",
      data: formData,
      contentType: false,
      processData: false,
      success: function (response) {
        console.log("Server response:", response);
        alert(response);
      },

      error: function (xhr, status, error) {
        console.log("Error details:", xhr.responseText);
        alert("Error: " + error);
      },
    });
  });
});
