import { createNotyf } from "../../../components/notyf.js";

import {
  onShow,
  onHide,
} from "../../../components/sweetalert2/alertAnimation.js";

// CLEAVE
var cleave = new Cleave("#inputContactNumber", {
  phone: true,
  phoneRegionCode: "PH",
});

// CALCULATE AGE
document.addEventListener("DOMContentLoaded", function () {
  function calculateAge() {
    const dob = document.getElementById("inputBirthday").value;
    if (dob) {
      const dobDate = new Date(dob);
      const today = new Date();

      if (dobDate > today) {
        alert("Invalid date of birth.");
        return;
      }
      let age = today.getFullYear() - dobDate.getFullYear();
      const monthDiff = today.getMonth() - dobDate.getMonth();
      const dayDiff = today.getDate() - dobDate.getDate();

      // Adjust age if the current date is before the birth date
      if (monthDiff < 0 || (monthDiff === 0 && dayDiff < 0)) {
        age--;
      }

      document.getElementById("inputAge").value = age;
    }
  }
  document
    .getElementById("inputBirthday")
    .addEventListener("change", calculateAge);
});

document.addEventListener("DOMContentLoaded", function () {
  // ADD PROFILE IMAGE
  const addProfileBtn = document.getElementById("addProfileButton");
  const inputProfile = document.getElementById("inputProfile");

  addProfileBtn.addEventListener("click", function (event) {
    event.preventDefault();
    inputProfile.click();
  });

  // LOAD IMAGE
  inputProfile.addEventListener("change", function (event) {
    const file = event.target.files[0];

    // Check if a valid file is selected
    if (file && file.type.startsWith("image/")) {
      const reader = new FileReader();

      // When the file is loaded, set it as the source of the image
      reader.onload = function (e) {
        document.getElementById("blank-profile").src = e.target.result;
        document.getElementById("removeProfile").style.display = "block";
      };

      // Read the file as a data URL
      reader.readAsDataURL(file);

      addProfileBtn.textContent = "Change Profile";
    } else {
      alert("Please select a valid image file.");
    }
  });

  // REMOVE PROFILE
  const removeProfile = document.getElementById("removeProfile");

  removeProfile.addEventListener("click", function (event) {
    event.preventDefault();

    inputProfile.value = ""; // Clear the file input
    document.getElementById("blank-profile").src =
      "/cca/assets/img/blank-profile.png"; // Reset to default image
    removeProfile.style.display = "none"; // Hide "X" icon

    addProfileBtn.textContent = "Add Profile";
  });
});

// NEXT BUTTON
const nextButton = document.getElementById("nextButton");
const inputs = document.querySelectorAll(
  "#inputFirstName, #inputMiddleName, #inputLastName, #inputBirthday, #inputAge, #inputContactNumber, #inputAddress, #inputEmail"
);

// enable next button
function checkFields() {
  nextButton.disabled = !Array.from(inputs).every((input) =>
    input.value.trim()
  );
}

// INPUT EVENT LISTENER
inputs.forEach((input) => input.addEventListener("input", checkFields));

// Copy name to the title
nextButton.addEventListener("click", () => {
  const fullName = `${document.getElementById("inputFirstName").value}
                    ${document.getElementById("inputMiddleName").value}
                    ${document.getElementById("inputLastName").value}`.trim();
  document.getElementById("memberName").textContent = fullName;
});

// ENABLE DATE LEFT INTPUT IF STATUS "EXITED" OR "TERMINATED"
document.getElementById("inputState").addEventListener("change", function () {
  const selectedValue = this.value;

  if (selectedValue === "Exited" || selectedValue === "Terminated") {
    inputDateLeft.disabled = false;
  } else {
    inputDateLeft.disabled = true;
  }
});

// DATE JOINED AND DATE LEFT VALIDATION
const inputDateJoined = document.getElementById("inputDateJoined");
const inputDateLeft = document.getElementById("inputDateLeft");

// Add event listener for validation when the input loses focus
inputDateLeft.addEventListener("blur", validateDates);
inputDateJoined.addEventListener("blur", validateDates);

