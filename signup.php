<?php
include "config/autoload.php";
if (is_login()) {
    redirect(site_url('dashboard'));
}
include_once "common/header.php";
?>
<div id="origin" class="container">
    <main class="row py-5">
        <div class="col-sm-10 m-auto">
            <?php include "common/alert.php"; ?>
            <div class="bg-white rounded shadow-sm overflow-hidden mb-3">
                <div class="bg-primary d-sm-flex justify-content-end">
                    <img src="<?= theme_url('img/login.png'); ?>" class="login-img object-fit-cover" />
                    <div class="p-3 bg-white login-form">
                        <h5>Account Signup</h5>
                        <hr />
                        <form action="signup_action.php" method="POST">
                            <input type="hidden" name="created" value="<?= date('Y-m-d H:i:s'); ?>">
                            <!-- <div class="row mb-2 align-items-center">
                                <label class="col-sm-4"><b>Select User Type</b></label>
                                <div class="col-sm-8">
                                    <select v-model="user_type" name="user_type" class="form-select">
                                        <option value="">Select Account</option>
                                        <option value="1">Customer</option>
                                        <option value="2">Land Owner</option>
                                        <option value="3">Broker/Builder</option>
                                        <option value="4">Munsi</option>
                                        <option value="5">Amin</option>
                                        <option value="8">Bhumi Locker</option>
                                    </select>
                                </div>
                            </div> -->
                            <div class="mb-2 row">
                                <div class="col-sm-6">
                                    <label>First name</label>
                                    <input type="text" name="first_name" required class="form-control">
                                </div>
                                <div class="col-sm-6">
                                    <label>Last name</label>
                                    <input type="text" name="last_name" required class="form-control">
                                </div>
                            </div>
                            <div class="mb-2">
                                <label>Email Id</label>
                                <input name="email_id" type="email" class="form-control" />
                            </div>
                            <div class="mb-2">
                                <label>Mobile Number</label>
                                <input name="mobile_number" onkeypress="return validateNumber(event)" type="text" minlength="10" maxlength="10" class="form-control" required />
                            </div>
                            <div class="mb-2">
                                <label>Password</label>
                                <input type="password" name="passwd" class="form-control" required />
                            </div>
                            <button type="submit" class="btn btn-primary">Continue</button>
                            <a href="index.php" class="btn btn-dark">Cancel</a>
                        </form>
                    </div>
                </div>
            </div>
            <div class="ath-note text-center tc-light"> Already have an account? <a href="login.php"> <strong>Sign in</strong></a></div>
        </div>
    </main>
</div>
<script>
    function validateNumber(evt) {
        let keyCode = (evt.which) ? evt.which : evt.keyCode
        if (keyCode > 31 && (keyCode < 48 || keyCode > 57))
            return false;
        return true;
    }
</script>
<?php
include_once "common/footer.php";
