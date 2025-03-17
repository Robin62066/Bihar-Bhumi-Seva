<?php
include "../../config/autoload.php";
if (!is_admin_login()) redirect(admin_url('index.php'), 'You must login to continue');

$id = $_GET['id'] ?? 0;
if (isset($_POST['button'])) {

    $form = $_POST['form'];
    if ($form['first_name'] == '' || $form['last_name'] == '') {
        set_flashdata('error_msg', "Please fill required fields");
    } else {

        $form['user_type'] = USER_CO;
        $form['created'] = date("Y-m-d H:i:s");
        if (isset($_FILES['image']['name']) && $_FILES['image']['name'] != '') {
            $form['image'] = do_upload('image');
        }

        $db->update("ai_users", $form, ['id' => $id]);
        set_flashdata('success_msg', 'CO Details has been updated');
    }
}

$states_arr = $db->select('ai_states', [], false, "state_name ASC")->result();
$dist_arr = $db->select('ai_districts', [], false, "dist_name ASC")->result();
$user = $db->select('ai_users', ['id' => $id])->row();

$menu = 'members';
include "../common/header.php";
?>
<div id="origin">
    <div class="page-header">
        <h5>Edit CO Details</h5>
        <a href="<?= admin_url('users/?user_type=6'); ?>" class="btn btn-sm btn-primary">View CO List</a>
    </div>
    <div class="bg-white p-3">
        <form enctype="multipart/form-data" action="" method="post">
            <div class="row align-items-center mb-3">
                <label for="" class="col-sm-1">District</label>
                <div class="col-sm-3">
                    <select @change="set_zones" name="form[dist_id]" v-model="dist_id" class="form-select">
                        <option value="">Select</option>
                        <?php
                        foreach ($dist_arr as $item) {
                        ?>
                            <option value="<?= $item->id; ?>" <?= $user->dist_id == $item->id ? 'selected' : ''; ?>><?= $item->dist_name; ?></option>
                        <?php
                        }
                        ?>
                    </select>
                </div>
                <label for="" class="col-sm-1">Zone</label>
                <div class="col-sm-3">
                    <select name="form[zone_id]" v-model="zone_id" class="form-select">
                        <option value="">Select</option>
                        <option v-for="item in zones" :value="item.id">{{ item.zone_name }}</option>
                    </select>
                </div>
            </div>
            <div class="row align-items-center mb-3">
                <label for="" class="col-sm-1">First name</label>
                <div class="col-sm-4">
                    <input type="text" name="form[first_name]" value="<?= $user->first_name; ?>" class="form-control">
                </div>
                <label for="" class="col-sm-1">Last name</label>
                <div class="col-sm-4">
                    <input type="text" name="form[last_name]" value="<?= $user->last_name; ?>" class="form-control">
                </div>
            </div>
            <div class="row align-items-center mb-3">
                <label for="" class="col-sm-1">Email Id</label>
                <div class="col-sm-4">
                    <input type="email" name="form[email_id]" value="<?= $user->email_id; ?>" class="form-control">
                </div>
                <label for="" class="col-sm-1">Mobile No</label>
                <div class="col-sm-4">
                    <input type="text" name="form[mobile_number]" value="<?= $user->mobile_number; ?>" class="form-control">
                </div>
            </div>
            <div class="row align-items-center mb-3">
                <label for="" class="col-sm-1">Aadhar Number</label>
                <div class="col-sm-4">
                    <input type="text" maxlength="12" value="<?= $user->aadhar_number; ?>" name="form[aadhar_number]" class="form-control">
                </div>
                <label for="" class="col-sm-1">PAN Number</label>
                <div class="col-sm-4">
                    <input type="text" name="form[pan_number]" value="<?= $user->pan_number; ?>" class="form-control">
                </div>
            </div>
            <div class="row align-items-center mb-3">
                <label for="" class="col-sm-1">Address</label>
                <div class="col-sm-9">
                    <textarea name="form[address]" rows="3" class="form-control"><?= $user->address; ?></textarea>
                </div>
            </div>
            <div class="row align-items-center mb-3">
                <label for="" class="col-sm-1">City</label>
                <div class="col-sm-3">
                    <input type="text" name="form[city]" value="<?= $user->city; ?>" class="form-control">
                </div>

                <label for="" class="col-sm-1">State</label>
                <div class="col-sm-2">
                    <select name="form[state]" class="form-select">
                        <option value="">Select</option>
                        <?php
                        foreach ($states_arr as $item) {
                        ?>
                            <option value="<?= $item->id; ?>" <?= $user->state == $item->id ? 'selected' : ''; ?>><?= $item->state_name; ?></option>
                        <?php
                        }
                        ?>
                    </select>
                </div>
                <label for="" class="col-sm-1">Pincode</label>
                <div class="col-sm-2">
                    <input type="text" name="form[pincode]" value="<?= $user->pincode; ?>" class="form-control">
                </div>
            </div>
            <div class="row align-items-center mb-3">
                <label for="" class="col-sm-1">Gender</label>
                <div class="col-sm-3">
                    <select name="form[gender]" class="form-select">
                        <option value="">Select</option>
                        <option value="male" <?= $user->gender == 'male' ? 'selected' : ''; ?>>Male</option>
                        <option value="female" <?= $user->gender == 'female' ? 'selected' : ''; ?>>Female</option>
                    </select>
                </div>
                <label for="" class="col-sm-1">KYC Status</label>
                <div class="col-sm-2">
                    <select name="form[kyc_status]" class="form-select">
                        <option value="">Select</option>
                        <option value="1" <?= $user->kyc_status == '1' ? 'selected' : ''; ?>>Approved</option>
                        <option value="0" <?= $user->kyc_status == '0' ? 'selected' : ''; ?>>Pending</option>
                    </select>
                </div>
                <label for="" class="col-sm-1">Login</label>
                <div class="col-sm-2">
                    <select name="form[status]" class="form-select">
                        <option value="">Select</option>
                        <option value="1" <?= $user->status == '1' ? 'selected' : ''; ?>>Active</option>
                        <option value="0" <?= $user->status == '0' ? 'selected' : ''; ?>>In-Active</option>
                    </select>
                </div>
            </div>
            <div class="row align-items-center mb-3">
                <label for="" class="col-sm-1">Profile Photo</label>
                <div class="col-sm-6">
                    <input type="file" accept="image/*" name="image" class="form-control">
                    <?php
                    if ($user->image != '') {
                    ?>
                        <img src="<?= base_url(upload_dir($user->image)) ?>" width="100" />
                    <?php
                    }
                    ?>
                </div>
            </div>
            <div class="row align-items-center mb-3">
                <label for="" class="col-sm-1"> </label>
                <div class="col-sm-6">
                    <input type="submit" name="button" value="Update Details" class="btn btn-primary" />
                    <a href="<?= admin_url('users/?user_type=6'); ?>" class="btn btn-dark">Cancel</a>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    var apiUrl = '<?= base_url('api.php') ?>'
    async function api_call(m, ob) {
        let url = apiUrl + '?m=' + m;
        let result = await axios.post(url, ob);
        let resp = result.data;
        return resp;
    }
    let vm = new Vue({
        el: '#origin',
        data: {
            zones: [],
            dist_id: '<?= $user->dist_id; ?>',
            zone_id: '<?= $user->zone_id; ?>',
            btncls: '',
            errmsg: '',
            errcls: ''
        },
        methods: {
            set_zones: function() {
                this.zones = [];
                api_call('zones', {
                    dist_id: this.dist_id
                }).then(resp => {
                    if (resp.success) this.zones = resp.data;
                })
            }
        },
        created: function() {
            this.set_zones()
        }
    });
</script>

<?php
include "../common/footer.php";
