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
  tippy("#membersBtn", {
    content: "Members",
    theme: "light",
    arrow: false,
    offset: [0, 23],
    placement: "right",
    appendTo: () => document.body,
    onShow() {
      tippy.hideAll();
    },
  });

  tippy("#calendarBtn", {
    content: "Calendar",
    theme: "light",
    arrow: false,
    offset: [0, 12],
    placement: "right",
    appendTo: () => document.body,
    onShow() {
      tippy.hideAll();
    },
  });

  tippy("#calendarBtn", {
    content:
      '<div class="no-bullets"><li><a href="#">View Schedules</a></li><li><a href="#">Notification</a></div>',
    theme: "light",
    allowHTML: true,
    arrow: false,
    offset: [0, 12],
    placement: "right",
    trigger: "click",
    appendTo: () => document.body,
    interactive: true,
    onShow() {
      tippy.hideAll();
    },
  });

  tippy("#financeBtn", {
    content: "Finance",
    theme: "light",
    arrow: false,
    offset: [0, 12],
    placement: "right",
    appendTo: () => document.body,
    onShow() {
      tippy.hideAll();
    },
  });

  tippy("#financeBtn", {
    content:
      '<div class="no-bullets"><li><a href="#">View Records</a></li><li><a href="#">My Records</a></div>',
    theme: "light",
    allowHTML: true,
    arrow: false,
    offset: [0, 12],
    placement: "right",
    trigger: "click",
    appendTo: () => document.body,
    interactive: true,
    onShow() {
      tippy.hideAll();
    },
  });

  tippy("#accomplishmentsBtn", {
    content: "Accomplishments",
    theme: "light",
    arrow: false,
    offset: [0, 12],
    placement: "right",
    appendTo: () => document.body,
    onShow() {
      tippy.hideAll();
    },
  });

  tippy("#accomplishmentsBtn", {
    content:
      '<div class="no-bullets"><li><a href="#">View Accomplishments</a></li><li><a href="#">My Accomplishments</a></div>',
    theme: "light",
    allowHTML: true,
    arrow: false,
    offset: [0, 12],
    placement: "right",
    trigger: "click",
    appendTo: () => document.body,
    interactive: true,
    onShow() {
      tippy.hideAll();
    },
  });
}
