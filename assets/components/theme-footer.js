document.addEventListener("DOMContentLoaded", () => {
  // const savedTheme = localStorage.getItem("theme");
  const themeSelect = document.getElementById("themeSelect");

  if (savedTheme) {
    themeSelect.value = savedTheme === "dark" ? "2" : "1";
  }

  themeSelect.addEventListener("change", () => {
    const selected = themeSelect.value;
    const theme = selected === "2" ? "dark" : "light";
    body.setAttribute("data-theme", theme);
    localStorage.setItem("theme", theme);
  });
});
