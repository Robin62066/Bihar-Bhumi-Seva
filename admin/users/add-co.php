<?php
include "../../config/autoload.php";
if (!is_admin_login()) redirect(admin_url('index.php'), 'You must login to continue');

// print_r($_POST);
if (isset($_POST['button'])) {

    $form = $_POST['form'];
    if ($form['first_name'] == '' || $form['last_name'] == '') {
        set_flashdata('error_msg', "Please fill required fields");
    } else {

        $form['user_type'] = USER_CO;
        $form['created'] = date("Y-m-d H:i:s");
        $form['image'] = do_upload('image');

        $db->insert("ai_users", $form);
        redirect(admin_url('users/all-co-list.php'), "CO Details has been saved successfully", "success");
    }
}

$states_arr = $db->select('ai_states', [], false, "state_name ASC")->result();
$dist_arr = $db->select('ai_districts', [], false, "dist_name ASC")->result();

$menu = 'members';
include "../common/header.php";
?>
<div id="origin">
    <div class="page-header">
        <h5>Add CO</h5>
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
                            <option value="<?= $item->id; ?>"><?= $item->dist_name; ?></option>
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
                    <input type="text" name="form[first_name]" value="<?= @$_POST['form']['first_name']; ?>" class="form-control">
                </div>
                <label for="" class="col-sm-1">Last name</label>
                <div class="col-sm-4">
                    <input type="text" name="form[last_name]" class="form-control">
                </div>
            </div>
            <div class="row align-items-center mb-3">
                <label for="" class="col-sm-1">Email Id</label>
                <div class="col-sm-4">
                    <input type="email" name="form[email_id]" value="<?= @$_POST['form']['email_id']; ?>" class="form-control">
                </div>
                <label for="" class="col-sm-1">Mobile No</label>
                <div class="col-sm-4">
                    <input type="text" name="form[mobile_number]" value="<?= @$_POST['form']['mobile_number']; ?>" class="form-control">
                </div>
            </div>

            <div class="row align-items-center mb-3">
                <label for="" class="col-sm-1">Address</label>
                <div class="col-sm-9">
                    <textarea name="form[address]" rows="3" class="form-control"></textarea>
                </div>
            </div>
            <div class="row align-items-center mb-3">
                <label for="" class="col-sm-1">Gender</label>
                <div class="col-sm-3">
                    <select name="form[gender]" class="form-select">
                        <option value="">Select</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                    </select>
                </div>
                <label for="" class="col-sm-1">KYC Status</label>
                <div class="col-sm-2">
                    <select name="form[kyc_status]" class="form-select">
                        <option value="">Select</option>
                        <option value="1">Approved</option>
                        <option value="0">Pending</option>
                    </select>
                </div>
                <label for="" class="col-sm-1">Login</label>
                <div class="col-sm-2">
                    <select name="form[status]" class="form-select">
                        <option value="">Select</option>
                        <option value="1">Active</option>
                        <option value="0">In-Active</option>
                    </select>
                </div>
            </div>
            <div class="row align-items-center mb-3">
                <label for="" class="col-sm-1">Profile Photo</label>
                <div class="col-sm-6">
                    <input type="file" accept="image/*" name="image" class="form-control">
                </div>
            </div>
            <div class="row align-items-center mb-3">
                <label for="" class="col-sm-1"> </label>
                <div class="col-sm-6">
                    <input type="submit" name="button" value="Save Details" class="btn btn-primary" />
                    <input type="reset" value="Reset" class="btn btn-dark" />
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
            dist_id: '',
            zone_id: '',
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
        }
    });
</script>

<?php
include "../common/footer.php";
