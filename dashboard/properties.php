<?php
include "../config/autoload.php";
if (!is_login()) redirect(base_url('login.php'), 'Please login to continue');

$user_id = user_id();
$sites = $db->select('ai_sites', ['user_id' => $user_id])->result();
include "../common/header.php";
?>
<script>
    var apiUrl = '<?= base_url('api.php') ?>'
    $(document).ready(function() {
        $('.select2').select2();
    });
</script>
<style>
    #mobile-cards {
        display: none;
    }

    @media (max-width: 768px) {
        #responsive {
            display: none;
        }

        #mobile-cards {
            display: block;
        }

        .mobile-cards {
            display: flex;
            flex-direction: column;
            padding: 10px;
        }

        .card {
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-bottom: 10px;
            padding: 15px;
            background-color: #f9f9f9;
        }

        .card-item {
            margin-bottom: 10px;
        }

        .chkbtn {
            font-size: 14px;

        }

        .applybtn {
            font-size: 14px;
        }
    }
</style>
<div class="dashboard">
    <div class="container py-5">
        <div class="user-panel">
            <div class="row">
                <?php
                $subpage = 'properties';
                include_once "dashboard-menu.php"; ?>
                <div class="col-sm-9">
                    <?= front_view('common/alert'); ?>
                    <div class="bg-white p-3 rounded-1">
                        <div class="page-header">
                            <h5>Properties</h5>
                            <!-- <a href="<?= base_url('sell-property.php') ?>" class="btn btn-sm btn-primary">Sell Property</a> -->
                            <a href="#" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#SellForm">Sell Property</a>
                        </div>
                        <div id="responsive" class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Sl</th>
                                        <th>#</th>
                                        <th>Title</th>
                                        <th>Location</th>
                                        <th>Khata no</th>
                                        <th>Total Area</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sl = 1;
                                    foreach ($sites as $item) {
                                        $editLink = base_url('property-details.php?id=' . $item->id);
                                        if ($item->status == 1) {
                                            $editLink = base_url('property-view.php?id=' . $item->id);
                                        }
                                    ?>
                                        <tr>
                                            <td><?= $sl++; ?></td>
                                            <td>
                                                <a href="<?= $editLink; ?>"><?= $item->id; ?></a>
                                            </td>
                                            <td><?= $item->site_title; ?></td>
                                            <td><?= $item->address; ?></td>
                                            <td><?= $item->khata_no; ?></td>
                                            <td><?= $item->total_area; ?> <?= $item->area_unit; ?></td>
                                            <td><?= $item->total_amount; ?></td>
                                            <td>
                                                <?php
                                                if ($item->status == 0) echo '<span class="badge bg-warning">Pending</span>';
                                                if ($item->status == 1) echo '<span class="badge bg-success">Active</span>';
                                                if ($item->status == 2) echo '<span class="badge bg-danger">Rejected</span>';
                                                ?>
                                            </td>
                                        </tr>
                                    <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                        <!-- This Cards data show only in mobile  -->
                        <div id="mobile-cards" class="mobile-cards">
                            <?php
                            $sl = 1;
                            foreach ($sites as $item) {
                                $editLink = base_url('property-details.php?id=' . $item->id);
                                if ($item->status == 1) {
                                    $editLink = base_url('property-view.php?id=' . $item->id);
                                }
                            ?>
                                <div class="card">
                                    <div class="card-item"><strong>Sl:</strong> <?= $sl++; ?></div>
                                    <div class="card-item"><strong>#</strong> <a href="<?= $editLink; ?>"><?= $item->id; ?></a></div>
                                    <div class="card-item"><strong>Title:</strong> <?= $item->site_title; ?></div>
                                    <div class="card-item"><strong>Location:</strong> <?= $item->site_title; ?></div>
                                    <div class="card-item"><strong>Khata no:</strong> <?= $item->khata_no; ?></div>
                                    <div class="card-item"><strong>Total Area:</strong><?= $item->total_area; ?> <?= $item->area_unit; ?> </div>
                                    <div class="card-item"><strong>Amount:</strong> <?= $item->total_amount; ?> </div>
                                    <div class="card-item"><strong>Status:</strong>
                                        <?php
                                        if ($item->status == 0) echo '<span class="badge bg-warning">Pending</span>';
                                        if ($item->status == 1) echo '<span class="badge bg-success">Active</span>';
                                        if ($item->status == 2) echo '<span class="badge bg-danger">Rejected</span>';
                                        ?>
                                    </div>
                                </div>
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
