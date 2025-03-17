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
    $db->delete('ai_mutations', ['id' => $id]);
    $db->delete('ai_mutation_data', ['app_id' => $id]);
    session()->set_flashdata('success', 'Record deleted successfully');
}
$sql = "SELECT * FROM ai_mutations ORDER BY id DESC LIMIT $limit OFFSET $offset";
$items = $db->query($sql)->result();

$total = $db->query("SELECT COUNT(*) as total FROM ai_mutations")->row()->total;
$links = ceil($total / $limit);
// $sql = "SELECT * FROM ai_mutations ORDER BY id DESC";
// $items = $db->query($sql)->result();
include "../common/header.php";

?>
<div id="origin">
    <div class="page-header">
        <h5>Mutation Applications(web)</h5>
    </div>
    <div class="bg-white p-3">
        <table class="table">
            <thead>
                <tr>
                    <th>Sl</th>
                    <th>#</th>
                    <th>Name</th>
                    <th>Year</th>
                    <th>Docs</th>
                    <th>Status</th>
                    <th>Address</th>
                    <th>Village</th>
                    <th>Form Status</th>
                    <th>Payment</th>
                    <th>Created</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sl = 1;
                foreach ($items as $item) {

                    if ($item->token == '') {
                        $token = "BS" . str_pad(time() . $item->id, 10, '0', STR_PAD_LEFT);
                        $db->update("ai_mutations", ['token' => $token], ['id' => $item->id]);
                        $item->token = $token;
                    }
                ?>
                    <tr>
                        <td><?= $sl++; ?></td>
                        <td><a href="<?= admin_url('services/mutations-details.php?id=' . $item->id) ?>"><?= $item->token; ?></a></td>
                        <td><?= $item->fname; ?></td>
                        <td><?= $item->case_year; ?></td>
                        <td>
                            <?php
                            if ($item->documents != '') {
                            ?>
                                <a href="<?= base_url(upload_dir($item->documents)) ?>" target="_blank">Download</a>
                            <?php
                            }
                            ?>
                        </td>
                        <td>
                            <?php
                            if ($item->status == 0) echo '<span class="badge bg-warning">Pending</span>';
                            if ($item->status == 1) echo '<span class="badge bg-success">Approved</span>';
                            if ($item->status == 2) echo '<span class="badge bg-info">Processing</span>';
                            if ($item->status == 3) echo '<span class="badge bg-danger">Rejected</span>';
                            if ($item->status == 4) echo '<span class="badge bg-secondary">Additional Info Required</span>';
                            ?>
                        </td>
                        <td><?= $item->address; ?></td>
                        <td><?= $item->village; ?></td>
                        <td>
                            <?php
                            if ($item->step == 6) echo '<span class="badge bg-success">Complete</span>';
                            if ($item->step <> 6) echo '<span class="badge bg-danger">In-Complete</span>';
                            ?>
                        </td>
                        <td>
                            <?php
                            if ($item->pay_status == 1) echo '<span class="badge bg-success">Paid</span>';
                            if ($item->pay_status == 0) echo '<span class="badge bg-dark">Pending</span>';
                            ?>
                        </td>
                        <td><?= $item->created; ?></td>
                        <td>
                            <div class="d-flex gap-2">
                                <?php
                                if ($perm->canEdit()) {
                                ?>
                                    <a href="<?= admin_url('services/mutations-details.php?id=' . $item->id) ?>" class="btn btn-xs btn-outline-primary"> Details</a>
                                <?php
                                }
                                if ($perm->canDelete()) {
                                ?>
                                    <a href="<?= admin_url('services/mutations.php?id=' . $item->id . '&act=del') ?>" class="btn btn-xs btn-danger btn-confirm" data-msg="Are you sure to delete?"> Delete</a>
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
                <a href="<?= admin_url('services/mutations.php?page=' . ($page + 1)) ?>" class="btn btn-sm btn-primary">Next</a>
            <?php
            } else if ($page >= $links) {
            ?>
                <a href="<?= admin_url('services/mutations.php?page=' . ($page - 1)) ?>" class="btn btn-sm btn-primary">Previous</a>
                <button disabled class="btn btn-sm btn-primary">Next</button>
            <?php
            } else if ($links >= 1 && $page < $links) {
            ?>
                <a href="<?= admin_url('services/mutations.php?page=' . ($page - 1)) ?>" class="btn btn-sm btn-primary">Previous</a>
                <a href="<?= admin_url('services/mutations.php?page=' . ($page + 1)) ?>" class="btn btn-sm btn-primary">Next</a>
            <?php
            }
            ?>
        </div>
    </div>
</div>
<?php
include "../common/footer.php";
