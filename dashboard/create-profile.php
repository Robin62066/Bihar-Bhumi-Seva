<?php
require('../config/autoload.php');
if (!is_login()) redirect(base_url('login.php'), 'You must login to continue');
$user_id = user_id();
$item = $db->select('ai_users', ['id' => $user_id], 1)->row();
$dists = $db->select('ai_districts', [], false, 'dist_name ASC')->result();

if (isset($_POST['clicked'])) {
    $sb = (array) $_POST['form'];
    $sb['user_id'] = $user_id;
    $sb['created'] = date("Y-m-d H:i:s");
    $db->insert('ai_profiles', $sb);

    //update the user table
    $db->update('ai_users', ['user_type' => $sb['category']], ['id' => $user_id]);
    session()->set_flashdata('success', "Business Profile account created. Fill other details.");
    redirect(base_url('dashboard/business-about.php'));
}

include "../common/header.php";
?>
<div class="dashboard">
    <div class="container py-5">
        <div class="user-panel">
            <div class="row">
                <?php
                $subpage = 'profile';
                include_once "dashboard-menu.php"; ?>
                <div class="col-sm-9">
                    <?= front_view('common/alert'); ?>
                    <div id="origin">
                        <form action="" method="post">
                            <div class="bg-white shadow-sm p-4 rounded-sm">
                                <div class="page-header">
                                    <h5>Create Business Profile</h5>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <p>Please select your store category type below to proceed with the onboarding.</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-5">
                                        <div class="mb-3">
                                            <label>Business Category</label>
                                            <select name="form[category]" class="form-select">
                                                <option value="">Select</option>
                                                <option value="<?= USER_BROKER ?>">Plot Broker</option>
                                                <option value="<?= USER_MUNSI ?>">Deed Writer Munsi</option>
                                                <option value="<?= USER_AMIN ?>">Plot Measurement Amin</option>
                                                <option value="<?= USER_BRICKS_MFG ?>">Brick Manufacture</option>
                                                <option value="<?= USER_SAND_SUPPLIER ?>">Sand Supplier</option>
                                                <option value="<?= USER_BUILDER_CONSTRUCTON ?>">Builder And Construction</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-5">
                                        <div class="mb-3">
                                            <label>Business Type</label>
                                            <select v-model="btype" name="form[business_type]" class="form-select">
                                                <option value="0">Select</option>
                                                <option value="1">Individual</option>
                                                <option value="2">Sole Proprietorship</option>
                                                <option value="3">Partnership Firm</option>
                                                <option value="4">Pvt Ltd. company</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div v-if="btype >= 1">
                                    <div class="mb-3 row">
                                        <div class="col-sm-5">
                                            <label>Legal Name <span class="text-danger">*</span> </label>
                                            <input name="form[legal_name]" required type="text" class="form-control">
                                        </div>
                                        <div v-if="btype == 1" class="col-sm-5">
                                            <label>Whatsapp Number <span class="text-danger">*</span></label>
                                            <input type="number" required name="form[mobile2]" class="form-control">
                                        </div>
                                        <div v-if="btype >1" class="col-sm-5">
                                            <label>GSTIN/CIN/PAN Number <span class="text-danger">*</span></label>
                                            <input type="text" required name="form[gstin]" class="form-control">
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <div class="col-sm-5">
                                            <label>Mobile Number <span class="text-danger">*</span></label>
                                            <input type="text" name="form[mobile]" required maxlength="10" class="form-control">
                                        </div>
                                        <div class="col-sm-5">
                                            <label>Email Id <span class="text-danger">*</span></label>
                                            <input type="email" name="form[email_id]" required class="form-control">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-sm-10">
                                            <label>Full Address</label>
                                            <textarea rows="3" type="text" name="form[address]" class="form-control"></textarea>
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <div class="col-sm-5">
                                            <label>City <span class="text-danger">*</span></label>
                                            <input type="text" name="form[city]" class="form-control">
                                        </div>
                                        <div class="col-sm-5">
                                            <label>District <span class="text-danger">*</span></label>
                                            <select name="form[dist_id]" class="form-select">
                                                <option value="">Select</option>
                                                <?php
                                                foreach ($dists as $dist) {
                                                ?>
                                                    <option value="<?= $dist->id; ?>"><?= $dist->dist_name; ?></option>
                                                <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" name="clicked" value="1">
                                <button :disabled="btype<=0" class="btn btn-primary btn-submit">SAVE</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
include "../common/footer.php";
?>
<script>
    let vm = new Vue({
        el: '#origin',
        data: {
            btype: 0
        }
    });
</script>