<?php
include "../config/autoload.php";
if (!is_login()) redirect(base_url('login.php'), 'You must login to continue');

$user_id = user_id();
$items = $db->select('ai_mutations_app', ['user_id' => $user_id])->result();
include "../common/header.php";
?>
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
                $subpage = 'wishlist';
                include_once "dashboard-menu.php"; ?>
                <div class="col-sm-9">
                    <div id="origin">
                        <div class="bg-white p-3 rounded-1">
                            <div class="page-header heading">
                                <h5>Mutation Application</h5>
                                <div>
                                    <a href="<?= base_url('check-mutation.php') ?>" class="btn btn-sm btn-warning chkbtn"> Check Mutation Status</a>
                                    <a href="<?= base_url('online-mutation.php') ?>" class="btn btn-sm btn-primary applybtn"> Apply Mutations</a>
                                </div>
                            </div>
                            <div id="responsive" class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Sl</th>
                                            <th>Case No</th>
                                            <th>Deed Number</th>
                                            <th>Years</th>
                                            <th>Document</th>
                                            <th>Status</th>
                                            <th>Created</th>
                                            <th>Pay Status</th>
                                            <th>Mutation Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $sl = 1;
                                        foreach ($items as $item) {
                                            if ($item->case_no == '') {
                                                $case_no = "BS" . str_pad(time() . $item->id, 10, '0', STR_PAD_LEFT);
                                                $db->update("ai_mutations_app", ['case_no' => $case_no], ['id' => $item->id]);
                                                $item->case_no = $case_no;
                                            }
                                        ?>
                                            <tr>
                                                <td><?= $sl++; ?></td>
                                                <td><?= $item->case_no; ?></td>
                                                <td><?= $item->deed_no; ?></td>
                                                <td><?= $item->years; ?></td>
                                                <td>
                                                    <?php
                                                    if ($item->documents != '') {
                                                    ?>
                                                        <a href="<?= base_url(upload_dir($item->documents)) ?>" download>
                                                            Download
                                                        </a>
                                                    <?php
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    if ($item->status == 0) echo '<span class="badge bg-warning">Pending</span>';
                                                    if ($item->status == 1) echo '<span class="badge bg-success">Approved</span>';
                                                    if ($item->status == 2) echo '<span class="badge bg-danger">Rejected</span>';
                                                    ?>
                                                </td>
                                                <td><?= $item->created; ?></td>
                                                <td>
                                                    <?php
                                                    if ($item->pay_status == 0 && $item->status == 0) {
                                                    ?>
                                                        <a href="<?= site_url('apply-confirm.php?order=' . $item->id) ?>" class="btn btn-xs btn-outline-primary">Pay Now</a>
                                                    <?php
                                                    } else if ($item->pay_status == 1) {
                                                    ?>
                                                        <button disabled class="btn btn-xs btn-success-outline">Paid</button>
                                                    <?php
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    if ($item->pay_status == 1 || $item->status == 1) {
                                                    ?>
                                                        <a href="<?= site_url('check-mutation.php?case=' . $item->case_no); ?>" class="btn btn-xs btn-outline-info">Mutation Status</a>
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

                            <!-- This Cards data show only in mobile  -->
                            <div id="mobile-cards" class="mobile-cards">
                                <?php
                                $sl = 1;
                                foreach ($items as $item) {
                                    if ($item->case_no == '') {
                                        $case_no = "BS" . str_pad(time() . $item->id, 10, '0', STR_PAD_LEFT);
                                        $db->update("ai_mutations_app", ['case_no' => $case_no], ['id' => $item->id]);
                                        $item->case_no = $case_no;
                                    }
                                ?>
                                    <div class="card">
                                        <div class="card-item"><strong>Sl:</strong> <?= $sl++; ?></div>
                                        <div class="card-item"><strong>Case No:</strong> <?= $item->case_no; ?></div>
                                        <div class="card-item"><strong>Deed Number:</strong> <?= $item->deed_no; ?></div>
                                        <div class="card-item"><strong>Years:</strong> <?= $item->years; ?></div>
                                        <div class="card-item">
                                            <strong>Document:</strong>
                                            <?php
                                            if ($item->documents != '') {
                                            ?>
                                                <a href="<?= base_url(upload_dir($item->documents)) ?>" download>
                                                    Download
                                                </a>
                                            <?php
                                            }
                                            ?>
                                        </div>
                                        <div class="card-item"><strong>Status:</strong>
                                            <?php
                                            if ($item->status == 0) echo '<span class="badge bg-warning">Pending</span>';
                                            if ($item->status == 1) echo '<span class="badge bg-success">Approved</span>';
                                            if ($item->status == 2) echo '<span class="badge bg-danger">Rejected</span>';
                                            ?>
                                        </div>
                                        <div class="card-item"><strong>Created:</strong> <?= $item->created; ?></div>
                                        <div class="card-item"><strong>Pay Status:</strong>
                                            <?php
                                            if ($item->pay_status == 0 && $item->status == 0) {
                                            ?>
                                                <a href="<?= site_url('apply-confirm.php?order=' . $item->id) ?>" class="btn btn-xs btn-outline-primary">Pay Now</a>
                                            <?php
                                            } else if ($item->pay_status == 1) {
                                            ?>
                                                <button disabled class="btn btn-xs btn-success-outline">Paid</button>
                                            <?php
                                            }
                                            ?>
                                        </div>
                                        <div class="card-item"><strong>Mutation Status:</strong>
                                            <?php
                                            if ($item->pay_status == 1 || $item->status == 1) {
                                            ?>
                                                <a href="<?= site_url('check-mutation.php?case=' . $item->case_no); ?>" class="btn btn-xs btn-outline-info">Mutation Status</a>
                                            <?php
                                            }
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
