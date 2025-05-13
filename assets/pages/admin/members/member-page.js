// SPLIDE
document.addEventListener("DOMContentLoaded", function () {
  var splide = new Splide(".splide", {
    // width: 250,
    type: "loop",
    perPage: 1,
    arrows: false,
    pagination: false,
    autoplay: true,
    interval: 5000,
  });
  splide.mount();
});
