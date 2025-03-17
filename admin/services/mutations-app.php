<?php
include "../../config/autoload.php";
if (!is_admin_login()) redirect(admin_url('index.php'), 'You must login to continue');
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page = max($page, 1);
$limit = 50;
$offset = ($page - 1) * $limit;

$perm = getPermission(Permission::MUTATION_SERVICES);
if (!$perm->canView()) {
    session()->set_flashdata('danger', 'Your do not have access permissions');
    redirect(admin_url('dashboard.php'));
}

$menu = 'services';
if (isset($_GET['act']) && $_GET['act'] == 'del') {
    $id = $_GET['id'];
    $db->delete('ai_mutations_app', ['id' => $id]);
    session()->set_flashdata('success', 'Record deleted successfully');
}
$sql = "SELECT * FROM ai_mutations_app ORDER BY id DESC LIMIT $limit OFFSET $offset";
$items = $db->query($sql)->result();

$total = $db->query("SELECT COUNT(*) as total FROM ai_mutations_app")->row()->total;
$links = ceil($total / $limit);

// $sql = "SELECT * FROM ai_mutations_app ORDER BY id DESC";
// $items = $db->query($sql)->result();
include "../common/header.php";

?>
<div id="origin">
    <div class="page-header">
        <h5>Mutation Applications(App)</h5>
    </div>
    <div class="bg-white p-3">
        <table class="table">
            <thead>
                <tr>
                    <th>Sl</th>
                    <th>User Id</th>
                    <th>Deed Number</th>
                    <th>Document</th>
                    <th>Created</th>
                    <th>Status</th>
                    <th>Email</th>
                    <th>Whatsapp Number</th>
                    <th>Payment Status</th>
                    <th>Order Id</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sl = 1;
                foreach ($items as $item) {
                ?>
                    <tr>
                        <td><?= $sl++; ?></td>
                        <td><?= $item->user_id; ?></td>
                        <td><?= $item->deed_no; ?></td>
                        <td>
                            <?php
                            if ($item->documents != '') {
                            ?>
                                <a href="<?= base_url(upload_dir($item->documents)) ?>" target="_blank">Download</a>
                            <?php
                            }
                            ?>

                        </td>
                        <td><?= $item->created; ?></td>
                        <td><?php
                            if ($item->status == 0) echo '<span class="badge bg-warning">Pending</span>';
                            if ($item->status == 1) echo '<span class="badge bg-success">Approved</span>';
                            if ($item->status == 2) echo '<span class="badge bg-info">Processing</span>';
                            if ($item->status == 3) echo '<span class="badge bg-danger">Rejected</span>';
                            if ($item->status == 4) echo '<span class="badge bg-secondary">Additional Info Required</span>';
                            ?></td>
                        <td><?= $item->email_id; ?></td>
                        <td><?= $item->whatsapp; ?></td>
                        <td>
                            <?php
                            if ($item->pay_status == 1) echo '<span class="badge bg-success">Paid</span>';
                            if ($item->pay_status == 0) echo '<span class="badge bg-dark">Pending</span>';
                            ?>
                        </td>
                        <td><?= $item->rzp_order_id; ?></td>
                        <td><?= $item->created; ?></td>
                        <td>
                            <div class="d-flex gap-2">
                                <?php
                                if ($perm->canEdit()) {
                                ?>
                                    <a href="<?= admin_url('services/mutations-app-details.php?id=' . $item->id) ?>" class="btn btn-xs btn-outline-primary"> Details</a>
                                <?php
                                }
                                if ($perm->canDelete()) {
                                ?>
                                    <a href="<?= admin_url('services/mutations-app.php?id=' . $item->id . '&act=del') ?>" class="btn btn-xs btn-danger btn-confirm" data-msg="Are you sure to delete?"> Delete</a>
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
        <div class="py-2 text-center">
            <?php
            if ($page <= 1) {
            ?>
                <button disabled class="btn btn-sm btn-primary">Previous</button>
                <a href="<?= admin_url('services/mutations-app.php?page=' . ($page + 1)) ?>" class="btn btn-sm btn-primary">Next</a>
            <?php
            } else if ($page >= $links) {
            ?>
                <a href="<?= admin_url('services/mutations-app.php?page=' . ($page - 1)) ?>" class="btn btn-sm btn-primary">Previous</a>
                <button disabled class="btn btn-sm btn-primary">Next</button>
            <?php
            } else if ($links >= 1 && $page < $links) {
            ?>
                <a href="<?= admin_url('services/mutations-app.php?page=' . ($page - 1)) ?>" class="btn btn-sm btn-primary">Previous</a>
                <a href="<?= admin_url('services/mutations-app.php?page=' . ($page + 1)) ?>" class="btn btn-sm btn-primary">Next</a>
            <?php
            }
            ?>
        </div>
    </div>
</div>
<?php
include "../common/footer.php";
