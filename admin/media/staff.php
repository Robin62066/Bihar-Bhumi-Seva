<?php
include '../../config/autoload.php';
if (!is_admin_login()) redirect(admin_url('index.php'), 'You must login to continue');

if (isset($_GET['act']) && $_GET['act'] == 'del') {
    $id = $_GET['id'];
    $db->delete('ai_media', ['id' => $id]);
    set_flashdata("success", "Media Item deleted");
}
$items = $db->select('ai_media')->result();
$menu = 'cms';
include '../common/header.php';
$perm = getPermission(Permission::MEDIA);

?>
<div class="page-header">
    <h5>Staff Manager</h5>
    <?php
    if ($perm->canCreateNew()) {
    ?>
        <a href="<?= admin_url('media/add-staff.php'); ?>" class="btn btn-sm btn-primary"><i class="bi-plus"></i> Add New</a>
    <?php
    }
    ?>
</div>
<div class="card">
    <div class="card-body">
        <table class="table data-table">
            <thead>
                <tr>
                    <th>Sl</th>
                    <th>Name</th>
                    <th>Empcode</th>
                    <th>Mobile</th>
                    <th>Email Id</th>
                    <th>Address</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>
</div>
<?php
include "../common/footer.php";
