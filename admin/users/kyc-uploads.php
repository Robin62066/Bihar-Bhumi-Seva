<?php
include '../../config/autoload.php';
if (!is_admin_login()) redirect(admin_url('index.php'), 'You must login to continue');

// $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
// $page = max($page, 1);
// $limit = 50;
// $offset = ($page - 1) * $limit;
$permission = getPermission();
$permission->setModule(Permission::USERS);

// $sql = "SELECT * FROM ai_kyc_upload LIMIT $limit OFFSET $offset";
// $items = $db->query($sql)->result();
$items = $db->select('ai_kyc_upload')->result();
// $total = $db->query("SELECT COUNT(*) as total FROM ai_kyc_upload")->row()->total;
// $links = ceil($total / $limit);
$menu = 'members';
include "../common/header.php";
?>
<div class="page-header">
    <h5>Manual KYC Report</h5>
    <a href="https://www.biharbhumiseva.in/aadhar-upload.php" class="btn btn-sm btn-primary" target="_blank">Manual KYC Upload</a>
</div>
<div class="card p-3">
    <table class="table data-table">
        <thead>
            <tr>
                <th>Sl</th>
                <th>Userinfo</th>
                <th>Aadhar Number</th>
                <th>Aadhar Name</th>
                <th>User Type</th>
                <th>Front</th>
                <th>Back</th>
                <th>Status</th>
                <th>Created</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sl = 1;
            foreach ($items as $item) {
                $us = $db->select('ai_users', ['id' => $item->user_id], 1)->row();
            ?>
                <tr>
                    <td><?= $sl++; ?></td>
                    <td><a href="<?= admin_url('users/view.php?id=' . $us->id) ?>"><?= $us->first_name . ' ' . $us->last_name; ?></a></td>
                    <td><?= $item->aadhar_no; ?></td>
                    <td><?= $item->aadhar_name; ?></td>
                    <td><?= user_type_string($us->user_type); ?></td>
                    <td>
                        <?php
                        if ($item->image != '') {
                        ?>
                            <a href="<?= base_url(upload_dir($item->image)); ?>" target="_blank">
                                <img src="<?= base_url(upload_dir($item->image)); ?>" width="100" />
                            </a>
                        <?php
                        }
                        ?>
                    </td>
                    <td>
                        <?php
                        if ($item->image_back != '') {
                        ?>
                            <a href="<?= base_url(upload_dir($item->image_back)); ?>" target="_blank">
                                <img src="<?= base_url(upload_dir($item->image_back)); ?>" width="100" />
                            </a>
                        <?php
                        }
                        ?>
                    </td>
                    <td><?php
                        if ($item->status == 0) echo '<span class="badge bg-warning">Pending</span>';
                        if ($item->status == 1) echo '<span class="badge bg-success">Approved</span>';
                        if ($item->status == 2) echo '<span class="badge bg-danger">Rejected</span>';
                        ?>
                    </td>
                    <td><?= $item->created; ?></td>
                    <td>
                        <a href="#" class="btn btn-xs btn-primary">View</a>
                    </td>
                </tr>
            <?php
            }
            ?>
        </tbody>
    </table>
    <!-- <div class="py-2 text-center">
        <?php
        if ($page <= 1) {
        ?>
            <button disabled class="btn btn-sm btn-primary">Previous</button>
            <a href="<?= admin_url('users/kyc-uploads.php?page=' . ($page + 1)) ?>" class="btn btn-sm btn-primary">Next</a>
        <?php
        } else if ($page >= $links) {
        ?>
            <a href="<?= admin_url('users/kyc-uploads.php?page=' . ($page - 1)) ?>" class="btn btn-sm btn-primary">Previous</a>
            <button disabled class="btn btn-sm btn-primary">Next</button>
        <?php
        } else if ($links >= 1 && $page < $links) {
        ?>
            <a href="<?= admin_url('users/kyc-uploads.php?page=' . ($page - 1)) ?>" class="btn btn-sm btn-primary">Previous</a>
            <a href="<?= admin_url('users/kyc-uploads.php?page=' . ($page + 1)) ?>" class="btn btn-sm btn-primary">Next</a>
        <?php
        }
        ?>
    </div> -->
</div>
<?php
include "../common/footer.php";
