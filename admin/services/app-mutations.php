<?php
include "../../config/autoload.php";
if (!is_admin_login()) redirect(admin_url('index.php'), 'You must login to continue');

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
$items = $db->select('ai_mutations_app')->result();
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
                    <th>Name</th>
                    <th>Email Id</th>
                    <th>WhatsApp No</th>
                    <th>Deed Number</th>
                    <th>Documents</th>
                    <th>Status</th>
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
                        <td>
                            <a href="<?= admin_url('users/view.php?id=' . $item->user_id); ?>"><?= $user->first_name . ' ' . $user->last_name; ?></a>
                        </td>
                        <td><?= $item->email_id; ?></td>
                        <td><?= $item->whatsapp; ?></td>
                        <td><?= $item->deed_no; ?></td>
                        <td>
                            <?php
                            if ($item->documents != '') {
                            ?>
                                <a href="<?= base_url(upload_dir($item->documents)) ?>" download="">
                                    Click to Download
                                </a>
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
                                    <a href="<?= admin_url('services/app-mutations.php?id=' . $item->id . '&act=del') ?>" class="btn btn-xs btn-danger btn-confirm" data-msg="Are you sure to delete?"> Delete</a>
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
