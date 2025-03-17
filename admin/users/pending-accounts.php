<?php
include '../../config/autoload.php';
if (!is_admin_login()) redirect(admin_url('index.php'), 'You must login to continue');

// $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
// $page = max($page, 1);
// $limit = 50;
// $offset = ($page - 1) * $limit;

// $sql = "SELECT * FROM ai_users WHERE admin_verified = 0 ORDER BY created DESC LIMIT $limit OFFSET $offset";
// $items = $db->query($sql)->result();

$results = $db->select('ai_users', ['admin_verified' => 0]);
$items = $results->result();

// $total = $db->query("SELECT COUNT(*) as total FROM ai_users WHERE admin_verified = 0 ")->row()->total;
// $links = ceil($total / $limit);

$perm = getPermission();
$perm->setModule(Permission::USERS);

$menu = 'members';
include "../common/header.php";
?>
<div class="page-header">
    <h5>All Pending Users</h5>
    <a href="create-user.php" class="btn btn-primary btn-sm">Create New Account</a>
</div>
<div class="card p-3">
    <table class="table data-table">
        <thead>
            <tr>
                <th>Sl</th>
                <th>Name</th>
                <th>Email Id</th>
                <th>Mobileर</th>
                <th>User Type</th>
                <th>Bhumi Locker</th>
                <?php
                if ($perm->canEdit()) {
                ?>
                    <th>Edit</th>
                <?php
                }
                if ($perm->canDelete()) {
                ?>
                    <th>Delete</th>
                <?php
                }
                ?>
            </tr>
        </thead>
        <tbody>
            <?php
            $sl = 1;
            foreach ($items as $item) {
            ?>
                <tr>
                    <td><?= $sl++; ?></td>
                    <td><a href="<?= admin_url('users/view.php?id=' . $item->id) ?>"><?= $item->first_name . ' ' . $item->last_name; ?></a></td>
                    <td><?= $item->email_id; ?></td>
                    <td><?= $item->mobile_number; ?></td>
                    <td><?= user_type_string($item->user_type); ?></td>
                    <td><?= $item->passwd; ?></td>
                    <?php
                    if ($perm->canEdit()) {
                    ?>
                        <td><a href="<?= admin_url('users/edit-user.php?id=' . $item->id) ?>" class='btn btn-success btn-xs'>Edit</a></td>
                    <?php
                    }
                    if ($perm->canDelete()) {
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
            <a href="<?= admin_url('users/pending-accounts.php?page=' . ($page + 1)) ?>" class="btn btn-sm btn-primary">Next</a>
        <?php
        } else if ($page >= $links) {
        ?>
            <a href="<?= admin_url('users/pending-accounts.php?page=' . ($page - 1)) ?>" class="btn btn-sm btn-primary">Previous</a>
            <button disabled class="btn btn-sm btn-primary">Next</button>
        <?php
        } else if ($links >= 1 && $page < $links) {
        ?>
            <a href="<?= admin_url('users/pending-accounts.php?page=' . ($page - 1)) ?>" class="btn btn-sm btn-primary">Previous</a>
            <a href="<?= admin_url('users/pending-accounts.php?page=' . ($page + 1)) ?>" class="btn btn-sm btn-primary">Next</a>
        <?php
        }
        ?>
    </div> -->
</div>
<?php
include "../common/footer.php";
