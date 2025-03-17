<?php
include "../config/autoload.php";
if (!is_login()) redirect(base_url('login.php'), 'You must login to continue');
$user_id = user_id();
$us = $db->select('ai_users', ['id' => $user_id])->row();
if (isset($_POST['btn_updated'])) {
    $fields = $_POST['form'];
    if (isset($_FILES['image']['name']) && $_FILES['image']['name'] != '') {
        $fields['image'] = do_upload('image', true);
    }
    $db->update('ai_users', $fields, ['id' => $user_id]);
    $us = $db->select('ai_users', ['id' => $user_id])->row();
    set_userdata('user', $us);
    redirect('edit-profile.php', 'Profile updated successfully', 'success');
}
$dists = $db->select('ai_districts', [], false, 'dist_name ASC')->result();
include "../common/header.php";
?>
<style>
    .pop-up-button {
        cursor: pointer;
        box-shadow: 4px 4px 20px white;
        -webkit-animation: mover 2s infinite alternate;
        animation: mover 2s infinite alternate;
    }

    @-webkit-keyframes mover {
        0% {
            transform: translateY(10px);
        }

        100% {
            transform: translateY(10px);
        }
    }

    @keyframes mover {
        0% {
            transform: translateY(3px);
        }

        100% {
            transform: translateY(-3px);
        }
    }
</style>
<div class="dashboard">
    <div class="container py-5">
        <div class="user-panel">
            <div class="row">
                <?php
                $subpage = 'edit-profile';
                include_once "dashboard-menu.php"; ?>
                <div class="col-sm-9">
                    <?= front_view('common/alert'); ?>
                    <?php if ($us->kyc_status == 0) { ?>
                        <div class="alert alert-danger" role="alert">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    Kyc is pending, please complete kyc to proceed further!
                                </div>
                                <div>
                                    <a href="<?= site_url('pan-verification.php') ?>" class="btn btn-sm pop-up-button btn-primary ">Complete Kyc</a>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    <div id="origin">
                        <div class="bg-white p-4 rounded-1">
                            <div class="page-header">
                                <h5>My Profile</h5>
                            </div>
                            <form enctype="multipart/form-data" action="" method="post">
                                <p><b>Personal Information</b></p>
                                <div class="row mb-3">
                                    <div class="col-sm-5">
                                        <label for="">First name</label>
                                        <input type="text" <?= ($us->kyc_status == 1) ? '' : '' ?> class="form-control" name="form[first_name]" value="<?= $us->first_name ?>">
                                    </div>
                                    <div class="col-sm-5">
                                        <label for="">Last name</label>
                                        <input type="text" <?= ($us->kyc_status == 1) ? '' : '' ?> class="form-control" name="form[last_name]" value="<?= $us->last_name ?>">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-5">
                                        <label for="">Email Id</label>
                                        <input type="text" class="form-control" name="form[email_id]" value="<?= $us->email_id ?>">
                                    </div>
                                    <div class="col-sm-5">
                                        <label for="">Mobile</label>
                                        <input type="text" disabled class="form-control" name="form[mobile_number]" value="<?= $us->mobile_number; ?>">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-10">
                                        <label for="">Address</label>
                                        <textarea rows="2" class="form-control" name="form[address]"><?= $us->address ?></textarea>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-5">
                                        <label for="">District</label>
                                        <select @change="set_zones" name="form[dist_id]" v-model="dist_id" class="form-select">
                                            <option value="">Select</option>
                                            <?php
                                            foreach ($dists as $item2) {
                                            ?>
                                                <option value="<?= $item2->id; ?>"><?= $item2->dist_name; ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-sm-5">
                                        <label for="">Anchal</label>
                                        <select class="form-select" name="form[zone_id]" v-model="zone_id">
                                            <option value="">Select</option>
                                            <option v-for="item in zones" :value="item.id">{{ item.zone_name }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-5">
                                        <label for="">Pincode</label>
                                        <input type="text" class="form-control" name="form[pincode]" value="<?= $us->pincode ?>">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-5">
                                        <label for="">WhatsApp Number</label>
                                        <input type="text" class="form-control" name="form[whatsapp]" value="<?= $us->whatsapp ?>">
                                    </div>
                                    <div class="col-sm-5">
                                        <label for="">Profile Photo</label>
                                        <input type="file" class="form-control" name="image">
                                        <?php
                                        if ($us->image != '') {
                                        ?>
                                            <img src="<?= base_url(upload_dir($us->image)) ?>" width="100" />
                                        <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <button class="btn btn-primary" name="btn_updated">UPDATE</button>
                                    <a href="<?= base_url('dashboard') ?>" class="btn btn-light">Cancel</a>
                                </div>
                            </form>
                            <!-- End of the form -->
                            <hr />
                            <div class="faq">
                                <!-- FAQ content here -->
                            </div>
                        </div>
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
            zones: [],
            dist_id: '<?= $us->dist_id; ?>',
            zone_id: '<?= $us->zone_id; ?>',
            btncls: '',
            errmsg: '',
            errcls: '',
            pid: 0
        },
        methods: {
            set_zones: function() {
                this.zones = [];
                api_call('zones', {
                    dist_id: this.dist_id
                }).then(resp => {
                    if (resp.success) this.zones = resp.data;
                })
            },
        },
        created: function() {
            if (this.dist_id != '') {
                this.set_zones();
            }
        }
    });
</script>