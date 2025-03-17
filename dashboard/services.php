<?php
include "../config/autoload.php";
if (!is_login()) redirect(base_url('login.php'), 'You must login to continue');

$user_id = user_id();
if (isset($_GET['act']) && $_GET['act'] == 'del') {
    $id = $_GET['id'];
    $db->delete('ai_services', ['id' => $id]);
    session()->set_flashdata('success', 'Service deleted successfully');
}
$items = $db->select('ai_services', ['user_id' => $user_id])->result();
include "../common/header.php";
?>
<div class="dashboard">
    <div class="container py-5">
        <div class="user-panel">
            <div class="row">
                <?php
                $subpage = 'wishlist';
                include_once "dashboard-menu.php"; ?>
                <div class="col-sm-9">
                    <?= front_view('common/alert'); ?>
                    <div id="origin">
                        <div class="bg-white p-3 rounded-1">
                            <div class="page-header">
                                <h5>My Services</h5>
                                <a href="<?= base_url('dashboard/add-services.php') ?>" class="btn btn-sm btn-primary"> New Service</a>
                            </div>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Sl</th>
                                            <th>Service name</th>
                                            <th>Price</th>
                                            <th>Locations</th>
                                            <th>Status</th>
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
                                                <td><?= $item->service_name; ?></td>
                                                <td><?= $item->amount; ?></td>
                                                <td><?php
                                                    $locs = json_decode($item->locations);
                                                    if (is_array($locs)) {
                                                        foreach ($locs as $loc) {
                                                            echo $loc->city . '-';
                                                        }
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    if ($item->status == 0) echo '<span class="badge bg-secondary">Draft</span>';
                                                    if ($item->status == 1) echo '<span class="badge bg-success">Active</span>';
                                                    if ($item->status == 2) echo '<span class="badge bg-danger">Rejected</span>';
                                                    ?>
                                                </td>
                                                <td>
                                                    <a href="<?= base_url('dashboard/edit-service.php?id=' . $item->id) ?>&act=del" class="btn btn-xs btn-primary"> Edit</a>
                                                </td>
                                            </tr>
                                        <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
include "../common/footer.php";
