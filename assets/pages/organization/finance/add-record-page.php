<?php require_once '../../../sql/base-path.php'?>

<?php
// require_once '../../sql/session_check.php';
// check_role('Organization');
?>

<?php session_start();?>

<?php require_once 'sql/get-month-year.php'?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard</title>

    <?php require_once BASE_PATH . '/assets/components/header-links.php' ?>

    <!-- FILEPOND -->
    <link href="https://unpkg.com/filepond/dist/filepond.css" rel="stylesheet" />

    <link rel="stylesheet" href="/cca/assets/components/add-page.css">

    <link rel="stylesheet" href="add-record-page.css">

    <link rel="stylesheet" href="/cca/assets/components/media-query.css">
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-auto">
                <?php include BASE_PATH . '/assets/components/sidebar/organization/sidebar.php' ?>
            </div>
            <div class="col main-content">
                <div class="row page-header">
                    <div class="col">
                        <div class="row header">
                            <div class="col">
                                <h1>Add Financial Statement</h1>
                                <a href="#" class="back-button">Go Back</a>
                            </div>
                        </div>
                        <div class="row body">
                            <div class="col">
                                <h3>For Academic Year
                                    <?= $academicYear ?> |
                                    <?= $month ?>
                                </h3>
                            </div>
                        </div>
                    </div>
                </div>
                <form id="submitRecordForm">
                    <div class="row page-body">
                        <div class="col">
                            <div class="row content">
                                <div class="col">
                                    <h3>Credit and Expense Entry</h3>

                                    <div class="row credit-expense-section">
                                        <div class="col">
                                            <div class="row entry">
                                                <div class="col text-end">
                                                    Starting Fund
                                                </div>
                                                <div class="col">
                                                    <input type="text" name="startingFund" class="numeral form-control"
                                                        id="startingFund" placeholder="0.00" readonly>
                                                </div>
                                            </div>
                                            <div class="row entry">
                                                <div class="col">
                                                    Credit From Weekly Contribution
                                                </div>
                                                <div class="col">
                                                    <input type="text" name="weeklyContribution"
                                                        class="numeral form-control" id="weeklyContribution"
                                                        placeholder="0.00">
                                                </div>
                                            </div>
                                            <div class="row entry">
                                                <div class="col">
                                                    Revenue From Internal Projects
                                                </div>
                                                <div class="col">
                                                    <input type="text" name="internalProjects"
                                                        class="numeral form-control" id="internalProjects"
                                                        placeholder="0.00">
                                                </div>
                                            </div>
                                            <div class="row entry">
                                                <div class="col">
                                                    Revenue From External Projects
                                                </div>
                                                <div class="col">
                                                    <input type="text" name="externalProjects"
                                                        class="numeral form-control" id="externalProjects"
                                                        placeholder="0.00">
                                                </div>
                                            </div>
                                            <div class="row entry">
                                                <div class="col">
                                                    Credit For Internal Initiative Funding
                                                </div>
                                                <div class="col">
                                                    <input type="text" name="internalInitiativeFunding"
                                                        class="numeral form-control" id="internalInitiativeFunding"
                                                        placeholder="0.00">
                                                </div>
                                            </div>
                                            <div class="row entry">
                                                <div class="col">
                                                    Credit From Donations/Sponsorships
                                                </div>
                                                <div class="col">
                                                    <input type="text" name="donationsSponsorships"
                                                        class="numeral form-control" id="donationsSponsorships"
                                                        placeholder="0.00">
                                                </div>
                                            </div>
                                            <div class="row entry">
                                                <div class="col">
                                                    Credit From Adviser
                                                </div>
                                                <div class="col">
                                                    <input type="text" name="adviserCredit" class="numeral form-control"
                                                        id="adviserCredit" placeholder="0.00">
                                                </div>
                                            </div>
                                            <div class="row entry">
                                                <div class="col">
                                                    Credit From Carri
                                                </div>
                                                <div class="col">
                                                    <input type="text" name="carriCredit" class="numeral form-control"
                                                        id="carriCredit" placeholder="0.00">
                                                </div>
                                            </div>
                                            <div class="row entry">
                                                <div class="col text-end">
                                                    Total Credit
                                                </div>
                                                <div class="col">
                                                    <input type="text" name="totalCredit" class="numeral form-control"
                                                        id="totalCredit" placeholder="----" readonly>
                                                </div>
                                            </div>
                                            <div class="row entry">
                                                <div class="col">
                                                    Cost and Expenses
                                                </div>
                                                <div class="col">
                                                    <input type="text" class="numeral form-control" id="costExpenses"
                                                        placeholder="0.00">
                                                </div>
                                            </div>
                                            <div class="row entry">
                                                <div class="col text-end">
                                                    Total Expenses
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

                            <div class="row content">
                                <div class="col">
                                    <h3>Supporting Receipts</h3>

                                    <div class="row filepond-container">
                                        <div class="col-12 px-5">
                                            <input type="file" name="receipt[]" id="uploadReceipts"
                                                accept="image/png, image/jpeg, image/heic" multiple />
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row content">
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

    <?php require_once BASE_PATH . '/assets/components/footer-links.php'; ?>

    <!-- HEIC2ANY -->
    <!-- <script src="https://raw.githubusercontent.com/alexcorvi/heic2any/master/dist/heic2any.min.js"></script> -->


    <!-- FILEPOND -->
    <script src="https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.js">
    </script>
    <script src="https://unpkg.com/filepond/dist/filepond.js"></script>

    <script src="add-record-page.js"></script>

    <script>
        console.log(
            "User ID:", <?php echo json_encode($_SESSION['user_id']); ?> ,
            "Statement ID:", <?php echo json_encode($_SESSION['statement_id']); ?>
        );
    </script>
</body>

</html>