<?php
include '../../config/autoload.php';
if (!is_admin_login()) redirect(admin_url('index.php'), 'You must login to continue');

$items = $db->select('ai_media')->result();
$menu = 'cms';
include '../common/header.php';
$perm = getPermission(Permission::MEDIA);

?>
<div class="page-header">
    <h5>Staff Details</h5>
    <?php
    if ($perm->canCreateNew()) {
    ?>
        <a href="<?= admin_url('media/add-staff.php'); ?>" class="btn btn-sm btn-primary"><i class="bi-plus"></i> Add New</a>
    <?php
    }
    ?>
</div>
<div class="row">
    <div class="col-sm-6">
        <div class="card">
            <div class="card-header"><b>Staff Details</b></div>
            <form action="" method="post" class="p-3">
                <div class="row mb-3">
                    <div class="col-sm-4">
                        <label>Employee Code</label>
                        <input class="form-control" name="form[emp_code]" />
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-6">
                        <label>First name</label>
                        <input class="form-control" name="form[first_name]" />
                    </div>
                    <div class="col-sm-6">
                        <label>Last name</label>
                        <input class="form-control" name="form[last_name]" />
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-6">
                        <label>Email Id</label>
                        <input class="form-control" name="form[email_id]" />
                    </div>
                    <div class="col-sm-6">
                        <label>Mobile number</label>
                        <input class="form-control" name="form[mobile]" />
                    </div>
                </div>
                <div class="mb-3">
                    <label>Address</label>
                    <input class="form-control" name="form[address]" />
                </div>
                <div class="row mb-3">
                    <div class="col-sm-6">
                        <label>Salary</label>
                        <input class="form-control" name="form[salary]" />
                    </div>
                    <div class="col-sm-6">
                        <label>Joining Date</label>
                        <input type="date" class="form-control" name="form[doj]" />
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-6">
                        <label>Status</label>
                        <select class="form-select" name="form[emp_code]">
                            <option value="1">Active</option>
                            <option value="0">Draft</option>
                        </select>
                    </div>
                </div>
                <input type="hidden" name="btn_clicked" value="1" />
                <button class="btn btn-primary btn-submit">SAVE</button>
            </form>
        </div>
    </div>
</div>
<?php
include "../common/footer.php";
