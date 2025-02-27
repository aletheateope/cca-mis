// SPLIDE
document.addEventListener("DOMContentLoaded", function () {
  var splide = new Splide(".splide", {
    type: "slide",
    perPage: 3,
    height: "12rem",
    gap: "1rem",
  });
  splide.mount();
  // INPUT IMAGE FUNCTIONALITY
  const inputImageButton = document.getElementById("inputImageButton");
  const inputImage = document.getElementById("inputActivityGallery");
  const list = document.querySelector(".splide__list");

  // Button click triggers file input
  inputImageButton.addEventListener("click", function (event) {
    event.preventDefault();
    inputImage.click();
  });

  // Handle file selection
  inputImage.addEventListener("change", function (event) {
    const files = event.target.files;

    for (let file of files) {
      if (file.type.startsWith("image/")) {
        const reader = new FileReader();
        reader.onload = function (e) {
          const li = document.createElement("li");
          li.classList.add("splide__slide");
          li.innerHTML = `<img src="${e.target.result}" alt="Uploaded Image">`;
          list.appendChild(li);

          // Refresh Splide after adding new slides
          splide.refresh();
        };
        reader.readAsDataURL(file);
      }
    }
  });
});

// CLEAVE
var cleave = new Cleave("#inputBudgetUtilized", {
  numeral: true,
  numeralThousandsGroupStyle: "thousand",
});

// INPUT IMAGE
// document.addEventListener("DOMContentLoaded", function () {
//   const inputImageButton = document.getElementById("inputImageButton");
//   const inputImage = document.getElementById("inputActivityGallery");

//   inputImageButton.addEventListener("click", function (event) {
//     event.preventDefault();
//     inputImage.click();
//   });
// });

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

    $.ajax({
      url: "sql/submit-activity.php",
      type: "POST",
      data: formData,
      contentType: false,
      processData: false,
      success: function (response) {
        alert(response);
      },
      error: function (xhr, status, error) {
        console.log("Error details:", xhr.responseText);
        alert("Error: " + error);
      },
    });
  });
});
