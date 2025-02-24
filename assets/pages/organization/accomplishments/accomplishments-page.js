// INPUT IMAGE
document.addEventListener("DOMContentLoaded", function () {
  const inputImageButton = document.getElementById("inputImageButton");
  const inputImage = document.getElementById("inputImage");

  inputImageButton.addEventListener("click", function (event) {
    event.preventDefault();
    inputImage.click();
  });
});
