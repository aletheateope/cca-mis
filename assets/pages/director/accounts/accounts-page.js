import { onShow, onHide } from "../../../components/alerts/sweetalert2/swal.js";

import { createNotyf } from "../../../components/alerts/notyf.js";

const notyf = createNotyf();

const adminTable = document.querySelector(".admin-table tbody");
const organizationTable = document.querySelector(".organization-table tbody");

async function refreshAdminTable() {
  adminTable.innerHTML = "";

  try {
    const response = await fetch("sql/refresh_admin_table.php");

    if (!response.ok) {
      throw new Error("Failed to refresh admin table");
    }

    const result = await response.json();

    result.forEach((admin) => {
      const name = admin.first_name + " " + admin.last_name;

      const row = document.createElement("tr");
      row.setAttribute("data-id", admin.public_key);
      row.innerHTML = `
        <td>${name}</td>
        <td>${admin.email}</td>
        <td>${admin.role}</td>
        <td>
          <div class="actions-group">
            <button
              class="no-style-btn edit-btn"
              data-bs-toggle="modal"
              data-bs-target="#editAdminModal"
            >
              <i class="bi bi-pencil-square"></i>
            </button>
            <button class="no-style-btn delete-btn">
              <i class="bi bi-trash-fill"></i>
            </button>
          </div>
        </td>
      `;

      adminTable.appendChild(row);
    });
  } catch (error) {
    console.log(error);
  }
}

// ADD USER
document
  .getElementById("addUserForm")
  .addEventListener("submit", async function (e) {
    e.preventDefault();

    const formData = new FormData(this);

    try {
      const response = await fetch("sql/add_user.php", {
        method: "POST",
        body: formData,
      });

      if (!response.ok) {
        throw new Error("Error");
      }

      Swal.fire({
        title: "Processing...",
        text: "Please wait while we add the user.",
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

      const result = await response.json();

      Swal.close();

      if (result.success) {
        refreshAdminTable();

        const modal = bootstrap.Modal.getInstance(
          document.getElementById("addUserModal")
        );
        modal.hide();

        this.reset();
        notyf.success("User added successfully");
      } else {
        Swal.fire({
          title: "Error",
          text: result.message,
          icon: "error",
          showClass: {
            popup: onShow,
          },
          hideClass: {
            popup: onHide,
          },
        });
      }
    } catch (error) {
      console.error("Error:", error);
    }
  });

// ADMIN TABLE
adminTable.addEventListener("click", async (e) => {
  const publicKey = e.target.closest("tr").dataset.id;
  const editBtn = e.target.closest(".edit-btn");
  const deleteBtn = e.target.closest(".delete-btn");

  // EDIT ADMIN
  if (editBtn) {
    try {
      const response = await fetch("sql/fetch_admin_data.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ publicKey }),
      });

      if (!response.ok) {
        throw new Error("Failed to fetch admin data");
      }

      const data = await response.json();

      if (data.success) {
        const role = data.result.role;
        const firstName = data.result.first_name;
        const lastName = data.result.last_name;
        const email = data.result.email;

        document.getElementById("editAdminRole").value = role;
        document.getElementById("editAdminFirstName").value = firstName;
        document.getElementById("editAdminLastName").value = lastName;
        document.getElementById("editAdminEmail").value = email;
      } else {
        console.log(data.message);
      }
    } catch (error) {
      console.log(error);
    }
  }

  // DELETE ADMIN
  if (deleteBtn) {
    try {
      const response = await fetch("sql/get_admin_name.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ publicKey }),
      });

      if (!response.ok) {
        throw new Error("Failed to fetch name");
      }

      const data = await response.json();

      if (data.success) {
        const name = data.firstName + " " + data.lastName;

        Swal.fire({
          title: `Are you sure you want to remove ${name} from the system?`,
          text: "This action cannot be undone.",
          icon: "warning",
          showCancelButton: true,
          confirmButtonText: "Yes",
          cancelButtonText: "No",
          reverseButtons: true,
          showClass: {
            popup: onShow,
          },
          hideClass: {
            popup: onHide,
          },
          customClass: {
            popup: "swal-container",
          },
        }).then(async (result) => {
          if (result.isConfirmed) {
            try {
              const response = await fetch("sql/delete_user.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ publicKey }),
              });

              if (!response.ok) {
                throw new Error("Failed to remove admin");
              }

              Swal.fire({
                title: "Processing...",
                text: "Please wait while we delete the admin.",
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

              const result = await response.json();

              Swal.close();

              if (result.success) {
                notyf.success("Admin removed successfully");

                e.target.closest("tr").remove();
              } else {
                Swal.fire({
                  title: "Error",
                  text: `${result.message}`,
                  icon: "error",
                  showClass: {
                    popup: onShow,
                  },
                  hideClass: {
                    popup: onHide,
                  },
                });
              }
            } catch (error) {
              Swal.close();
              console.log(error);
            }
          }
        });
      } else {
        console.log(result.message);
      }
    } catch (error) {
      console.log(error);
    }
  }
});

// ORGANIZATION TABLE
organizationTable.addEventListener("click", async (e) => {
  const publicKey = e.target.closest("tr").dataset.id;
  const editBtn = e.target.closest(".edit-btn");

  if (editBtn) {
    try {
      const response = await fetch("sql/fetch_organization_data.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ publicKey }),
      });

      if (!response.ok) {
        throw new Error("Failed to fetch organization data");
      }

      const data = await response.json();

      if (data.success) {
        const publicKey = data.result.public_key;
        const name = data.result.name;
        const email = data.result.email;

        document.getElementById("organizationPublicKey").value = publicKey;
        document.getElementById("editOrganizationName").value = name;
        document.getElementById("editOrganizationEmail").value = email;
      } else {
        console.log(data.message);
      }
    } catch (error) {
      console.log(error);
    }
  }
});

// EDIT ORGANIZATION FORM
document
  .getElementById("editOrganizationForm")
  .addEventListener("submit", async function (e) {
    e.preventDefault();

    const formData = new FormData(this);

    try {
      const response = await fetch("sql/edit_organization.php", {
        method: "POST",
        body: formData,
      });

      if (!response.ok) {
        throw new Error("Error");
      }

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

      const result = await response.json();

      Swal.close();

      if (result.success) {
        const name = result.data.name;
        const email = result.data.email;
        const publicKey = result.data.public_key;

        const row = document.querySelector(
          `.organization-table tr[data-id="${publicKey}"]`
        );

        row.querySelector("td:nth-child(1)").textContent = name;

        const emailCell = row.querySelector("td:nth-child(2)");

        if (email === null || email === "") {
          emailCell.innerHTML =
            '<h5 class="missing-email">This organization has no email yet. Add one now.</h5>';
        } else {
          emailCell.textContent = email;
        }

        const modal = bootstrap.Modal.getInstance(
          document.getElementById("editOrganizationModal")
        );
        modal.hide();

        this.reset();
        notyf.success("Organization updated successfully");
      } else {
        Swal.fire({
          title: "Error",
          text: result.message,
          icon: "error",
          showClass: {
            popup: onShow,
          },
          hideClass: {
            popup: onHide,
          },
        });
      }
    } catch (error) {
      Swal.close();
      console.error("Error:", error);
    }
  });
