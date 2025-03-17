<?php
include "../config/autoload.php";
if (!is_login()) redirect(base_url('login.php'), 'You must login to continue');

if (isset($_GET['act']) && $_GET['act'] == 'del') {
    $id = $_GET['id'];
    $db->delete('ai_leads', ['id' => $id]);
    session()->set_flashdata('success', "Item Deleted Successfully");
}

$prop_ids = [];
$ar_sites = $db->select('ai_sites', ['user_id' => user_id()])->result();
foreach ($ar_sites as $as) {
    $prop_ids[] = $as->id;
}
if (count($prop_ids) > 0) {
    $str = implode(',', $prop_ids);
    $sql = "SELECT * FROM ai_leads WHERE prop_id IN ($str)";
    $leads = $db->query($sql)->result();
} else {
    $leads = [];
}
include "../common/header.php";
?>

<div class="dashboard">
    <div class="container py-5">
        <div class="user-panel">
            <div class="row">
                <?php
                $subpage = 'leads';
                include_once "dashboard-menu.php"; ?>
                <div class="col-sm-9 ">
                    <?= front_view('common/alert'); ?>
                    <div class="bg-white p-3 rounded-1 card-details">
                        <div class="page-header">
                            <h5>Leads/Enquiries</h5>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Sl</th>
                                        <th>Customer name</th>
                                        <th>Mobile no</th>
                                        <th>Details</th>
                                        <th>Created</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sl = 1;
                                    foreach ($leads as $item) {
                                    ?>
                                        <tr>
                                            <td><?= $sl++; ?></td>
                                            <td><?= $item->first_name . ' ' . $item->last_name; ?></td>
                                            <td><?= $item->mobile; ?></td>
                                            <td><?= $item->details; ?></td>
                                            <td><?= $item->created; ?></td>
                                            <td><?php
                                                if ($item->status == 0) echo '<span class="badge bg-warning">Pending</span>';
                                                if ($item->status == 1) echo '<span class="badge bg-success">Viewd</span>';
                                                ?></td>
                                            <td>
                                                <a href="<?= site_url('dashboard/leads.php?id=' . $item->id . '&act=del') ?>" class="btn btn-xs btn-danger btn-confirm" data-msg="Are you sure to Delete?">Del</a>
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
<?php
include "../common/footer.php";
