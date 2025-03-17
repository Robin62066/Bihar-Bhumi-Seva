<?php
include '../../config/autoload.php';
if (!is_admin_login()) redirect(admin_url('index.php'), 'You must login to continue');

if (isset($_POST['clicked'])) {
    $form = $_POST['form'];
    // Check mobile no
    $ab = $db->select('ai_users', ['mobile_number' => $form['mobile_number']], 1)->row();
    if ($ab == null) {
        $form['created'] = date("Y-m-d H:i:s");
        $form['status'] = 1;
        $form['mobile_verified'] = 0;

        $db->insert('ai_users', $form);

        $name = $form['first_name'];
        $pass = $form['passwd'];
        $mobile = $form['mobile_number'];

        sendSMS($name, $pass, $mobile, "usermsg");
        redirect(admin_url("users/create-user.php"), "Account created and login sent successfully", "success");
    } else {
        session()->set_flashdata('error', "Mobile no already registered with us.");
    }
}

$menu = 'members';
include "../common/header.php";
?>
<div class="page-header">
    <h5>Create User</h5>
</div>
<div class="row">
    <div class="col-sm-8">
        <div class="card p-3">
            <form method="post">
                <div class="row mb-2 align-items-center">
                    <label class="col-sm-2"><b>User Type</b></label>
                    <div class="col-sm-3">
                        <select required name="form[user_type]" class="form-select">
                            <option value="">Select Account</option>
                            <option value="1">Customer</option>
                            <option value="2">Land Owner</option>
                            <option value="3">Broker/Builder</option>
                            <option value="4">Munsi</option>
                            <option value="5">Amin</option>
                            <option value="8">Bhumi Locker</option>
                            <option value="9">Labour</option>
                            <option value="10">Brick Manufacture</option>
                            <option value="11">Sand Supplier</option>

                        </select>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-6">
                        <label>First name</label>
                        <input required name="form[first_name]" type="text" class="form-control">
                    </div>
                    <div class="col-sm-6">
                        <label>Last name</label>
                        <input required name="form[last_name]" type="text" class="form-control">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-sm-6">
                        <label>Mobile no</label>
                        <input required name="form[mobile_number]" type="text" class="form-control">
                    </div>
                    <div class="col-sm-6">
                        <label>Password</label>
                        <input required name="form[passwd]" type="text" class="form-control">
                    </div>
                </div>

                <input type="hidden" name="clicked" value="1" />
                <button type="submit" class="btn btn-primary btn-submit">Create User</button>
            </form>
        </div>
    </div>
</div>
<?php
include "../common/footer.php";
?>