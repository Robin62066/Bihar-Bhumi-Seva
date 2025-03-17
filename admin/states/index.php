<?php
include '../../config/autoload.php';

$menu = 'settings';
include '../common/header.php';
$results = $db->select('ai_states');
$items = $results->result_array();
?>
<div class="page-header">
    <h5>All States</h5>
    <!-- <a href="#" class="btn btn-sm btn-primary"><i class="bi-plus"></i> Upload</a> -->
</div>
<div class="card">
    <table class="table">
        <thead>
            <tr>
                <th>Sl</th>
                <th>State name</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sl = 1;
            foreach ($items as $row) {
            ?>
                <tr>
                    <td><?= $sl++; ?></td>
                    <td><?= $row['state_name']; ?></td>

                </tr>
            <?php
            }
            ?>
        </tbody>
    </table>
</div>
<?php
include "../common/footer.php";
