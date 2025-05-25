export function formatDate(dateStr) {
  const date = new Date(dateStr);

  if (isNaN(date)) {
    throw new Error("Invalid date string");
  }

  return date.toLocaleDateString("en-US", {
    year: "numeric",
    month: "long",
    day: "2-digit",
  });
}

export function formatTime(timeStr) {
  const [hour, minute] = timeStr.split(":");
  const date = new Date();
  date.setHours(+hour, +minute);
  return date.toLocaleTimeString([], {
    hour: "numeric",
    minute: "2-digit",
    hour12: true,
  });
}
