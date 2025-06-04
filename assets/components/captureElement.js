import { generateFileName } from "./formatter/fileNameGenerator.js";

export function downloadElement({ triggerId, captureId }) {
  const trigger = document.getElementById(triggerId);
  const captureElement = document.getElementById(captureId);

  if (!trigger || !captureElement) {
    console.error("Invalid IDs provided to setupImageDownloader.");
    return;
  }

  trigger.addEventListener("click", function () {
    html2canvas(captureElement, {
      scale: 2,
    }).then((canvas) => {
      let image = canvas.toDataURL("image/jpeg", 0.95);
      let fileName = `${generateFileName(10)}.jpg`;

      let link = document.createElement("a");
      link.href = image;
      link.download = fileName;
      document.body.appendChild(link);
      link.click();
      document.body.removeChild(link);
    });
  });
}
