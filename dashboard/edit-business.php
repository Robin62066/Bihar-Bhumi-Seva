<?php
require('../config/autoload.php');
if (!is_login()) redirect(base_url('login.php'), 'You must login to continue');
$user_id = user_id();
$item = $db->select('ai_users', ['id' => $user_id], 1)->row();
$dists = $db->select('ai_districts', [], false, 'dist_name ASC')->result();

if (isset($_POST['clicked'])) {
    $sb = $_POST['form'];
    $db->update('ai_profiles', $sb, ['user_id' => $user_id]);
    session()->set_flashdata('success', "Business Profile account updated.");
    redirect(base_url('dashboard/profile.php'));
}
$profile = $db->select('ai_profiles', ['user_id' => $user_id], 1)->row();

include "../common/header.php";
?>
<div class="dashboard">
    <div class="container py-5">
        <div class="user-panel">
            <div class="row">
                <?php
                $subpage = 'business';
                include_once "dashboard-menu.php"; ?>
                <div class="col-sm-9">
                    <?= front_view('common/alert'); ?>
                    <div id="origin">
                        <form action="" method="post">
                            <div class="bg-white shadow-sm p-4 rounded-sm">
                                <div class="page-header">
                                    <h5>Edit Business Profile</h5>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <p>Please select your store category type below to proceed with the onboardig.</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-5">
                                        <div class="mb-3">
                                            <label>Business Catogory</label>
                                            <select name="form[category]" class="form-select">
                                                <option value="">Select</option>
                                                <option <?php if ('3' == $profile->category) echo 'selected'; ?> value="3">Plot Broker</option>
                                                <option value="4" <?php if ('4' == $profile->category) echo 'selected'; ?>>Deed Writer Munsi</option>
                                                <option value="5" <?php if ('5' == $profile->category) echo 'selected'; ?>>Plot Measurement Amin</option>
                                                <option value="10" <?php if ('10' == $profile->category) echo 'selected'; ?>>Brick Manufacture</option>
                                                <option value="11" <?php if ('11' == $profile->category) echo 'selected'; ?>>Sand Supplier</option>
                                                <option value="12" <?php if ('12' == $profile->category) echo 'selected'; ?>>Builder And Construction</option>
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
                                <div>
                                    <div class="mb-3 row">
                                        <div class="col-sm-5">
                                            <label>Legal Name <span class="text-danger">*</span> </label>
                                            <input name="form[legal_name]" required type="text" class="form-control" value="<?= $profile->legal_name; ?>">
                                        </div>
                                        <div class="col-sm-5" v-if="btype== 1">
                                            <label>Whatsapp Number <span class="text-danger">*</span></label>
                                            <input type="text" required name="form[mobile2]" value="<?= $profile->mobile2; ?>" class="form-control">
                                        </div>
                                        <div class="col-sm-5" v-if="btype> 1">
                                            <label>GSTIN/CIN/PAN Number <span class="text-danger">*</span></label>
                                            <input type="text" required name="form[gstin]" value="<?= $profile->gstin; ?>" class="form-control">
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <div class="col-sm-5">
                                            <label>Mobile Number <span class="text-danger">*</span></label>
                                            <input type="number" name="form[mobile]" required maxlength="10" class="form-control" value="<?= $profile->mobile; ?>">
                                        </div>
                                        <div class="col-sm-5">
                                            <label>Email Id <span class="text-danger">*</span></label>
                                            <input type="email" name="form[email_id]" required class="form-control" value="<?= $profile->email_id; ?>">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-sm-10">
                                            <label>Full Address</label>
                                            <textarea rows="3" type="text" name="form[address]" class="form-control"><?= $profile->address; ?></textarea>
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <div class="col-sm-5">
                                            <label>City <span class="text-danger">*</span></label>
                                            <input type="text" name="form[city]" required value="<?= $profile->city; ?>" class="form-control">
                                        </div>
                                        <div class="col-sm-5">
                                            <label>District <span class="text-danger">*</span></label>
                                            <select name="form[dist_id]" required class="form-select">
                                                <option value="">Select</option>
                                                <?php
                                                foreach ($dists as $dist) {
                                                ?>
                                                    <option <?php if ($dist->id == $profile->dist_id) echo 'selected'; ?> value="<?= $dist->id; ?>"><?= $dist->dist_name; ?></option>
                                                <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" name="clicked" value="1">
                                <button :disabled="btype<=0" class="btn btn-primary btn-submit">UPDATE</button>
                                <a href="<?= site_url('dashboard'); ?>" class="btn btn-dark">Cancel</a>
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
            btype: '<?= $profile->business_type; ?>'
        }
    });
</script>