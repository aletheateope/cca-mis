const sidebar = document.getElementById("sidebar");
const collapseBtn = document.getElementById("collapse-btn");
const collapseIcon = collapseBtn.querySelector("i");

function toggleSidebar() {
  sidebar.classList.toggle("close");

  if (sidebar.classList.contains("close")) {
    collapseIcon.classList.remove("bi-chevron-double-left");
    collapseIcon.classList.add("bi-list");

    tooltip();
  } else {
    collapseIcon.classList.remove("bi-list");
    collapseIcon.classList.add("bi-chevron-double-left");
  }

  closeSubMenu();
}

function toggleSubMenu(button) {
  if (sidebar.classList.contains("close")) {
    return;
  }

  if (!button.nextElementSibling.classList.contains("show")) {
    closeSubMenu();
  }

  button.nextElementSibling.classList.toggle("show");
  button.classList.toggle("rotate");

  if (sidebar.classList.contains("close")) {
    sidebar.classList.toggle("close");
    collapseBtn.classList.toggle("rotate");
  }
}

function closeSubMenu() {
  Array.from(sidebar.getElementsByClassName("show")).forEach((ul) => {
    ul.classList.remove("show");
    ul.previousElementSibling.classList.remove("rotate");
  });
}
