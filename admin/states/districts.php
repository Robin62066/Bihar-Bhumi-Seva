<?php
include '../../config/autoload.php';
if (!is_admin_login()) redirect(admin_url('index.php'), 'You must login to continue');

$perm = getPermission(Permission::SETTINGS);

$action = isset($_GET['action']) ? $_GET['action'] : 'view';

if (isset($_POST['btn_add_dist'])) {
    $dist_name = $_POST['dist_name'];
    $db->insert("ai_districts", ['dist_name' => $dist_name]);
    set_flashdata('success_msg', 'Distrct added successfully');
    redirect(admin_url('states/districts.php'));
} else if (isset($_POST['btn_edit_dist'])) {
    $dist_name = $_POST['dist_name'];
    $id = $_GET['id'];
    $db->update("ai_districts", ['dist_name' => $dist_name], ['id' => $id]);
    set_flashdata('success_msg', 'Distrct updated successfully');
    redirect(admin_url('states/districts.php'));
} else if ($action == 'delete') {
    $id = $_GET['id'];
    $db->delete('ai_districts', ['id' => $id]);
    set_flashdata('success_msg', 'District deleted successfully');
    redirect(admin_url('states/districts.php'));
}
$menu = 'settings';
include '../common/header.php';
switch ($action) {
    case 'view':
        $results = $db->select('ai_districts', [], false, 'dist_name ASC');
        $items = $results->result_array();
?>
        <div class="page-header">
            <h5>All Districts</h5>
            <?php
            if ($perm->canCreateNew()) {
            ?>
                <a href="<?= admin_url('states/districts.php?action=add') ?>" class="btn btn-sm btn-primary"><i class="bi-plus"></i> Add New</a>
            <?php
            }
            ?>
        </div>
        <div class="card p-3">
            <table class="table data-table">
                <thead>
                    <tr>
                        <th>Sl</th>
                        <th>District name</th>
                        <th>Total Zones</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sl = 1;
                    foreach ($items as $row) {
                    ?>
                        <tr>
                            <td><?= $sl++; ?></td>
                            <td><?= $row['dist_name']; ?></td>
                            <td>
                                <a href="#">0</a>
                            </td>
                            <td>
                                <?php
                                if ($perm->canEdit()) {
                                ?>
                                    <a href="<?= admin_url('states/districts.php?action=edit&id=' . $row['id']) ?>" class="btn btn-primary btn-xs"> <i class="bi-pencil"></i> Edit</a>
                                <?php
                                }
                                ?>
                                <?php
                                if ($perm->canDelete()) {
                                ?>
                                    <a href="<?= admin_url('states/districts.php?action=delete&id=' . $row['id']) ?>" class="btn btn-danger btn-delete btn-xs"> <i class="bi-trash"></i> Del</a>
                                <?php
                                }
                                ?>
                            </td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    <?php
        break;
    case 'add':
    ?>
        <div class="page-header">
            <h5>Add Districts</h5>
            <a href="<?= admin_url('states/districts.php') ?>" class="btn btn-sm btn-primary"><i class="bi-list"></i> View All</a>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <form action="" method="post">
                    <div class="card">
                        <div class="card-body">
                            <div class="mb-2">
                                <label>District name</label>
                                <input required type="text" name="dist_name" class="form-control" />
                            </div>
                            <button type="submit" name="btn_add_dist" class="btn btn-sm btn-primary" value="Save">Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    <?php
        break;
    case 'edit':
        $id = $_GET['id'];
        $dist = $db->select('ai_districts', ['id' => $id])->row();
    ?>
        <div class="page-header">
            <h5>Edit Districts</h5>
            <a href="<?= admin_url('states/districts.php') ?>" class="btn btn-sm btn-primary"><i class="bi-chevron-left"></i> Cancel</a>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <form action="" method="post">
                    <div class="card">
                        <div class="card-body">
                            <div class="mb-2">
                                <label>District name</label>
                                <input required type="text" value="<?= $dist->dist_name; ?>" name="dist_name" class="form-control" />
                            </div>
                            <button type="submit" name="btn_edit_dist" class="btn btn-sm btn-primary" value="Save">Update</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
<?php
        break;
}
include "../common/footer.php";
