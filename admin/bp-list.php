<?php
include_once "../config/autoload.php";
if (!is_admin_login()) redirect(admin_url('index.php'), 'You must login to continue');


// $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
// $page = max($page, 1);
// $limit = 50;
// $offset = ($page - 1) * $limit;

if (isset($_GET['act']) && $_GET['act'] == 'del') {
    $id = $_GET['id'];
    $db->delete('ai_profiles', ['id' => $id]);
    set_flashdata("success", "Recorded list is deleted");
}

// $sql = "SELECT * FROM ai_profiles LIMIT $limit OFFSET $offset";
// $items = $db->query($sql)->result();

// $total = $db->query("SELECT COUNT(*) as total FROM ai_profiles")->row()->total;
// $links = ceil($total / $limit);

$items = $db->select('ai_profiles', [])->result();
$perm = getPermission();
$perm->setModule(Permission::USERS);
include_once "common/header.php";
?>
<div class="page-header">
    <h5>Business Profile</h5>
</div>
<div id="origin" class="dashboard text-white">
    <?php include "../common/alert.php"; ?>
    <div class="card">
        <div class="card-header">
            <b>Business profile list</b>
        </div>
        <table class="table data-table">
            <thead>
                <tr>
                    <th>Sl</th>
                    <th>Name</th>
                    <th>Mobile No</th>
                    <th>Business Type </th>
                    <th>Category</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sl = 1;
                foreach ($items as $item) {
                    $user = $db->select('ai_users', ['id' => $item->user_id], 1)->row();
                ?>
                    <tr>
                        <td><?= $sl++; ?></td>
                        <td> <?= $item->legal_name; ?></td>
                        <td><?= $item->mobile; ?></td>
                        <td><?= $item->business_type; ?> </td>
                        <td><?= $item->category; ?></td>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="<?= admin_url('users/edit-business.php?id=' . $item->user_id) ?>" class="btn btn-xs btn-primary">Edit</a>
                                <?php
                                if ($perm->canDelete()) {
                                ?>
                                    <a href="<?= admin_url('bp-list.php?id=' . $item->id); ?>&act=del" class="btn btn-xs btn-danger btn-delete">Delete</a>
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
        <!-- <div class="py-2 text-center">
            <?php
            if ($page <= 1) {
            ?>
                <button disabled class="btn btn-sm btn-primary">Previous</button>
                <a href="<?= admin_url('bp-list.php?page=' . ($page + 1)) ?>" class="btn btn-sm btn-primary">Next</a>
            <?php
            } else if ($page >= $links) {
            ?>
                <a href="<?= admin_url('bp-list.php?page=' . ($page - 1)) ?>" class="btn btn-sm btn-primary">Previous</a>
                <button disabled class="btn btn-sm btn-primary">Next</button>
            <?php
            } else if ($links >= 1 && $page < $links) {
            ?>
                <a href="<?= admin_url('bp-list.php?page=' . ($page - 1)) ?>" class="btn btn-sm btn-primary">Previous</a>
                <a href="<?= admin_url('bp-list.php?page=' . ($page + 1)) ?>" class="btn btn-sm btn-primary">Next</a>
            <?php
            }
            ?>
        </div> -->
    </div>
</div>
<?php
include_once "common/footer.php";
?>