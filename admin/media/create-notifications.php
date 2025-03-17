<?php
include '../../config/autoload.php';
if (!is_admin_login()) redirect(admin_url('index.php'), 'You must login to continue');

$menu = 'cms';
if (isset($_POST['clicked'])) {

    $sb = [];
    $sb['title'] = $_POST['title'];
    $sb['sendto'] = $_POST['sendto'];
    $sb['message'] = $_POST['message'];
    $sb['created'] = date("Y-m-d H:i:s");
    $sb['deleted'] = 0;
    $db->insert("ai_notifications", $sb);

    redirect(admin_url("media/create-notifications.php"), "Account created and login sent successfully", "success");
}
include '../common/header.php';
?>
<div class="page-header">
    <h5>Create Notification</h5>
    <?php
    if ($perm->canCreateNew()) {
    ?>
        <a href="<?= admin_url('media/create-notifications.php'); ?>" class="btn btn-sm btn-primary"><i class="bi-plus"></i> Create</a>
    <?php
    }
    ?>
</div>
<div class="row">
    <div class="col-sm-7">
        <form action="" method="post">
            <div class="card">
                <div class="card-header">Create Message</div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="row">
                            <div class="col-sm-6">
                                <label>Send To</label>
                                <select name="sendto" class="form-select">
                                    <option value="">Select</option>
                                    <option value="all">All Users</option>
                                    <option value="one">One User</option>
                                    <option value="many">Many Users</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label>Title</label>
                        <input type="text" name="title" required class="form-control" />
                    </div>
                    <div class="mb-3">
                        <label>Message</label>
                        <textarea rows="3" name="message" required class="form-control"></textarea>
                    </div>
                    <input type="hidden" name="clicked" value="1">
                    <button class="btn btn-primary btn-submit">Send Now</button>
                </div>
            </div>
        </form>
    </div>
</div>
<?php
include "../common/footer.php";
