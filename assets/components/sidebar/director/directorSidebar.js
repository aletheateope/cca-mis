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

function tooltip() {
  tippy("#orgMembersBtn", {
    content: "Organization Members",
    theme: "light-no-border",
    arrow: false,
    offset: [0, 27],
    placement: "right",
    interactive: true,
    onShow() {
      tippy.hideAll();
    },
    appendTo: () => document.body,
  });

  tippy("#calendarBtn", {
    content: "Calendar",
    theme: "light-no-border",
    arrow: false,
    offset: [0, 27],
    placement: "right",
    appendTo: () => document.body,
    interactive: true,
    onShow() {
      tippy.hideAll();
    },
  });

  tippy("#financeBtn", {
    content: "Finance",
    theme: "light-no-border",
    arrow: false,
    offset: [0, 27],
    placement: "right",
    appendTo: () => document.body,
    interactive: true,
    onShow() {
      tippy.hideAll();
    },
  });

  tippy("#accomplishmentsBtn", {
    content: "Accomplishments",
    theme: "light-no-border",
    arrow: false,
    offset: [0, 27],
    placement: "right",
    appendTo: () => document.body,
    interactive: true,
    onShow() {
      tippy.hideAll();
    },
  });

  tippy("#accountsBtn", {
    content: "Accounts",
    theme: "light-no-border",
    arrow: false,
    offset: [0, 27],
    placement: "right",
    appendTo: () => document.body,
    interactive: true,
    onShow() {
      tippy.hideAll();
    },
  });

  tippy("#logOutBtn", {
    content: "Log Out",
    theme: "light-no-border",
    arrow: false,
    offset: [0, 16],
    placement: "right",
    appendTo: () => document.body,
    interactive: true,
    onShow() {
      tippy.hideAll();
    },
  });
}
