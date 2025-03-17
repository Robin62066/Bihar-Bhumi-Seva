<?php
include '../../config/autoload.php';
if (!is_admin_login()) redirect(admin_url('index.php'), 'You must login to continue');



$action = isset($_GET['action']) ? $_GET['action'] : 'view';

if (isset($_POST['btn_add_zone'])) {
    $zone_name = $_POST['zone_name'];
    $dist_id = $_POST['dist_id'];
    $db->insert("ai_zones", ['dist_id' => $dist_id, 'zone_name' => $zone_name]);
    set_flashdata('success_msg', 'Zones added successfully');
    redirect(admin_url('states/dzones.php'));
} else if (isset($_POST['btn_edit_zone'])) {
    $zone_name = $_POST['zone_name'];
    $dist_id = $_POST['dist_id'];
    $id = $_GET['id'];
    $db->update("ai_zones", ['dist_id' => $dist_id, 'zone_name' => $zone_name], ['id' => $id]);
    set_flashdata('success_msg', 'Zones updated successfully');
    redirect(admin_url('states/dzones.php'));
} else if ($action == 'delete') {
    $id = $_GET['id'];
    $db->delete('ai_zones', ['id' => $id]);
    set_flashdata('success_msg', 'Zones deleted successfully');
    redirect(admin_url('states/dzones.php'));
}
$menu = 'settings';
include '../common/header.php';
switch ($action) {
    case 'view':
        $results = $db->select('ai_zones', [], false, 'zone_name ASC');
        $items = $results->result();
?>
        <div class="page-header">
            <h5>All Zones</h5>
            <a href="<?= admin_url('states/dzones.php?action=add') ?>" class="btn btn-sm btn-primary"><i class="bi-plus"></i> Add New</a>
        </div>
        <div class="card p-3">
            <table class="table data-table">
                <thead>
                    <tr>
                        <th>Sl</th>
                        <th>Zone name</th>
                        <th>District</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sl = 1;
                    foreach ($items as $row) {
                        $dt = $db->select('ai_districts', ['id' => $row->dist_id], 1)->row();
                        if ($dt == null) continue;
                    ?>
                        <tr>
                            <td><?= $sl++; ?></td>
                            <td><?= $row->zone_name; ?></td>
                            <td><?= $dt->dist_name; ?></td>
                            <td>
                                <a href="<?= admin_url('states/dzones.php?action=edit&id=' . $row->id) ?>" class="btn btn-primary btn-xs"> <i class="bi-pencil"></i> Edit</a>
                                <a href="<?= admin_url('states/dzones.php?action=delete&id=' . $row->id) ?>" class="btn btn-danger btn-delete btn-xs"> <i class="bi-trash"></i> Del</a>
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
        $dists = $db->select('ai_districts', [], false, 'dist_name ASC')->result();
    ?>
        <div class="page-header">
            <h5>Add Zone</h5>
            <a href="<?= admin_url('states/districts.php') ?>" class="btn btn-sm btn-primary"><i class="bi-list"></i> View All</a>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <form action="" method="post">
                    <div class="card">
                        <div class="card-body">
                            <div class="mb-2">
                                <label>Select District</label>
                                <select required name="dist_id" class="form-control">
                                    <?php
                                    foreach ($dists as $dist) {
                                    ?>
                                        <option value="<?= $dist->id; ?>"><?= $dist->dist_name; ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="mb-2">
                                <label>Zone name</label>
                                <input required type="text" name="zone_name" class="form-control" />
                            </div>
                            <button type="submit" name="btn_add_zone" class="btn btn-sm btn-primary" value="Save">Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    <?php
        break;
    case 'edit':
        $id = $_GET['id'];
        $dists = $db->select('ai_districts', [], false, 'dist_name ASC')->result();
        $zone = $db->select('ai_zones', ['id' => $id])->row();
    ?>
        <div class="page-header">
            <h5>Edit Zones</h5>
            <a href="<?= admin_url('states/dzones.php') ?>" class="btn btn-sm btn-primary"><i class="bi-chevron-left"></i> Cancel</a>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <form action="" method="post">
                    <div class="card">
                        <div class="card-body">
                            <div class="mb-2">
                                <label>Select District</label>
                                <select required name="dist_id" class="form-control">
                                    <?php
                                    foreach ($dists as $dist) {
                                    ?>
                                        <option value="<?= $dist->id; ?>" <?= $zone->dist_id == $dist->id ? 'selected' : ''; ?>><?= $dist->dist_name; ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="mb-2">
                                <label>District name</label>
                                <input required type="text" value="<?= $zone->zone_name; ?>" name="zone_name" class="form-control" />
                            </div>
                            <button type="submit" name="btn_edit_zone" class="btn btn-sm btn-primary" value="Save">Update</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
<?php
        break;
}
include "../common/footer.php";
