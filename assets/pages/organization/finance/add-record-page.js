// PAGE RELOAD WARNING
function beforeUnloadHandler(event) {
  event.preventDefault();
  event.returnValue = "Changes you made may not be saved.";
}

window.addEventListener("beforeunload", beforeUnloadHandler);

window.addEventListener("pagehide", function () {
  navigator.sendBeacon("sql/kill_session.php");
});

// DISABLE WARNING ON SUBMIT
document.querySelector("form").addEventListener("submit", function () {
  window.removeEventListener("beforeunload", beforeUnloadHandler);
});

if (window.performance.getEntriesByType("navigation")[0]?.type === "reload") {
  window.location.href = "my_records_page.php";
}

// CHECK IF STARTING FUND EXISTS
function startingFund() {
  let inputStartingFund = document.getElementById("startingFund");
  let totalCredit = document.getElementById("totalCredit");
  let finalFunding = document.getElementById("finalFundingTable");

  // Fetch the count from the PHP file
  fetch("sql/starting_fund.php")
    .then((response) => response.json())
    .then((data) => {
      let count = data.count;
      let startingFund = data.startingFund;

      // If only one occurrence exists, remove readonly
      if (count === 1) {
        inputStartingFund.removeAttribute("readonly");
      }

      if (count > 1 && startingFund) {
        inputStartingFund.value = startingFund;
        totalCredit.value = startingFund;
        finalFunding.textContent = startingFund;
      }
    })
    .catch((error) => console.error("Error fetching data:", error));
}

let cleaveInstances = {};
function cleave() {
  // CLEAVE
  document.querySelectorAll(".numeral").forEach((element) => {
    cleaveInstances[element.id] = new Cleave(element, {
      numeral: true,
      numeralThousandsGroupStyle: "thousand",
    });

    element.addEventListener("input", calculateTotalCredit);
  });

  function getFloatValue(id) {
    let element = document.getElementById(id);
    return element
      ? parseFloat(cleaveInstances[id]?.getRawValue() || element.value) || 0
      : 0;
  }

  function calculateTotalCredit() {
    let creditFields = [
      "startingFund",
      "weeklyContribution",
      "internalProjects",
      "externalProjects",
      "internalInitiativeFunding",
      "donationsSponsorships",
      "adviserCredit",
      "carriCredit",
    ];

    let totalCredit = creditFields.reduce(
      (sum, id) => sum + getFloatValue(id),
      0
    );
    let totalExpenses = getFloatValue("costExpenses");
    let finalFunding = totalCredit - totalExpenses;

    function formatNumber(value) {
      return value.toLocaleString({ minimumFractionDigits: 2 });
    }

    document.getElementById("totalCredit").value = formatNumber(totalCredit);
    document.getElementById("totalExpenses").value =
      formatNumber(totalExpenses);

    document.getElementById("totalCreditTable").textContent =
      formatNumber(totalCredit);
    document.getElementById("totalExpensesTable").textContent =
      formatNumber(totalExpenses);
    document.getElementById("finalFundingTable").textContent =
      formatNumber(finalFunding);
  }
}

document.addEventListener("DOMContentLoaded", function () {
  startingFund();
  cleave();
});

// FILEPOND
FilePond.registerPlugin(FilePondPluginFileValidateType);
FilePond.registerPlugin(FilePondPluginFileRename);
FilePond.registerPlugin(FilePondPluginImageExifOrientation);
FilePond.registerPlugin(FilePondPluginImagePreview);

const inputElement = document.getElementById("uploadReceipts");
const pond = FilePond.create(inputElement, {
  acceptedFileTypes: ["image/png", "image/jpeg"],

  fileRenameFunction: (file) => {
    const extension = file.name.slice(file.name.lastIndexOf(".")); // Get file extension
    const baseName = file.name.slice(0, file.name.lastIndexOf(".")); // Get filename without extension
    const newBaseName = window.prompt("Enter new filename", baseName); // Prompt for new name
    return newBaseName ? newBaseName + extension : file.name;
  },

  // imagePreviewMinHeight: 50,
  // imagePreviewMaxHeight: 100,
});

// SUBMIT RECORD
document
  .getElementById("submitRecordForm")
  .addEventListener("submit", async function (event) {
    event.preventDefault();

    const formData = new FormData(this);

    pond.getFiles().forEach((fileItem) => {
      const file = fileItem.file;
      const renamedFile = new File([file], fileItem.filename, {
        type: file.type,
      }); // Use renamed filename
      formData.append(`receipt[]`, renamedFile);
    });

    try {
      const response = await fetch("sql/record_submit.php", {
        method: "POST",
        body: formData,
      });

      const result = await response.json();

      if (result.success) {
        localStorage.setItem("submissionStatus", "success");
        window.location.href = "my_records_page.php";
      } else {
        alert("Error: " + result.message);
      }
    } catch (error) {
      console.error("Error:", error);
      alert("An error occurred while submitting the form.");
    }
  });
