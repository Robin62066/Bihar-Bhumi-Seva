<?php
include '../../config/autoload.php';
if (!is_admin_login()) redirect(admin_url('index.php'), 'You must login to continue');
// $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
// $page = max($page, 1);
// $limit = 50;
// $offset = ($page - 1) * $limit;


$type = isset($_GET['user_type']) ? $_GET['user_type'] : '';
if (input_get('id') && input_get('action')) {
    $id = input_get('id');
    $db->delete('ai_wishlist', ['user_id' => $id]);
    $db->delete('ai_orders', ['user_id' => $id]);
    $db->delete('ai_membership', ['user_id' => $id]);
    $db->delete('ai_sites', ['user_id' => $id]);
    $db->delete('ai_mutations', ['user_id' => $id]);
    $db->delete('ai_users', ['id' => $id]);
    redirect(admin_url('users'), 'Record Deleted Successfully', 'success');
}
$results = null;
if ($type == '') {
    $sql = "SELECT * FROM ai_users WHERE source = 'app' ORDER BY created DESC ";
    $results = $db->query($sql);
} else {
    $sql = "SELECT * FROM ai_users WHERE user_type = $type AND source = 'app' ORDER BY created DESC";
    $results = $db->query($sql);
}
$items = $results->result();
// $total = $db->query("SELECT COUNT(*) as total FROM ai_users WHERE source = 'app'")->row()->total;
// $links = ceil($total / $limit);
$permission = getPermission();
$permission->setModule(Permission::USERS);

$menu = 'members';
include "../common/header.php";
?>
<div class="page-header">
    <h5>All <?= user_type_string($type); ?> Users</h5>
    <?php
    if ($permission->canCreateNew()) {
    ?>
        <a href="create-user.php" class="btn btn-primary btn-sm">Create New Account</a>
    <?php
    }
    ?>

</div>
<div class="card p-3">
    <table class="table data-table">
        <thead>
            <tr>
                <th>क्रम</th>
                <th>नाम</th>
                <th>ईमेल आईडी</th>
                <th>मोबाइल नंबर</th>
                <th>उपयोगकर्ता का प्रकार</th>
                <th>केवाईसी</th>
                <th>Password</th>
                <th>Bhumi Locker</th>
                <th>Created</th>
                <?php
                if ($permission->canEdit()) {
                ?>
                    <th>EDIT</th>
                <?php
                }
                if ($permission->canDelete()) {
                ?>
                    <th>DELETE</th>
                <?php
                }
                ?>
            </tr>
        </thead>
        <tbody>
            <?php
            $sl = 1;
            foreach ($items as $item) {
                // print_r($item);
                // die;
            ?>
                <tr>
                    <td><?= $sl++; ?></td>
                    <td><a href="<?= admin_url('users/view.php?id=' . $item->id) ?>"><?= $item->first_name . ' ' . $item->last_name; ?></a>
                        <?php if ($item->isPromoted) { ?>
                            <p class="text-success text-center" style="font-size: 12px;">Promoted</p>
                        <?php } ?>
                    </td>
                    <td><?= $item->email_id; ?></td>
                    <td><?= $item->mobile_number; ?></td>
                    <td><?= user_type_string($item->user_type); ?></td>
                    <td><?php
                        if ($item->kyc_status == 0) echo '<span class="badge bg-warning">Pending</span>';
                        if ($item->kyc_status == 1) echo '<span class="badge bg-success">Approved</span>';
                        if ($item->kyc_status == 2) echo '<span class="badge bg-danger">Rejected</span>';
                        ?></td>
                    <td><?= $item->passwd; ?></td>
                    <?php
                    $txt = $item->pay_status == 1 ? '<span class="badge bg-success">Paid</span>' : '<span class="badge bg-warning">Pending</span>';
                    ?>
                    <td><?= $txt; ?></td>
                    <td><?= $item->created; ?></td>
                    <?php
                    if ($permission->canEdit()) {
                    ?>
                        <td><a href="<?= admin_url('users/edit-user.php?id=' . $item->id) ?>" class='btn btn-success btn-xs'>Edit</a></td>
                    <?php
                    }
                    if ($permission->canDelete()) {
                    ?>
                        <td><a href="<?= admin_url('users/index.php?id=' . $item->id . '&action=delete') ?>" class='btn btn-danger btn-xs btn-delete'>Delete</a></td>
                    <?php
                    }
                    ?>
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
            <a href="<?= admin_url('users/user-from-app.php?page=' . ($page + 1)) ?>" class="btn btn-sm btn-primary">Next</a>
        <?php
        } else if ($page >= $links) {
        ?>
            <a href="<?= admin_url('users/user-from-app.php?page=' . ($page - 1)) ?>" class="btn btn-sm btn-primary">Previous</a>
            <button disabled class="btn btn-sm btn-primary">Next</button>
        <?php
        } else if ($links >= 1 && $page < $links) {
        ?>
            <a href="<?= admin_url('users/user-from-app.php?page=' . ($page - 1)) ?>" class="btn btn-sm btn-primary">Previous</a>
            <a href="<?= admin_url('users/user-from-app.php?page=' . ($page + 1)) ?>" class="btn btn-sm btn-primary">Next</a>
        <?php
        }
        ?>
    </div> -->
</div>
<?php
include "../common/footer.php";
