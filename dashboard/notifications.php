<?php
include "../config/autoload.php";
if (!is_login()) redirect(base_url('login.php'), 'You must login to continue');
if (isset($_GET['act']) && $_GET['act'] == 'del') {
    $id = $_GET['id'];
    $db->delete('ai_notifications', ['id' => $id]);
    set_flashdata("success", "Notification Item deleted");
}
$items = $db->select('ai_notifications', [], false, 'id DESC')->result();
include "../common/header.php";
?>
<div class="dashboard">
    <div class="container py-5">
        <div class="user-panel">
            <div class="row">
                <?php
                $subpage = 'notifications';
                include_once "dashboard-menu.php"; ?>
                <div class="col-sm-9">

                    <div id="origin">
                        <div class="bg-white p-3 rounded-1">
                            <div class="page-header">
                                <h5>Notifications</h5>
                            </div>
                            <?php foreach ($items as $item) { ?>
                                <div class="card-body mb-3">
                                    <div class="row">
                                        <div class="col-sm-6 mb-3">
                                            <h5 class="card-title"><?= $item->title ?></h5>
                                        </div>
                                        <div class="col-sm-6 d-flex justify-content-end">
                                            <a href="<?= base_url('dashboard/nootifications.php?id=' . $item->id . '&act=del'); ?>" class="btn btn-danger btn-confirm" data-msg="Are you sure to Delete?">Delete</a>
                                        </div>
                                        <div>
                                            <b><?= $item->message; ?></b>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                            <?php } ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
include "../common/footer.php";
