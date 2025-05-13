export function initializeFancybox() {
  Fancybox.bind("[data-fancybox]", {
    Toolbar: {
      display: {
        right: ["iterateZoom", "slideshow", "download", "fullscreen", "close"],
      },
    },
  });
}
