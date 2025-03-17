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
    $db->delete('ai_users', ['id' => $id]);
    redirect(admin_url('users/all-co-list.php'), 'Record Deleted Successfully', 'success');
}
// $total = $db->query("SELECT COUNT(*) as total FROM ai_users WHERE  user_type = '" . USER_CO . "'")->row()->total;
// $links = ceil($total / $limit);

// $sql = "SELECT * FROM ai_users WHERE user_type = '" . USER_CO . "' LIMIT " . $limit . " 
//         OFFSET " . $offset;
// $items = $db->query($sql)->result();
$results = $db->select('ai_users', ['user_type' => USER_CO]);
$items = $results->result();

$menu = 'members';
include "../common/header.php";
?>
<div class="page-header">
    <h5>All Reg. CO</h5>
    <a href="<?= admin_url('users/add-co.php') ?>" class="btn btn-primary btn-sm"> <i class="bi-plus"></i> Add New</a>
</div>
<div class="card">
    <table class="table data-table">
        <thead>
            <tr>
                <th>क्रम</th>
                <th>नाम</th>
                <th>Photo</th>
                <th>ईमेल आईडी</th>
                <th>मोबाइल नंबर</th>
                <th>केवाईसी</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sl = 1;
            foreach ($items as $item) {
                $dist = $db->select('ai_districts', ['id' => $item->dist_id], 1)->row();
                $zone = $db->select('ai_zones', ['id' => $item->zone_id], 1)->row();
            ?>
                <tr>
                    <td><?= $sl++; ?></td>
                    <td><a href="#"><?= $item->first_name . ' ' . $item->last_name; ?></a>
                        <div class="small text-muted">
                            <?= is_object($dist) ? $dist->dist_name : ''; ?>,
                            <?= is_object($zone) ? $zone->zone_name : ''; ?>
                        </div>
                    </td>
                    <td>
                        <?php
                        if ($item->image != '') {
                        ?>
                            <img src="<?= base_url(upload_dir($item->image)) ?>" width="100" />
                        <?php
                        }
                        ?>
                    </td>
                    <td><?= $item->email_id; ?></td>
                    <td><?= $item->mobile_number; ?></td>
                    <td><?php
                        if ($item->kyc_status == 0) echo '<span class="badge bg-warning">Pending</span>';
                        if ($item->kyc_status == 1) echo '<span class="badge bg-success">Approved</span>';
                        if ($item->kyc_status == 2) echo '<span class="badge bg-danger">Rejected</span>';
                        ?></td>
                    <td><a href="<?= admin_url('users/edit-co.php?id=' . $item->id) ?>" class='btn btn-success btn-xs'> <i class="bi-pencil"></i> Edit</a>
                        <a href="<?= admin_url('users/index.php?id=' . $item->id . '&action=delete') ?>" class='btn btn-danger btn-xs btn-delete'> <i class="bi-trash"></i> Delete</a>
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
            <a href="<?= admin_url('users/all-co-list.php?page=' . ($page + 1)) ?>" class="btn btn-sm btn-primary">Next</a>
        <?php
        } else if ($page >= $links) {
        ?>
            <a href="<?= admin_url('users/all-co-list.php?page=' . ($page - 1)) ?>" class="btn btn-sm btn-primary">Previous</a>
            <button disabled class="btn btn-sm btn-primary">Next</button>
        <?php
        } else if ($links >= 1 && $page < $links) {
        ?>
            <a href="<?= admin_url('users/all-co-list.php?page=' . ($page - 1)) ?>" class="btn btn-sm btn-primary">Previous</a>
            <a href="<?= admin_url('users/all-co-list.php?page=' . ($page + 1)) ?>" class="btn btn-sm btn-primary">Next</a>
        <?php
        }
        ?>
    </div> -->
</div>
<?php
include "../common/footer.php";
