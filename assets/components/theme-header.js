const body = document.documentElement;

// Apply saved theme on page load
const savedTheme = localStorage.getItem("theme");

if (savedTheme) {
  body.setAttribute("data-theme", savedTheme);
}
