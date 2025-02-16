function calculateAge() {
  const dob = document.getElementById("inputBirthday").value;
  if (dob) {
    const dobDate = new Date(dob);
    const today = new Date();
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

// Load Image
document
  .getElementById("inputProfile")
  .addEventListener("change", function (event) {
    const file = event.target.files[0];

    // Check if a valid file is selected
    if (file && file.type.startsWith("image/")) {
      const reader = new FileReader();

      // When the file is loaded, set it as the source of the image
      reader.onload = function (e) {
        document.getElementById("blank-profile").src = e.target.result;
      };

      reader.readAsDataURL(file); // Read the file as a data URL
    } else {
      alert("Please select a valid image file.");
    }
  });

//   Enable Next Button
const inputFirstName = document.getElementById("inputFirstName");
const inputMiddleName = document.getElementById("inputMiddleName");
const inputLastName = document.getElementById("inputLastName");
const inputBirthday = document.getElementById("inputBirthday");
const inputAge = document.getElementById("inputAge");
const inputAddress = document.getElementById("inputAddress");
const inputContactNumber = document.getElementById("inputContactNumber");

const nextButton = document.getElementById("nextButton");

// Function to check if both fields are filled
function checkFields() {
  if (
    inputFirstName.value.trim() !== "" &&
    inputMiddleName.value.trim() !== "" &&
    inputLastName.value.trim() !== "" &&
    inputBirthday.value !== "" &&
    inputAge.value.trim() !== "" &&
    inputAddress.value.trim() !== "" &&
    inputContactNumber.value.trim() !== ""
  ) {
    nextButton.disabled = false; // Enable the button
  } else {
    nextButton.disabled = true; // Disable the button
  }
}

// Add event listeners to the input fields
inputFirstName.addEventListener("input", checkFields);
inputMiddleName.addEventListener("input", checkFields);
inputLastName.addEventListener("input", checkFields);
inputBirthday.addEventListener("input", checkFields);
inputAge.addEventListener("input", checkFields);
inputAddress.addEventListener("input", checkFields);
inputContactNumber.addEventListener("input", checkFields);

document.addEventListener("DOMContentLoaded", function () {
  var myModal = new bootstrap.Modal(document.getElementById("addMemberModal"));
  myModal.show();
});
