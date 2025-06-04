import { formatDate } from "../../../components/formatter/formatDate.js";

const tableBody = document.querySelector(".members tbody");

function clearActiveRows() {
  tableBody
    .querySelectorAll("tr")
    .forEach((row) => row.classList.remove("active"));
}

// DISPLAY MEMBER INFO
const infoPanel = document.querySelector(".member-info-panel");
const closeBtn = document.getElementById("closePanelBtn");

tableBody.addEventListener("click", async function (event) {
  const row = event.target.closest("tr");

  if (!row || !tableBody.contains(row)) return;

  // Do nothing
  if (
    event.target.closest(".form-check-input") ||
    event.target.closest(".form-check-label")
  ) {
    return;
  }

  const isActive = row.classList.contains("active");

  clearActiveRows();

  // Open
  if (!isActive) {
    row.classList.add("active");
    infoPanel.classList.add("open");

    const publicKey = row.getAttribute("data-id");

    try {
      const response = await fetch("sql/member.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ public_key: publicKey }),
      });

      if (!response.ok) {
        throw new Error("Network response was not ok.");
      }

      const data = await response.json();

      if (data.error) {
        console.error("Error:", data.error);
        return;
      }

      const fullName = `${data.first_name || ""} ${data.middle_name || ""} ${
        data.last_name || ""
      }`
        .replace(/\s+/g, " ")
        .trim();

      const updateField = (id, value) =>
        (document.getElementById(id).textContent = value || "---");

      updateField("memberName", fullName);
      updateField("memberAge", data.age);
      updateField("memberDob", formatDate(data.birthdate));
      updateField("memberGender", data.gender);
      updateField("memberContact", data.mobile_number);
      updateField("memberEmail", data.email);
      updateField("memberAddress", data.address);
      updateField("memberStudentNumber", data.student_number);
      updateField("memberCourse", data.course);
      updateField("memberYearLevel", data.year_level);
      updateField("memberOrganization", data.organization);
      updateField("memberStatus", data.status);
      updateField("memberState", data.state);
      updateField("memberDateJoined", formatDate(data.date_joined));
      updateField("memberDateLeft", formatDate(data.date_left));

      // SEE MORE LINK
      const seeMoreLink = document.getElementById("memberFullDetailsLink");
      const studentNum = data.student_number;

      if (seeMoreLink) {
        seeMoreLink.href = `member_page.php?stud-num=${studentNum}`;
      }
    } catch (error) {
      console.error("Error fetching data:", error);
      console.error("Fetch or parse error:", error.message);
    }
  } else {
    infoPanel.classList.remove("open");
  }
});

closeBtn.addEventListener("click", function () {
  infoPanel.classList.remove("open");

  clearActiveRows();
});

// SEARCH FUNCTION
document
  .getElementById("memberSearch")
  .addEventListener("input", searchMembers);

document
  .getElementById("selectMemberOrganization")
  .addEventListener("change", searchMembers);

document
  .getElementById("selectMemberState")
  .addEventListener("change", searchMembers);

async function searchMembers() {
  const query = document.getElementById("memberSearch").value;
  const state = document.getElementById("selectMemberState").value;
  const organization = document.getElementById(
    "selectMemberOrganization"
  ).value;

  try {
    const response = await fetch("sql/search_members.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ query, state, organization }),
    });

    if (!response.ok) {
      throw new Error(`HTTP error! Status: ${response.status}`);
    }

    const members = await response.json();

    if (members.success) {
      populateTable(members.data);
    } else {
      console.log(members.message);
    }
  } catch (error) {
    console.error("Error fetching members:", error);
  }
}

async function populateTable(members) {
  tableBody.innerHTML = "";

  members.forEach((member, index) => {
    const row = document.createElement("tr");
    row.setAttribute("data-id", member.public_key);

    row.innerHTML = `
      <td>${member.first_name} ${member.last_name}</td>
      <td>${member.organization}</td>
      <td>${member.status}</td>
      <td>${member.state}</td>
      <td>${formatDate(member.date_joined)}</td>
    `;
    tableBody.appendChild(row);
  });

  document.getElementById("totalMembers").textContent = members.length;
}
