// INPUT IMAGE
document.addEventListener("DOMContentLoaded", function () {
  const inputImageButton = document.getElementById("inputImageButton");
  const inputImage = document.getElementById("inputImage");

  inputImageButton.addEventListener("click", function (event) {
    event.preventDefault();
    inputImage.click();
  });
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
