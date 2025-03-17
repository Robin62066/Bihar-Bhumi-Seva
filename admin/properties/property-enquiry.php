<?php
include_once "../../config/autoload.php";
if (!is_admin_login()) redirect(admin_url('index.php'), 'You must login to continue');

$id = isset($_GET['id']) ? $_GET['id'] : null;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page = max($page, 1);
$limit = 50;
$offset = ($page - 1) * $limit;
$menu = 'properties';
if (isset($_GET['act']) && $_GET['act'] == 'del') {
    $del_id = $_GET['del_id'];
    $db->delete('ai_property_enquiry', ['sl' => $del_id]);
    set_flashdata("success", "Record is deleted");
}
$sql = "SELECT * FROM ai_property_enquiry ORDER BY created DESC LIMIT $limit OFFSET $offset";
$items = $db->query($sql)->result();

$total = $db->query("SELECT COUNT(*) as total FROM ai_property_enquiry")->row()->total;
$links = ceil($total / $limit);
// $items = $db->select('ai_property_enquiry', [], false, "created DESC")->result();

include "../common/header.php";

$perm = getPermission();
$perm->setModule(Permission::PROPERTIES);
?>
<div id="origin">
    <div class="page-header">
        <h5> Property Enqury Details </h5>
    </div>

    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <th>Sl</th>
                                <th>Name</th>
                                <th>Mobile Number</th>
                                <th>District</th>
                                <th>Query</th>
                                <th>Created</th>
                                <th>Actions</th>

                            </thead>
                            <tbody>
                                <?php
                                $sl = 1;
                                foreach ($items as $item) {
                                ?>
                                    <tr>
                                        <td><?= $sl++ ?></td>
                                        <td><?= $item->name ?></td>
                                        <td><?= $item->mobile ?></td>
                                        <td><?= $item->dist ?></td>
                                        <td><?= $item->query ?></td>
                                        <td><?= $item->created ?></td>
                                        <td>
                                            <div>
                                                <?php
                                                if ($perm->canDelete()) {
                                                ?>
                                                    <a href="<?= admin_url('properties/property-enquiry.php?del_id=' . $item->sl); ?>&act=del&id=<?= $item->sl; ?>" class="btn btn-xs btn-danger btn-delete">Delete</a>
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
                                <a href="<?= admin_url('properties/property-enquiry.php?page=' . ($page + 1)) ?>" class="btn btn-sm btn-primary">Next</a>
                            <?php
                            } else if ($page >= $links) {
                            ?>
                                <a href="<?= admin_url('properties/property-enquiry.php?page=' . ($page - 1)) ?>" class="btn btn-sm btn-primary">Previous</a>
                                <button disabled class="btn btn-sm btn-primary">Next</button>
                            <?php
                            } else if ($links >= 1 && $page < $links) {
                            ?>
                                <a href="<?= admin_url('properties/property-enquiry.php?page=' . ($page - 1)) ?>" class="btn btn-sm btn-primary">Previous</a>
                                <a href="<?= admin_url('properties/property-enquiry.php?page=' . ($page + 1)) ?>" class="btn btn-sm btn-primary">Next</a>
                            <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
include "../common/footer.php";
