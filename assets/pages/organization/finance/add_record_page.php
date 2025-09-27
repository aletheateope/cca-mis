<?php require_once '../../../sql/base_path.php'?>

<?php
require_once BASE_PATH . '/assets/sql/session_check.php';
check_role('Organization');
?>

<?php
// include_once BASE_PATH . '/assets/sql/temporary_session.php'
?>

<?php require_once 'sql/check_statement_id.php'?>
<?php require_once 'sql/get_month_year.php'?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Add Record</title>

    <?php require_once BASE_PATH . '/assets/components/header_links.php' ?>

    <!-- FILEPOND -->
    <link href="https://unpkg.com/filepond/dist/filepond.css" rel="stylesheet" />
    <link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css"
        rel="stylesheet" />

    <link rel="stylesheet" href="/cca/assets/components/media-query/add-page.css">

    <link rel="stylesheet" href="add-record-page.css">
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-auto">
                <?php include_once BASE_PATH . '/assets/components/sidebar/organization/org_sidebar.php' ?>
            </div>
            <div class="col main-content">
                <div class="row page-header">
                    <div class="col">
                        <div class="row">
                            <div class="col">
                                <h1>Add Financial Statement</h1>
                                <?php include_once BASE_PATH . '/assets/components/topbar/topbar.php'?>
                                <a href="#" class="back-button">Go Back</a>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <h3>For Academic Year
                                    <?= $academicYear ?> |
                                    <?= $month ?>
                                </h3>
                            </div>
                        </div>
                    </div>
                </div>
                <form id="submitRecordForm" enctype="multipart/form-data">
                    <div class="row page-body">
                        <div class="col">
                            <div class="row container">
                                <div class="col">
                                    <h3>Credit and Expense Entry</h3>

                                    <div class="row credit-expense-section">
                                        <div class="col">
                                            <div class="row entry">
                                                <div class="col text-end">
                                                    <label for="startingFund">Starting Fund</label>
                                                </div>
                                                <div class="col">
                                                    <input type="text" name="startingFund" class="numeral form-control"
                                                        id="startingFund" placeholder="0.00" readonly>
                                                </div>
                                            </div>
                                            <div class="row entry">
                                                <div class="col">
                                                    <label for="weeklyContribution">Credit From Weekly
                                                        Contribution</label>
                                                </div>
                                                <div class="col">
                                                    <input type="text" name="weeklyContribution"
                                                        class="numeral form-control" id="weeklyContribution"
                                                        placeholder="0.00">
                                                </div>
                                            </div>
                                            <div class="row entry">
                                                <div class="col">
                                                    <label for="internalProjects">Revenue From Internal Projects</label>
                                                </div>
                                                <div class="col">
                                                    <input type="text" name="internalProjects"
                                                        class="numeral form-control" id="internalProjects"
                                                        placeholder="0.00">
                                                </div>
                                            </div>
                                            <div class="row entry">
                                                <div class="col">
                                                    <label for="externalProjects">Revenue From External Projects</label>
                                                </div>
                                                <div class="col">
                                                    <input type="text" name="externalProjects"
                                                        class="numeral form-control" id="externalProjects"
                                                        placeholder="0.00">
                                                </div>
                                            </div>
                                            <div class="row entry">
                                                <div class="col">
                                                    <label for="internalInitiativeFunding">Credit For Internal
                                                        Initiative Funding</label>
                                                </div>
                                                <div class="col">
                                                    <input type="text" name="internalInitiativeFunding"
                                                        class="numeral form-control" id="internalInitiativeFunding"
                                                        placeholder="0.00">
                                                </div>
                                            </div>
                                            <div class="row entry">
                                                <div class="col">
                                                    <label for="donationsSponsorships">Credit From
                                                        Donations/Sponsorships</label>
                                                </div>
                                                <div class="col">
                                                    <input type="text" name="donationsSponsorships"
                                                        class="numeral form-control" id="donationsSponsorships"
                                                        placeholder="0.00">
                                                </div>
                                            </div>
                                            <div class="row entry">
                                                <div class="col">
                                                    <label for="adviserCredit">Credit From Adviser</label>
                                                </div>
                                                <div class="col">
                                                    <input type="text" name="adviserCredit" class="numeral form-control"
                                                        id="adviserCredit" placeholder="0.00">
                                                </div>
                                            </div>
                                            <div class="row entry">
                                                <div class="col">
                                                    <label for="carriCredit">Credit From Carri</label>
                                                </div>
                                                <div class="col">
                                                    <input type="text" name="carriCredit" class="numeral form-control"
                                                        id="carriCredit" placeholder="0.00">
                                                </div>
                                            </div>
                                            <div class="row entry">
                                                <div class="col text-end">
                                                    <label for="totalCredit">Total Credit</label>
                                                </div>
                                                <div class="col">
                                                    <input type="text" name="totalCredit" class="numeral form-control"
                                                        id="totalCredit" placeholder="----" readonly>
                                                </div>
                                            </div>
                                            <div class="row entry">
                                                <div class="col">
                                                    <label for="costExpenses">Cost and Expenses</label>
                                                </div>
                                                <div class="col">
                                                    <input type="text" class="numeral form-control" id="costExpenses"
                                                        placeholder="0.00">
                                                </div>
                                            </div>
                                            <div class="row entry">
                                                <div class="col text-end">
                                                    <label for="totalExpenses">Total Expenses</label>
                                                </div>
                                                <div class="col">
                                                    <input type="text" name="totalExpenses" class="numeral form-control"
                                                        id="totalExpenses" placeholder="----" readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row container">
                                <div class="col">
                                    <h3>Supporting Receipts</h3>

                                    <div class="row filepond-container">
                                        <div class="col-12 px-5">
                                            <input type="file" name="receipt[]" id="uploadReceipts"
                                                accept="image/png, image/jpeg" multiple />
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class=" row container">
                                <div class="col">
                                    <h3>Summary</h3>

                                    <table class="table summary-table">
                                        <tbody>
                                            <tr>
                                                <td>Total Credit</td>
                                                <td id="totalCreditTable">0.00</td>
                                            </tr>
                                            <tr>
                                                <td>Total Expenses</td>
                                                <td id="totalExpensesTable">0.00</td>
                                            </tr>
                                            <tr>
                                                <td>Final Funding Less All Expenses</td>
                                                <td id="finalFundingTable">0.00</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="row submit-row">
                                <div class="col">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL -->
    <!-- Topbar -->
    <?php include_once BASE_PATH . '/assets/components/topbar/topbar_modal.php'?>

    <!-- View Profile Modal -->
    <?php include_once BASE_PATH . '/assets/components/sidebar/org_modal.php'; ?>

    <!-- FOOTER LINKS -->
    <?php require_once BASE_PATH . '/assets/components/footer_links.php'?>

    <!-- HEIC2ANY -->
    <!-- <script src="https://raw.githubusercontent.com/alexcorvi/heic2any/master/dist/heic2any.min.js"></script> -->


    <!-- FILEPOND -->
    <script src="https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.js">
    </script>
    <script
        src="https://unpkg.com/filepond-plugin-image-exif-orientation/dist/filepond-plugin-image-exif-orientation.js">
    </script>
    <script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.js"></script>
    <script src="https://unpkg.com/filepond-plugin-file-rename/dist/filepond-plugin-file-rename.js"></script>
    <script src="https://unpkg.com/filepond/dist/filepond.js"></script>

    <script type="module" src="add-record-page.js"></script>

    <script>
        console.log(
            "User ID:", <?php echo json_encode($_SESSION['user_id']); ?> ,
            "Statement Report ID:", <?php echo json_encode($_SESSION['statement_report_id']); ?> ,
        );
    </script>
</body>

</html>