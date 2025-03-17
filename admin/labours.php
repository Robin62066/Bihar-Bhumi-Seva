<?php
include_once "../config/autoload.php";
if (!is_admin_login()) redirect(admin_url('index.php'), 'You must login to continue');

// $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
// $page = max($page, 1);
// $limit = 50;
// $offset = ($page - 1) * $limit;

$tu = $db->select("ai_users", ['user_type' => USER_CUSTOMER], false, false, 'AND', "count(id) as c")->row()->c;
$tlw = $db->select("ai_users", ['user_type' => USER_LAND_OWNER], false, false, 'AND', "count(id) as c")->row()->c;
$tb = $db->select("ai_users", ['user_type' => USER_BROKER], false, false, 'AND', "count(id) as c")->row()->c;
$tbl = $db->select("ai_users", ['user_type' => USER_BHUMI_LOCKER], false, false, 'AND', "count(id) as c")->row()->c;
$tco = $db->select("ai_users", ['user_type' => USER_CO], false, false, 'AND', "count(id) as c")->row()->c;
if (isset($_GET['act']) && $_GET['act'] == 'del') {
    $id = $_GET['id'];
    $db->delete('ai_labours', ['id' => $id]);
    set_flashdata("success", "Labour list is deleted");
}
// $sql = "SELECT * FROM ai_labours LIMIT $limit OFFSET $offset";
// $items = $db->query($sql)->result();

// $total = $db->query("SELECT COUNT(*) as total FROM ai_labours")->row()->total;
// $links = ceil($total / $limit);
$items = $db->select('ai_labours', [])->result();
$perm = getPermission();
$perm->setModule(Permission::USERS);
include_once "common/header.php";
?>
<div class="page-header">
    <h5>Labour Registrations</h5>
</div>
<div id="origin" class="dashboard text-white">
    <?php include "../common/alert.php"; ?>
    <div class="card">
        <div class="card-header">
            <b>List User</b>
        </div>
        <table class="table data-table">
            <thead>
                <tr>
                    <th>Sl</th>
                    <th>Name</th>
                    <th>Mobile No</th>
                    <th>Aadhar No</th>
                    <th>PAN No</th>
                    <th>Created</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sl = 1;
                foreach ($items as $item) {
                ?>
                    <tr>
                        <td><?= $sl++; ?></td>
                        <td> <?= $item->first_name . ' ' . $item->middle_name . ' ' . $item->last_name; ?></td>
                        <td><?= $item->mobile_number; ?></td>
                        <td><?= $item->aadhar_no; ?> </td>
                        <td><?= $item->pan_no; ?></td>
                        <td><?= $item->created; ?> </td>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="<?= admin_url('labours-details.php?id=' . $item->id); ?>" class="btn btn-xs btn-primary">Details</a>
                                <?php
                                if ($perm->canDelete()) {
                                ?>
                                    <a href="<?= admin_url('labours.php?id=' . $item->id); ?>&act=del" class="btn btn-xs btn-danger btn-delete">Delete</a>
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
                <a href="<?= admin_url('labours.php?page=' . ($page + 1)) ?>" class="btn btn-sm btn-primary">Next</a>
            <?php
            } else if ($page >= $links) {
            ?>
                <a href="<?= admin_url('labours.php?page=' . ($page - 1)) ?>" class="btn btn-sm btn-primary">Previous</a>
                <button disabled class="btn btn-sm btn-primary">Next</button>
            <?php
            } else if ($links >= 1 && $page < $links) {
            ?>
                <a href="<?= admin_url('labours.php?page=' . ($page - 1)) ?>" class="btn btn-sm btn-primary">Previous</a>
                <a href="<?= admin_url('labours.php?page=' . ($page + 1)) ?>" class="btn btn-sm btn-primary">Next</a>
            <?php
            }
            ?>
        </div> -->
    </div>
</div>
<?php
include_once "common/footer.php";
?>