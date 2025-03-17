<?php
include_once "../../config/autoload.php";
if (!is_admin_login()) redirect(admin_url('index.php'), 'You must login to continue');

$items = $db->select("ai_users", ['kyc_status' => 0])->result();
$menu = 'reports';
include "../common/header.php";

?>
<div id="origin">
    <div class="page-header">
        <h5>Signup Reports</h5>
    </div>
    <div class="bg-white p-3">
        <table class="table m-0 data-table">
            <thead>
                <tr>
                    <th>Sl</th>
                    <th>Name</th>
                    <th>Account Type</th>
                    <th>Mobile/Status</th>
                    <th>PAN/Status</th>
                    <th>Aadhar/Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sl = 1;
                foreach ($items as $item) {
                    if ($item->user_type == USER_CUSTOMER) continue;
                    if ($item->user_type == USER_CO) continue;
                    if ($item->user_type == USER_SDO) continue;
                ?>
                    <tr>
                        <td><?= $sl++; ?></td>
                        <td><?= $item->first_name . ' ' . $item->last_name; ?></td>
                        <td><?= user_type_string($item->user_type); ?></td>
                        <td><?= $item->mobile_number; ?> <br />
                            <?php
                            if ($item->mobile_verified == 1) echo '<span class="badge bg-primary">Success</span>';
                            if ($item->mobile_verified == 0) echo '<span class="badge bg-dark">Failed</span>';
                            ?>
                        </td>
                        <td>
                            <?php
                            if ($item->pan_verified == 1) echo '<span class="badge bg-primary">Success</span>';
                            if ($item->pan_verified == 0) echo '<span class="badge bg-dark">Failed</span>';
                            ?>
                        </td>
                        <td>
                            <?php
                            if ($item->aadhar_verified == 1) echo '<span class="badge bg-primary">Success</span>';
                            if ($item->aadhar_verified == 0) echo '<span class="badge bg-dark">Failed</span>';
                            ?>
                        </td>
                        <td>
                            <a href="<?= admin_url('users/edit-user.php?id=' . $item->id) ?>" class='btn btn-success btn-xs'>Edit</a>
                            <a href="<?= admin_url('reports/index.php?id=' . $item->id . '&act=send') ?>" class='btn btn-primary btn-xs btn-confirm' data-msg="Do you want to Send Password?">Send Password</a>
                            <a href="<?= admin_url('users/index.php?id=' . $item->id . '&action=delete') ?>" class='btn btn-danger btn-xs btn-delete'>Delete</a>
                        </td>
                    </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
<?php
include "../common/footer.php";
