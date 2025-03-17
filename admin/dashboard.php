<?php
include_once "../config/autoload.php";
if (!is_admin_login()) redirect(admin_url('index.php'), 'You must login to continue');

$tr = $db->select("ai_users", [], false, false, 'AND', "count(id) as c")->row()->c;
$tu = $db->select("ai_users", ['user_type' => USER_CUSTOMER], false, false, 'AND', "count(id) as c")->row()->c;
$tlw = $db->select("ai_users", ['user_type' => USER_LAND_OWNER], false, false, 'AND', "count(id) as c")->row()->c;
$tb = $db->select("ai_users", ['user_type' => USER_BROKER], false, false, 'AND', "count(id) as c")->row()->c;
$tbl = $db->select("ai_users", ['user_type' => USER_BHUMI_LOCKER], false, false, 'AND', "count(id) as c")->row()->c;
$tco = $db->select("ai_users", ['user_type' => USER_CO], false, false, 'AND', "count(id) as c")->row()->c;
include_once "common/header.php";
?>
<div class="page-header">
    <h5>Dashboard</h5>
</div>
<div id="origin" class="dashboard text-white">
    <?php include "../common/alert.php"; ?>
    <div class="row g-2">
        <div class="col-sm-3">
            <div class="box bg-primary border-0">
                <div class="p-3 d-flex justify-content-between align-items-center">
                    <div>
                        <h6>Total Registration</h6>
                        <h2 class="m-0"><?= $tr; ?></h2>
                    </div>
                    <div>
                        <i class="bi-person fa-3x"></i>
                    </div>
                </div>
                <div class="box-footer p-2 box-footer-dark">
                    <a href="#" class="btn btn-sm btn-outline-light">View More</a>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="box bg-primary border-0">
                <div class="p-3 d-flex justify-content-between align-items-center">
                    <div>
                        <h6>Total Customers</h6>
                        <h2 class="m-0"><?= $tu; ?></h2>
                    </div>
                    <div>
                        <i class="bi-person fa-3x"></i>
                    </div>
                </div>
                <div class="box-footer p-2 box-footer-dark">
                    <a href="#" class="btn btn-sm btn-outline-light">View More</a>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="box bg-primary border-0">
                <div class="p-3 d-flex justify-content-between align-items-center">
                    <div>
                        <h6>Total Landowner</h6>
                        <h2 class="m-0"><?= $tlw; ?></h2>
                    </div>
                    <div>
                        <i class="bi-bag fa-3x"></i>
                    </div>
                </div>
                <div class="box-footer p-2 box-footer-dark">
                    <a href="#" class="btn btn-sm btn-outline-light">View More</a>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="box bg-primary border-0">
                <div class="p-3 d-flex justify-content-between align-items-center">
                    <div>
                        <h6>Total Broker/Builder</h6>
                        <h2 class="m-0"><?= $tb; ?></h2>
                    </div>
                    <div>
                        <i class="bi-bag fa-3x"></i>
                    </div>
                </div>
                <div class="box-footer p-2 box-footer-dark">
                    <a href="#" class="btn btn-sm btn-outline-light">View More</a>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="box bg-primary border-0">
                <div class="p-3 d-flex justify-content-between align-items-center">
                    <div>
                        <h6>Total BhumiLocker</h6>
                        <h2 class="m-0"><?= $tbl; ?></h2>
                    </div>
                    <div>
                        <i class="bi-bag fa-3x"></i>
                    </div>
                </div>
                <div class="box-footer p-2 box-footer-dark">
                    <a href="#" class="btn btn-sm btn-outline-light">View More</a>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="box bg-primary border-0">
                <div class="p-3 d-flex justify-content-between align-items-center">
                    <div>
                        <h6>Total CO Regd.</h6>
                        <h2 class="m-0"><?= $tco; ?></h2>
                    </div>
                    <div>
                        <i class="bi-bag fa-3x"></i>
                    </div>
                </div>
                <div class="box-footer p-2 box-footer-dark">
                    <a href="#" class="btn btn-sm btn-outline-light">View More</a>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="box bg-primary border-0">
                <div class="p-3 d-flex justify-content-between align-items-center">
                    <div>
                        <h6>Total Cases Regd.</h6>
                        <h2 class="m-0">-</h2>
                    </div>
                    <div>
                        <i class="bi-bag fa-3x"></i>
                    </div>
                </div>
                <div class="box-footer p-2 box-footer-dark">
                    <a href="#" class="btn btn-sm btn-outline-light">View More</a>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="box bg-primary border-0">
                <div class="p-3 d-flex justify-content-between align-items-center">
                    <div>
                        <h6>Total Cases In-Progress</h6>
                        <h2 class="m-0">-</h2>
                    </div>
                    <div>
                        <i class="bi-bag fa-3x"></i>
                    </div>
                </div>
                <div class="box-footer p-2 box-footer-dark">
                    <a href="#" class="btn btn-sm btn-outline-light">View More</a>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="box bg-primary border-0">
                <div class="p-3 d-flex justify-content-between align-items-center">
                    <div>
                        <h6>Total Cases Processed.</h6>
                        <h2 class="m-0">-</h2>
                    </div>
                    <div>
                        <i class="bi-bag fa-3x"></i>
                    </div>
                </div>
                <div class="box-footer p-2 box-footer-dark">
                    <a href="#" class="btn btn-sm btn-outline-light">View More</a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
include_once "common/footer.php";
