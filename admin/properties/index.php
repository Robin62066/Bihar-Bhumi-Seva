<?php
include "../../config/autoload.php";
if (!is_admin_login()) redirect(admin_url('index.php'), 'You must login to continue');

// Get the current page from URL (default = 1)
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

// $page = max($page, 1);
// $limit = 50;
// $offset = ($page - 1) * $limit;


// $total = $db->query("SELECT COUNT(*) as total FROM ai_sites")->row()->total;
// $links = ceil($total / $limit);

$menu = 'properties';
if (isset($_GET['act']) && $_GET['act'] == 'del') {
    $id = $_GET['id'];
    $db->delete('ai_sites', ['id' => $id]);
    set_flashdata("success", "Property list deleted");
}
$sql = "SELECT * FROM ai_sites ORDER BY created DESC";
$items = $db->query($sql)->result();
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
                    <th>Property Type</th>
                    <th>Property For</th>
                    <th>Price</th>
                    <th>Photo</th>
                    <th>Owner Info</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th>Action</th>
                    <th>Enquiry</th>
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
                            <?php if ($item->isPromoted) { ?>
                                <p class="text-success text-center" style="font-size: 12px;">Promoted</p>
                            <?php } ?>
                        </td>
                        <td><?= $dist->dist_name; ?></td>
                        <td><?= $zone->zone_name; ?></td>
                        <td><?= $item->property_type; ?></td>
                        <td><?= $item->property_for; ?></td>
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
                            if ($item->status == 3) echo '<span class="badge bg-danger">Sold</span>';
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
                        <td>
                            <a href="<?= admin_url('properties/property-enqury.php?id=' . $item->id); ?>" class="btn btn-xs btn-success ">Enquiry</a>
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
                <a href="<?= admin_url('properties/index.php?page=' . ($page + 1)) ?>" class="btn btn-sm btn-primary">Next</a>
            <?php
            } else if ($page >= $links) {
            ?>
                <a href="<?= admin_url('properties/index.php?page=' . ($page - 1)) ?>" class="btn btn-sm btn-primary">Previous</a>
                <button disabled class="btn btn-sm btn-primary">Next</button>
            <?php
            } else if ($links >= 1 && $page < $links) {
            ?>
                <a href="<?= admin_url('properties/index.php?page=' . ($page - 1)) ?>" class="btn btn-sm btn-primary">Previous</a>
                <a href="<?= admin_url('properties/index.php?page=' . ($page + 1)) ?>" class="btn btn-sm btn-primary">Next</a>
            <?php
            }
            ?>
        </div> -->
    </div>
</div>
<?php
include "../common/footer.php";
