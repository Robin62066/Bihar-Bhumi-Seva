<?php
include_once "../../config/autoload.php";
if (!is_admin_login()) redirect(admin_url('index.php'), 'You must login to continue');

$title = 'Open Complains';
$status = input_get('status');
if ($status == null) {
    $items = $db->select("ai_complains", ['status' => 0], [], "id DESC")->result();
    $title = "Closed Complains";
} else {
    $items = $db->select("ai_complains", ['status' => 1], [], "id DESC")->result();
}

$menu = 'supports';
include "../common/header.php";

?>
<div id="origin">
    <div class="page-header">
        <h5><?= $title; ?></h5>
    </div>
    <div class="bg-white p-3">
        <table class="table m-0">
            <thead>
                <tr>
                    <th>Sl</th>
                    <th>Created</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Mobile</th>
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
                        <td><?= $item->created; ?></td>
                        <td><?= $item->yourname; ?></td>
                        <td><?= $item->email_id; ?></td>
                        <td><?= $item->mobile; ?></td>
                        <td>
                            <?php
                            if ($item->status == 1) echo '<span class="badge bg-primary">Open</span>';
                            if ($item->status == 0) echo '<span class="badge bg-dark">Closed</span>';
                            ?>
                        </td>
                        <td>
                            <a href="<?= admin_url('supports/details.php?ticket_id=' . $item->id); ?>" class="btn btn-xs btn-outline-primary">View Details</a>
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
