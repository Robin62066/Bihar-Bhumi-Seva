<?php
include '../../config/autoload.php';
if (!is_admin_login()) redirect(admin_url('index.php'), 'You must login to continue');

$permission = getPermission();
$permission->setModule(Permission::USERS);

$act = $_GET['act'] ?? null;
if ($act == 'del') {
    $db->delete('ai_profiles', ['id' => $_GET['id']], 'AND', 1);
    redirect(admin_url('users/business-profiles.php'), "Recored Deleted successfully", "success");
}

$users = $db->select('ai_profiles', [], false, 'id DESC')->result();

$menu = 'members';
include "../common/header.php";
?>
<div class="page-header">
    <h5>Business Profiles</h5>
</div>
<div class="card p-3">
    <table class="table data-table">
        <thead>
            <tr>
                <th>Sl</th>
                <th>Legal Name</th>
                <th>GSTIN</th>
                <th>Account Type</th>
                <th>Address</th>
                <th>Contact Number</th>
                <th>Created</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sl = 1;
            foreach ($users as $user) {
            ?>
                <tr>
                    <td><?= $sl++; ?></td>
                    <td>
                        <a href="<?= admin_url('users/view.php?id=' . $user->user_id); ?>" target="_blank">
                            <?= $user->legal_name; ?>
                            <i class="bi-send"></i></a>
                    </td>
                    <td><?= strtoupper($user->gstin); ?></td>
                    <td><?= businessAccountType($user->business_type); ?></td>
                    <td><?= $user->address; ?></td>
                    <td><?= $user->mobile; ?></td>
                    <td><?= $user->created; ?></td>
                    <td>
                        <!-- <a href="#" class="btn btn-xs btn-primary"><i class="bi-pencil"></i> Edit</a> -->
                        <a href="<?= admin_url('users/business-profiles.php?act=del&id=' . $user->id); ?>" class="btn btn-xs btn-danger btn-confirm" data-msg="Are you sure to Delete?"><i class="bi-trash"></i> Delete</a>
                    </td>
                </tr>
            <?php
            }
            ?>
        </tbody>
    </table>
</div>
<?php
include "../common/footer.php";
