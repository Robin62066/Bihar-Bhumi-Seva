<?php
include "../../config/autoload.php";
if (!is_admin_login()) redirect(admin_url('index.php'), 'You must login to continue');

$menu = 'properties';
if (isset($_GET['act']) && $_GET['act'] == 'del') {
    $id = $_GET['id'];
    $db->delete('ai_sites', ['id' => $id]);
    set_flashdata("success", "Property list deleted");
}
$items = $db->select('ai_sites', ["share_on_home" => '1'], false, "id DESC")->result();
include "../common/header.php";
$perm = getPermission();
$perm->setModule(Permission::PROPERTIES);
?>
<div id="origin">
    <div class="page-header">
        <h5>All Properties</h5>
        <?php
        if ($perm->canCreateNew()) {
        ?>
            <a href="<?= admin_url('properties/add-new.php') ?>" class="btn btn-primary btn-sm">Add Property</a>
        <?php
        }
        ?>
    </div>
    <div class="bg-white p-3">
        <table class="table data-table">
            <thead>
                <tr>
                    <th>Sl</th>
                    <th>Title</th>
                    <th>Dist</th>
                    <th>Anchal</th>
                    <th>Area</th>
                    <th>Price</th>
                    <th>Photo</th>
                    <th>Owner Info</th>

                    <th>Status</th>
                    <th>Created</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sl = 1;
                foreach ($items as $item) {
                    $dist = $db->select('ai_districts', ['id' => $item->dist_id], 1)->row();
                    $zone = $db->select('ai_zones', ['id' => $item->zone_id], 1)->row();
                    $user = $db->select('ai_users', ['id' => $item->user_id], 1)->row();
                ?>
                    <tr>
                        <td><?= $sl++; ?></td>
                        <td>
                            <a href="<?= base_url('property-view.php?id=' . $item->id) ?>" target="_blank">
                                <?= $item->site_title; ?> <i class="bi-send"></i>
                            </a>
                        </td>
                        <td><?= $dist->dist_name; ?></td>
                        <td><?= $zone->zone_name; ?></td>
                        <td><?= $item->total_area . ' ' . $item->area_unit; ?></td>
                        <td><?= $item->total_amount; ?></td>
                        <td>
                            <?php
                            if ($item->photo_front != '') {
                            ?>
                                <img src="<?= base_url(upload_dir($item->photo_front)) ?>" width="100" />
                            <?php
                            }
                            ?>
                        </td>
                        <td>
                            <a href="<?= admin_url('users/view.php?id=' . $user->id) ?>" target="_blank">
                                <?= $user->first_name . ' ' . $user->last_name; ?> <br />
                                <?= $user->mobile_number; ?>
                            </a>
                        </td>
                        <td>
                            <?php
                            if ($item->status == -1) echo '<span class="badge bg-dark">In-Complete</span>';
                            if ($item->status == 1) echo '<span class="badge bg-success">Active</span>';
                            if ($item->status == 0) echo '<span class="badge bg-warning">Pending</span>';
                            if ($item->status == 2) echo '<span class="badge bg-danger">Rejected</span>';
                            ?>
                        </td>
                        <td><?= $item->created; ?></td>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="<?= admin_url('properties/details.php?id=' . $item->id); ?>" class="btn btn-xs btn-primary">Details</a>
                                <?php
                                if ($perm->canDelete()) {
                                ?>
                                    <a href="<?= admin_url('properties/?id=' . $item->id); ?>&act=del" class="btn btn-xs btn-danger btn-delete">Delete</a>
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
