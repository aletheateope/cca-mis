document.getElementById("logoutBtn").addEventListener("click", function () {
  event.preventDefault();
  Swal.fire({
    title: "Are you sure you want to log out?",
    text: "You will be logged out of your account. Do you want to continue?",
    icon: "warning",
    showCancelButton: true,
    confirmButtonText: "Yes",
    cancelButtonText: "No",
    showClass: {
      popup: `
      animate__animated
      animate__fadeIn
      animate__faster
    `,
    },
    hideClass: {
      popup: `
      animate__animated
      animate__fadeOut
      animate__faster
    `,
    },
    customClass: {
      popup: "swal-container",
    },
  }).then((result) => {
    if (result.isConfirmed) {
      window.location.href = "/cca/assets/sql/logout.php";
    }
  });
});
