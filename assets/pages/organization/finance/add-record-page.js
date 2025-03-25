// PAGE RELOAD WARNING
function beforeUnloadHandler(event) {
  event.preventDefault();
  event.returnValue = "Changes you made may not be saved.";
}

window.addEventListener("beforeunload", beforeUnloadHandler);

window.addEventListener("unload", function () {
  navigator.sendBeacon("sql/cleanup.php");
});

// CHECK IF STARTING FUND EXISTS
document.addEventListener("DOMContentLoaded", function () {
  let inputField = document.getElementById("startingFund");

  // Fetch the count from the PHP file
  fetch("sql/starting-fund.php")
    .then((response) => response.json())
    .then((data) => {
      let count = data.count;

      // If only one occurrence exists, remove readonly
      if (count === 1) {
        inputField.removeAttribute("readonly");
      }
    })
    .catch((error) => console.error("Error fetching data:", error));
});

document.addEventListener("DOMContentLoaded", function () {
  let cleaveInstances = {};

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
    if (!element) return 0;

    // If Cleave.js is applied, use getRawValue()
    if (element.cleave) {
      return parseFloat(element.cleave.getRawValue()) || 0;
    }

    // Otherwise, use normal value parsing
    return parseFloat(cleaveInstances[id]?.getRawValue()) || 0;
  }

  function calculateTotalCredit() {
    let startingFund = getFloatValue("startingFund");
    let weeklyContribution = getFloatValue("weeklyContribution");
    let internalProjects = getFloatValue("internalProjects");
    let externalProjects = getFloatValue("externalProjects");
    let internalInitiativeFunding = getFloatValue("internalInitiativeFunding");
    let donationsSponsorships = getFloatValue("donationsSponsorships");
    let adviserCredit = getFloatValue("adviserCredit");
    let carriCredit = getFloatValue("carriCredit");

    let costExpenses = getFloatValue("costExpenses");

    let totalCredit =
      startingFund +
      weeklyContribution +
      internalProjects +
      externalProjects +
      internalInitiativeFunding +
      donationsSponsorships +
      adviserCredit +
      carriCredit;

    let totalExpense = costExpenses;

    let finalFunding = totalCredit - totalExpense;

    document.getElementById("totalCredit").value = totalCredit.toLocaleString(
      undefined,
      { minimumFractionDigits: 2 }
    );
    document.getElementById("totalCreditTable").textContent =
      totalCredit.toLocaleString(undefined, { minimumFractionDigits: 2 });

    document.getElementById("totalExpenses").value =
      totalExpense.toLocaleString(undefined, { minimumFractionDigits: 2 });
    document.getElementById("totalExpensesTable").textContent =
      totalExpense.toLocaleString(undefined, { minimumFractionDigits: 2 });

    document.getElementById("finalFundingTable").textContent =
      finalFunding.toLocaleString(undefined, { minimumFractionDigits: 2 });
  }
});

// FILEPOND
FilePond.registerPlugin(FilePondPluginFileValidateType);

const inputElement = document.getElementById("uploadReceipts");
const pond = FilePond.create(inputElement, {
  acceptedFileTypes: ["image/png", "image/jpeg", "image/heic", "image/heif"],
});

// DISABLE WARNING ON SUBMIT
document.querySelector("form").addEventListener("submit", function () {
  window.removeEventListener("beforeunload", beforeUnloadHandler);
});

// SUBMIT RECORD
document
  .getElementById("submitRecordForm")
  .addEventListener("submit", async function (event) {
    event.preventDefault();

    const formData = new FormData(this);

    try {
      const response = await fetch("sql/submit-record.php", {
        method: "POST",
        body: formData,
      });

      const result = await response.json();

      if (result.success) {
        alert(result.message);
        window.location.href = "finance-page.php";
      } else {
        alert("Error: " + result.message); // Show error message
      }
    } catch (error) {
      console.error("Error:", error);
      alert("An error occurred while submitting the form.");
    }
  });