function validateDates() {
  const dateJoinedValue = inputDateJoined.value;
  const dateLeftValue = inputDateLeft.value;

  // Ensure both inputs are fully entered (length of 10 characters for mm/dd/yyyy)
  if (dateJoinedValue.length === 10 && dateLeftValue.length === 10) {
    const dateJoined = new Date(dateJoinedValue);
    const dateLeft = new Date(dateLeftValue);

    // Check if both dates are valid
    if (!isNaN(dateJoined.getTime()) && !isNaN(dateLeft.getTime())) {
      if (dateLeft < dateJoined) {
        alert(
          "Invalid input: 'Date Left' cannot be earlier than 'Date Joined'."
        );
        inputDateLeft.value = "";
      }
    }
  }
}

document.addEventListener("DOMContentLoaded", function () {
  // FILEPOND
  FilePond.registerPlugin(FilePondPluginFileValidateType);
  FilePond.registerPlugin(FilePondPluginFileRename);

  const inputElement = document.getElementById("uploadStudentDocument");
  const pond = FilePond.create(inputElement, {
    acceptedFileTypes: ["image/png", "image/jpeg", "application/pdf"],

    fileRenameFunction: (file) => {
      const extension = file.name.slice(file.name.lastIndexOf(".")); // Get file extension
      const baseName = file.name.slice(0, file.name.lastIndexOf(".")); // Get filename without extension
      const newBaseName = window.prompt("Enter new filename", baseName); // Prompt for new name
      return newBaseName ? newBaseName + extension : file.name;
    },
  });

  document
    .getElementById("addMemberForm")
    .addEventListener("submit", async function (event) {
      event.preventDefault();

      Swal.fire({
        title: "Processing...",
        text: "Please wait while we delete the event.",
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

      const formData = new FormData(this);

      pond.getFiles().forEach((fileItem) => {
        const file = fileItem.file;
        const renamedFile = new File([file], fileItem.filename, {
          type: file.type,
        }); // Use renamed filename
        formData.append(`document[]`, renamedFile);
      });

      try {
        const response = await fetch("sql/add-member.php", {
          method: "POST",
          body: formData,
        });

        const result = await response.json();

        Swal.close();

        if (result.success) {
          const modal = bootstrap.Modal.getInstance(
            document.getElementById("addMemberModal2")
          );
          modal.hide();

          document.getElementById("addMemberForm").reset();
          pond.removeFiles();

          const notyf = createNotyf();
          notyf.success("Member added successfully.");
        } else {
          alert("Error: " + result.message);
        }
      } catch (error) {
        Swal.close();
        console.error("Error:", error);
        alert("An error occurred while submitting the form.");
      }
    });
});

// $(document).ready(function () {
//   // FILEPOND
//   FilePond.registerPlugin(FilePondPluginFileValidateType);
//   FilePond.registerPlugin(FilePondPluginFileRename);

//   const inputElement = document.getElementById("uploadStudentDocument");
//   const pond = FilePond.create(inputElement, {
//     acceptedFileTypes: ["image/png", "image/jpeg", "application/pdf"],

//     fileRenameFunction: (file) => {
//       const extension = file.name.slice(file.name.lastIndexOf(".")); // Get file extension
//       const baseName = file.name.slice(0, file.name.lastIndexOf(".")); // Get filename without extension

//       const newBaseName = window.prompt("Enter new filename", baseName); // Prompt for new name

//       return newBaseName ? newBaseName + extension : file.name;
//     },
//   });

//   // ADD MEMBER TO DATABASE
//   $("#addMemberForm").on("submit", function (e) {
//     e.preventDefault();

//     const formData = new FormData(this);

//     pond.getFiles().forEach((fileItem) => {
//       const file = fileItem.file;
//       const renamedFile = new File([file], fileItem.filename, {
//         type: file.type,
//       }); // Use renamed filename
//       formData.append(`document[]`, renamedFile);
//     });

//     $.ajax({
//       url: "sql/add-member.php",
//       type: "POST",
//       data: formData,
//       contentType: false,
//       processData: false,
//       success: function (response) {
//         alert(response);
//       },
//       error: function (xhr, status, error) {
//         console.log("Error details:", xhr.responseText);
//         alert("Error: " + error);
//       },
//     });
//   });
// });
