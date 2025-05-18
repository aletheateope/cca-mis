document.addEventListener("DOMContentLoaded", function () {
  tippy("#notificationBtn", {
    theme: "light",
    content: `
      <div class="container-fluid notification">
        <div class="row">
          <div class="col">
            <h3>Notifications</h3>
          </div>
        </div>
        <div class="container-fluid body">
          <div class="row">
            <div class="col">
              <p>No notifications.</p>
            </div>
          </div>
        </div>
        <div class="container-fluid footer">
          <a href="/cca/assets/pages/notification/notification_page.php">View All Notifications</a>
        </div>
      </div>
    `,
    trigger: "click",
    allowHTML: true,
    interactive: true,
    placement: "bottom-start",
  });
});
