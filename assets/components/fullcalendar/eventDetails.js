export function getEventDetails(
  info,
  formattedStart,
  formattedEnd,
  formatNumber,
  admin = false
) {
  const actionButtonsHTML = admin
    ? `
    <div class="action-group">
                <button class="no-style-btn edit-btn">
                  <i class="bi bi-pencil-square"></i>
                </button>
                <button class="no-style-btn delete-btn">
                  <i class="bi bi-trash-fill"></i>
                </button>
                <button class="no-style-btn options-btn">
                  <i class="bi bi-three-dots-vertical"></i>
                </button>
              </div>
`
    : "";

  const budgetHTML =
    info.event.extendedProps.budget != null &&
    info.event.extendedProps.budget !== ""
      ? `
    <div class="row">
      <div class="col">
        <h5>Budget Given</h5>
        <p>${formatNumber(info.event.extendedProps.budget)}</p>
      </div>
    </div>
  `
      : "";

  return `
   <div class="container-fluid">
        <div class="row tippy-event-details">
            <div class="col">
                <div class="row header">
                    <div class="col">
                        <h3>Event Details</h3>
                        ${actionButtonsHTML}
                    </div>
                </div>
                <div class="row title">
                    <div class="col">
                        <h4>${info.event.title}</h4>
                        <h6>${info.event.extendedProps.scheduled_by}</h6>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <p>${info.event.extendedProps.description}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <p>${info.event.extendedProps.location}</p>
                    </div>
                </div>
                <div class="row date">
                    <div class="col">
                        <h5>Start Date</h5>
                        <p>${formattedStart}</p>
                    </div>
                    <div class="col">
                        <h5>End Date</h5>
                        <p>${formattedEnd}</p>
                    </div>
                </div>
                ${budgetHTML}
            </div>
        </div>
    </div>
  `;
}
