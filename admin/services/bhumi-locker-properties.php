<?php
include "../../config/autoload.php";
if (!is_admin_login()) redirect(admin_url('index.php'), 'You must login to continue');

$perm = getPermission(Permission::BHUMI_LOCKER);
if (!$perm->canView()) {
    session()->set_flashdata('danger', 'Your do not have access permissions');
    redirect(admin_url('dashboard.php'));
}

$menu = 'services';
if (isset($_GET['act']) && $_GET['act'] == 'del') {
    $id = $_GET['id'];
    $db->delete('ai_bhumifiles', ['id' => $id]);
    session()->set_flashdata('success', 'Record deleted successfully');
}
$items = $db->select('ai_bhumifiles')->result();
include "../common/header.php";
?>
<div id="origin">
    <div class="page-header">
        <h5>Bhumi Locker Properties</h5>
    </div>
    <div class="bg-white p-3">
        <table class="table">
            <thead>
                <tr>
                    <th>Sl</th>
                    <th>#</th>
                    <th>Name</th>
                    <th>District</th>
                    <th>Circle</th>
                    <th>Raiyat Name</th>
                    <th>Khata No</th>
                    <th>Rakba</th>
                    <th>Address</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sl = 1;
                foreach ($items as $item) {
                    $us = $db->select('ai_users', ['id' => $item->user_id], 1)->row();
                    $name = is_object($us) ?  $us->first_name . ' ' . $us->last_name : 'Guest';
                ?>
                    <tr>
                        <td><?= $sl++; ?></td>
                        <td><?= $item->id; ?></td>
                        <td><a href="<?= admin_url('users/view.php?id=' . $item->user_id) ?>"><?= $name; ?></a></td>
                        <td> </td>
                        <td> </td>
                        <td><?= $item->raiyat_name; ?></td>
                        <td><?= $item->khata_no; ?></td>
                        <td><?= $item->rakba; ?></td>
                        <td><?= $item->address; ?></td>
                        <td>
                            <div class="d-flex gap-2">
                                <?php
                                if ($perm->canEdit()) {
                                ?>
                                    <a href="<?= admin_url('services/edit-bhumifiles.php?id=' . $item->id) ?>" class="btn btn-xs btn-outline-primary"> Details</a>
                                <?php
                                }
                                if ($perm->canDelete()) {
                                ?>
                                    <a href="<?= admin_url('services/bhumi-locker-properties.php?id=' . $item->id . '&act=del') ?>" class="btn btn-xs btn-danger btn-confirm" data-msg="Are you sure to delete?"> Delete</a>
                                <?php
                                }
                                ?>
                            </div>
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
