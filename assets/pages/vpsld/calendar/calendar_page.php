<?php require_once '../../../sql/base_path.php'?>

<?php
require_once BASE_PATH . '/assets/sql/session_check.php';
check_role('VPSLD');
?>

<?php
// include_once BASE_PATH . '/assets/sql/temporary_session.php'
?>

<?php require_once 'sql/display_budget_request.php'?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Calendar</title>

    <?php require_once BASE_PATH . '/assets/components/header_links.php' ?>

    <link rel="stylesheet" href="/cca/assets/components/media-query/calendar.css">

    <link rel="stylesheet" href="calendar-page.css">

</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-auto">
                <?php include_once BASE_PATH . '/assets/components/sidebar/vpsld/vpsld_sidebar.php';?>
            </div>
            <div class="col main-content">
                <div class="row page-header">
                    <div class="col">
                        <h1>Calendar</h1>
                        <?php include_once BASE_PATH . '/assets/components/topbar/topbar.php'?>
                    </div>
                </div>
                <div class="row page-body">
                    <div class="col">
                        <div class="row container calendar-container">
                            <div class="col">
                                <div class="calendar" id='calendar'></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL -->
    <!-- Topbar -->
    <?php include_once BASE_PATH . '/assets/components/topbar/topbar_modal.php'?>

    <!-- Budget Approval Modal -->
    <div class="modal fade budget-approval-modal" id="budgetApprovalModal" tabindex="-1"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Event Budget Approvals</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Event Title</th>
                                    <th>File</th>
                                    <th>Amount</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = $result->fetch_assoc()) { ?>
                                <tr
                                    data-id="<?php echo $row['public_key']?>">
                                    <td><button
                                            class="no-style-btn"><?php echo $row['title']?></button>
                                    </td>
                                    <td>
                                        <a href="<?php echo $row ['path']?>"
                                            target="_blank">
                                            request_letter.pdf
                                        </a>
                                    </td>
                                    <td><?php echo number_format($row['amount_requested'], 2)?>
                                    </td>
                                    <td>
                                        <div class="action-group">
                                            <button class="approve-btn" title="Approve">
                                                <i class="bi bi-check2"></i>
                                            </button>
                                            <button class="reject-btn" title="Reject">
                                                <i class="bi bi-x-lg"></i>
                                            </button>
                                            <button class="change-amount-btn" data-bs-toggle="modal"
                                                data-bs-target="#changeBudgetAmountModal">Change<br>Amount</button>
                                        </div>
                                    </td>
                                </tr>
                                <?php }?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Change Amount Modal -->
    <form id="changeBudgetAmountForm">
        <div class="modal fade change-budget-amount-modal" id="changeBudgetAmountModal" tabindex="-1"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Change Budget Amount</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="container-fluid">
                            <input type="hidden" name="public_key" id="inputPublicKey">

                            <div class="row">
                                <div class="col">
                                    <h3 id="eventTitle">Event Title</h3>
                                    <p>By: <span id="eventOrganization">Organization</span></p>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col">
                                    <label for="inputBudgetAmount" class="form-label">Amount</label>
                                    <input type="text" name="amount" class="form-control" id="inputBudgetAmount">
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-toggle="modal"
                            data-bs-target="#budgetApprovalModal">Back</button>
                        <button type="submit" class="btn btn-primary">Approve</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- FOOTER LINKS -->
    <?php require_once BASE_PATH . '/assets/components/footer_links.php';?>

    <!-- FULLCALENDAR -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>

    <script type="module" src="calendar-page.js"></script>

    <script>
        console.log(
            "User ID:", <?php echo json_encode($_SESSION['user_id']); ?>
        )
    </script>
</body>

</html>