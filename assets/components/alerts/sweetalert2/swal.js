export const onShow = "animate__animated animate__fadeIn animate__faster";
export const onHide = "animate__animated animate__fadeOut animate__faster";

export function swalLoadingAlert(
  title = "Processing...",
  text = "Please wait..."
) {
  return Swal.fire({
    title: title,
    text: text,
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
}

export function swalConfirmAlert({
  title = "Are you sure?",
  text = "You won't be able to revert this!",
  confirmText = "Yes",
  cancelText = "No",
}) {
  return Swal.fire({
    title: title,
    text: text,
    icon: "warning",
    showCancelButton: true,
    confirmButtonText: confirmText,
    cancelButtonText: cancelText,
    reverseButtons: true,
    showClass: { popup: onShow },
    hideClass: { popup: onHide },
    customClass: { popup: "swal-container" },
  }).then((result) => result.isConfirmed);
}

export function swalErrorAlert(text = "An error occurred. Please try again.") {
  return Swal.fire({
    title: "Error",
    text: text,
    icon: "error",
    showClass: {
      popup: onShow,
    },
    hideClass: {
      popup: onHide,
    },
  });
}
