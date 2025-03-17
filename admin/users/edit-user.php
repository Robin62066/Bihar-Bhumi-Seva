<?php
include '../../config/autoload.php';
if (!is_admin_login()) redirect(admin_url('index.php'), 'You must login to continue');

$user_id = $_GET['id'];
$user = $db->select('ai_users', ['id' => $user_id])->row();

if (isset($_POST['btn_update'])) {

    $fields = $_POST['form'];
    $fields['kyc_status'] = $fields['admin_verified'];
    $db->update('ai_users', $fields, ['id' => $user_id]);
    set_flashdata('success_msg', 'Profile updated successfully');
    redirect(admin_url('users/edit-user.php?id=' . $user_id));
}
$menu = 'members';
include "../common/header.php";
?>
<div class="page-header">
    <h5>Edit Profile</h5>
</div>
<form method="post">
    <div class="row">
        <div class="col-sm-8">
            <div class="card p-3">
                <div class="row mb-3">
                    <div class="col-sm-6">
                        <label>First name</label>
                        <input name="form[first_name]" required type="text" value="<?= $user->first_name; ?>" class="form-control">
                    </div>
                    <div class="col-sm-6">
                        <label>Last name</label>
                        <input name="form[last_name]" required type="text" value="<?= $user->last_name; ?>" class="form-control">
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-6">
                        <label>Address</label>
                        <textarea name="form[address]" rows="4" type="text" class="form-control"><?= $user->address; ?></textarea>
                    </div>
                    <div class="col-sm-6">
                        <label>City</label>
                        <input name="form[city]" type="text" value="<?= $user->city; ?>" class="form-control">
                        <label>Pincode</label>
                        <input name="form[pincode]" type="text" value="<?= $user->pincode; ?>" class="form-control">
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-6">
                        <label>Gender</label>
                        <select name="form[gender]" class="form-select">
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                        </select>
                    </div>
                    <div class="col-sm-6">
                        <label>Password</label>
                        <input name="form[passwd]" type="text" value="<?= $user->passwd; ?>" class="form-control">
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-6">
                        <label>Email Id</label>
                        <input name="form[email_id]" type="text" value="<?= $user->email_id; ?>" class="form-control">
                    </div>
                    <div class="col-sm-6">
                        <label>Mobile no</label>
                        <input name="form[mobile_number]" type="text" value="<?= $user->mobile_number; ?>" class="form-control">
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-6">
                        <label>PAN Number</label>
                        <input name="form[pan_number]" type="text" value="<?= $user->pan_number; ?>" class="form-control">
                    </div>
                    <div class="col-sm-6">
                        <label>PAN Name</label>
                        <input name="form[pan_name]" type="text" value="<?= $user->pan_name; ?>" class="form-control">
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-6">
                        <label>Aadhar Number</label>
                        <input name="form[aadhar_number]" type="text" value="<?= $user->aadhar_number; ?>" class="form-control">
                    </div>
                    <div class="col-sm-6">
                        <label>Aadhar Name</label>
                        <input name="form[aadhar_name]" type="text" value="<?= $user->aadhar_name; ?>" class="form-control">
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-6">
                        <label>PAN Verified</label>
                        <select name="form[pan_verified]" class="form-select">
                            <option <?= $user->pan_verified == 1 ? 'selected' : ''; ?> value="1">Yes</option>
                            <option <?= $user->pan_verified == 0 ? 'selected' : ''; ?> value="0">No</option>
                        </select>
                    </div>
                    <div class="col-sm-6">
                        <label>Aadhar Verified</label>
                        <select name="form[aadhar_verified]" class="form-select">
                            <option <?= $user->aadhar_verified == 1 ? 'selected' : ''; ?> value="1">Yes</option>
                            <option <?= $user->aadhar_verified == 0 ? 'selected' : ''; ?> value="0">No</option>
                        </select>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-6">
                        <label>KYC Verified</label>
                        <select name="form[admin_verified]" class="form-select">
                            <option <?= $user->admin_verified == 1 ? 'selected' : ''; ?> value="1">Verified</option>
                            <option <?= $user->admin_verified == 0 ? 'selected' : ''; ?> value="0">Pending</option>
                            <option <?= $user->admin_verified == 2 ? 'selected' : ''; ?> value="0">Rejected</option>
                        </select>
                    </div>
                    <div class="col-sm-6">
                        <label>Account Status</label>
                        <select name="form[status]" class="form-select">
                            <option <?= $user->status == 1 ? 'selected' : ''; ?> value="1">Active</option>
                            <option <?= $user->status == 0 ? 'selected' : ''; ?> value="0">In-Active</option>
                        </select>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-sm-3">
                        <label><b>User Type</b></label>
                        <select name="form[user_type]" class="form-select">
                            <option value="">Select Account</option>
                            <option <?= $user->user_type == USER_CUSTOMER ? 'selected' : ''; ?> value="1">Customer</option>
                            <!-- <option <?= $user->user_type == USER_LAND_OWNER ? 'selected' : ''; ?> value="2">Land Owner</option> -->
                            <option <?= $user->user_type == USER_BROKER ? 'selected' : ''; ?> value="3">Broker/Builder</option>
                            <option <?= $user->user_type == USER_MUNSI ? 'selected' : ''; ?> value="4">Munsi</option>
                            <option <?= $user->user_type == USER_AMIN ? 'selected' : ''; ?> value="5">Amin</option>
                            <option <?= $user->user_type == USER_CO ? 'selected' : ''; ?> value="6">CO</option>
                            <option <?= $user->user_type == USER_SDO ? 'selected' : ''; ?> value="7">SDO</option>
                            <option <?= $user->user_type == USER_BHUMI_LOCKER ? 'selected' : ''; ?> value="8">Bhumi Locker</option>
                            <option <?= $user->user_type == USER_SAND_SUPPLIER ? 'selected' : ''; ?> value="11">Sand Supplier</option>
                            <option <?= $user->user_type == USER_BRICKS_MFG ? 'selected' : ''; ?> value="10">Sand Supplier</option>
                            <option <?= $user->user_type == USER_BUILDER_CONSTRUCTON ? 'selected' : ''; ?> value="12">Sand Supplier</option>
                        </select>
                    </div>
                    <div class="col-sm-3">
                        <label for=""><b>Promoted to Sponsor</b></label>
                        <select name="form[isPromoted]" class="form-select">
                            <option value="0">Select </option>
                            <option <?= $user->isPromoted == 1 ? 'selected' : ''; ?> value="1">Yes</option>
                            <option <?= $user->isPromoted == 0 ? 'selected' : ''; ?>value="0">No</option>
                        </select>
                    </div>
                </div>
                <div>
                    <button name="btn_update" value="Update" type="submit" class="btn btn-primary">Update User</button>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <?php
            if ($user->user_type == USER_BHUMI_LOCKER) {
            ?>
                <div class="card card-body">
                    <div class="mb-3">
                        <label>Payment Status</label>
                        <select name="form[pay_status]" class="form-select">
                            <option <?= $user->pay_status == 1 ? 'selected' : ''; ?> value="1">Success</option>
                            <option <?= $user->pay_status == 2 ? 'selected' : ''; ?> value="2">Failed</option>
                            <option <?= $user->pay_status == 0 ? 'selected' : ''; ?> value="0">Pending</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Payment Mode</label>
                        <select name="form[pay_mode]" class="form-select">
                            <option <?= $user->pay_mode == 'offline' ? 'selected' : ''; ?> value="offline">Offline</option>
                            <option <?= $user->pay_mode == 'online' ? 'selected' : ''; ?> value="online">Online</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Payment Date</label>
                        <input type="date" name="form[pay_date]" value="<?= $user->pay_date; ?>" class="form-control">
                    </div>
                </div>
            <?php
            }
            ?>
        </div>
    </div>
</form>
<?php
include "../common/footer.php";
?>