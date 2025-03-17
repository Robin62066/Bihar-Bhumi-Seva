<?php
include_once "../../config/autoload.php";
if (!is_admin_login()) redirect(admin_url('index.php'), 'You must login to continue');

if (isset($_GET['act']) && $_GET['act'] == 'del') {
    $id = $_GET['id'];
    $db->delete('ai_leads', ['id' => $id]);
    session()->set_flashdata('success', "Item Deleted Successfully");
}

$leads = $db->select("ai_leads", [], false, "id DESC")->result();
$menu = 'reports';
include "../common/header.php";
?>
<div id="origin">
    <div class="page-header">
        <h5>Leads Reports</h5>
    </div>
    <div class="bg-white p-3">
        <table class="table table-sm data-table">
            <thead>
                <tr>
                    <th>Sl</th>
                    <th>Customer name</th>
                    <th>Mobile no</th>
                    <th>Property Id</th>
                    <th>Details</th>
                    <th>Email Id</th>
                    <th>Created</th>
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
                        <td>
                            <a href="<?= admin_url('properties/details.php?id=' . $item->prop_id) ?>">#<?= $item->prop_id; ?></a>
                        </td>
                        <td><?= $item->details; ?></td>
                        <td><?= $item->email_id; ?></td>
                        <td><?= $item->created; ?></td>

                        <td>
                            <a href="<?= admin_url('reports/leads.php?id=' . $item->id . '&act=del') ?>" class="btn btn-xs btn-danger btn-confirm" data-msg="Are you sure to Delete?">Del</a>
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
