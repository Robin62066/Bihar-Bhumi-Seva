 <?php
include "../config/autoload.php";
include "../common/header.php";
?>
<div class="dashboard">
    <div class="container py-5">
        <div class="user-panel">
            <div class="row">
                <?php
                $subpage = 'properties';
                include_once "dashboard-menu.php"; ?>
                <div class="col-sm-9">
                    <?= front_view('common/alert'); ?>
                    <div class="page-header">
                        <h5>Page title</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
include "../common/footer.php";
