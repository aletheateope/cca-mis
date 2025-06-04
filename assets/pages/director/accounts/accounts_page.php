<?php require_once '../../../sql/base_path.php'?>

<?php
require_once BASE_PATH . '/assets/sql/session_check.php';
check_role('Director');
?>

<?php require_once 'sql/display_accounts.php'?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accounts</title>

    <?php require_once BASE_PATH . '/assets/components/header_links.php' ?>

    <link rel="stylesheet" href="accounts-page.css">
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-auto">
                <?php include BASE_PATH . '/assets/components/sidebar/director/director_sidebar.php' ?>
            </div>
            <main class="col main-content">
                <div class="row page-header">
                    <div class="col">
                        <h1>Accounts</h1>
                        <?php include_once BASE_PATH . '/assets/components/topbar/topbar.php'?>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
                            <i class="bi bi-plus"></i> Add User
                        </button>
                    </div>
                </div>
                <div class="row page-body">
                    <div class="col">
                        <!-- ADMIN TABLE -->
                        <section class="row container">
                            <div class="col">
                                <h3>Admin</h3>
                                <table class="table admin-table">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Role</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($row_admin = $result_admin->fetch_assoc()) { ?>
                                        <tr
                                            data-id="<?php echo $row_admin['public_key']?>">
                                            <td>
                                                <?php echo $row_admin['first_name'] . ' ' . $row_admin['last_name'] ?>
                                            </td>
                                            <td>
                                                <?php echo $row_admin['email'] ?>
                                            </td>
                                            <td>
                                                <?php echo $row_admin['role'] ?>
                                            </td>
                                            <td>
                                                <div class="actions-group">
                                                    <button class="no-style-btn edit-btn" data-bs-toggle="modal"
                                                        data-bs-target="#editAdminModal">
                                                        <i class="bi bi-pencil-square"></i>
                                                    </button>
                                                    <button class="no-style-btn delete-btn">
                                                        <i class="bi bi-trash-fill"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </section>

                        <!-- ORGANIZATION TABLE -->
                        <section class="row container">
                            <div class="col">
                                <h3>Organization</h3>
                                <table class="table organization-table">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($row_organization = $result_organization->fetch_assoc()) { ?>
                                        <tr
                                            data-id="<?php echo $row_organization['public_key']?>">
                                            <td>
                                                <?php echo $row_organization['name'] ?>
                                            </td>
                                            <td>
                                                <?php if ($row_organization['email'] == null) {
                                                    echo '<h5 class="missing-email">This organization has no email yet. Add one now.</h5>';
                                                } else {
                                                    echo $row_organization['email'];
                                                }?>
                                            </td>
                                            <td>
                                                <button class="no-style-btn edit-btn" data-bs-toggle="modal"
                                                    data-bs-target="#editOrganizationModal">
                                                    <i class="bi bi-pencil-square"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </section>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- MODAL -->
    <?php include_once BASE_PATH . '/assets/components/topbar/topbar_modal.php'?>

    <!-- Add User -->
    <form id="addUserForm" enctype="multipart/form-data">
        <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5">Add User Admin</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col">
                                    <label for="selectRole" class="form-label">Role</label>
                                    <select class="form-select" id="selectRole" name="role">
                                        <option value="1" selected>Director</option>
                                        <option value="2">VPSLD</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <label for="inputAdminFirstName" class="form-label">First Name</label>
                                    <input type="text" class="form-control" id="inputAdminFirstName" name="first_name">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <label for="inputAdminLastName" class="form-label">Last Name</label>
                                    <input type="text" class="form-control" id="inputAdminLastName" name="last_name">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <label for="inputEmail" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="inputEmail" name="email">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Create</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- Edit User Admin -->
    <div class="modal fade" id="editAdminModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Edit User Admin</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col">
                                <label for="editAdminRole" class="form-label">Role</label>
                                <select class="form-select" id="editAdminRole" name="role">
                                    <option value="1">Director</option>
                                    <option value="2">VPSLD</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <label for="editAdminFirstName" class="form-label">First Name</label>
                                <input type="text" class="form-control" id="editAdminFirstName" name="first_name">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <label for="editAdminLastName" class="form-label">Last Name</label>
                                <input type="text" class="form-control" id="editAdminLastName" name="last_name">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <label for="editAdminEmail" class="form-label">Email</label>
                                <input type="email" class="form-control" id="editAdminEmail" name="email">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit User Organization -->
    <form id="editOrganizationForm" enctype="multipart/form-data">
        <div class="modal fade" id="editOrganizationModal" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5">Edit User Organization</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="container-fluid">
                            <input type="hidden" name="public_key" class="form-control" id="organizationPublicKey">
                            <div class="row">
                                <div class="col">
                                    <label for="editOrganizationName" class="form-label">Name</label>
                                    <input type="text" name="name" class="form-control" id="editOrganizationName">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <label for="editOrganizationEmail" class="form-label">Email</label>
                                    <input type="email" name="email" class="form-control" id="editOrganizationEmail"
                                        placeholder="org_email@gmail.com">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <?php require_once BASE_PATH . '/assets/components/footer_links.php'; ?>

    <script type="module" src="accounts-page.js"></script>
</body>

</html>