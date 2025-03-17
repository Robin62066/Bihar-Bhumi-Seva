<?php
include '../../config/autoload.php';
if (!is_admin_login()) redirect(admin_url('index.php'), 'You must login to continue');
$id = admin_id();
if (isset($_POST['submit'])) {
    $form = $_POST['form'];
    $db->update('ai_admin', $form, ['id' => $id]);
    session()->set_flashdata('success', 'Profile details updated');
}
$user = $db->select('ai_admin', ['id' => $id])->row();
$menu = 'settings';
include '../common/header.php';
?>
<div class="page-header">
    <h5>Edit Profile</h5>
</div>
<div class="card p-3">
    <div class="page-header">
        <h5>Edit Profile</h5>
    </div>
    <div class="px-3">
        <form enctype="multipart/form-data" action="" method="post">
            <div class="row">
                <div class="col-sm-3">
                    <div class="card card-info mb-1 p-3">
                        <?php
                        $file = base_url('assets/img/avg.png');
                        if ($user->avatar != '') {
                            $file = base_url(upload_dir($user->avatar));
                        }
                        ?>
                        <img src="<?= $file; ?>" class="img-fluid circle" />
                    </div>
                    <div class="d-grid">
                        <input type="file" name="avatar" id="avatar" class="form-control">
                    </div>
                </div>
                <div class="col-sm-9">
                    <div class="card card-info p-3">
                        <div class="form-group row">
                            <label class="col-sm-3 control-label">Username</label>
                            <div class="col-sm-6">
                                <input type="text" value="<?= $user->username; ?>" disabled class="form-control">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 control-label">Name</label>
                            <div class="col-sm-6">
                                <input type="text" name="form[first_name]" value="<?= $user->first_name; ?>" class="form-control">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 control-label">Email Id</label>
                            <div class="col-sm-6">
                                <input type="text" name="form[email_id]" value="<?= $user->email_id; ?>" class="form-control">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 control-label">Phone Number</label>
                            <div class="col-sm-6">
                                <input type="text" name="form[phone_no]" value="<?= $user->phone_no; ?>" class="form-control">
                            </div>
                        </div>
                        <div class="row">
                            <label class="col-sm-3 control-label"> </label>
                            <div class="col-sm-6">
                                <input type="hidden" name="submit" value="Submit">
                                <button class="btn btn-primary btn-submit">Submit</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </div>
    </form>
</div>
</div>
<?php
include "../common/footer.php";
