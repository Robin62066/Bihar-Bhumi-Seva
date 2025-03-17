<?php
include_once "../../config/autoload.php";
if (!is_admin_login()) redirect(admin_url('index.php'), 'You must login to continue');

$items = [];
$menu = 'reports';
include "../common/header.php";

?>
<div id="origin">
    <div class="page-header">
        <h5>Payment Reports</h5>
    </div>
    <div class="bg-white p-3">
        <table class="table m-0">
            <thead>
                <tr>
                    <th>Sl</th>
                    <th>Name</th>
                    <th>Amount</th>
                    <th>Payment For</th>
                    <th>Status</th>
                    <th>Signup</th>
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
