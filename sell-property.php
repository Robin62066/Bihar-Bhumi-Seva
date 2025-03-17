<?php
include "config/autoload.php";
if (!is_login()) redirect(base_url('login.php'), 'Please login to continue');
$user_id = user_id();
$sites = $db->select('ai_sites', ['user_id' => $user_id])->result();
$count = count($sites);

//This was come from session
$user = userdata('user');


$type = $_GET['type'] ?? 'free';
$dists = $db->select('ai_districts', [], false, 'dist_name ASC')->result();
$arr_brokers = $db->select('ai_users', ['user_type' => USER_BROKER, 'kyc_status' => KYC_APPROVED])->result();
$arr_munsi = $db->select('ai_users', ['user_type' => USER_MUNSI, 'kyc_status' => KYC_APPROVED])->result();


// echo $count;
// print_r($user);
// die();
if ($count <= 1 || $user->kyc_status == 1) {
    if ($count > 1 && $user->kyc_status == 1) {
        redirect('pricing.php', 'You can only post one property please upgrade your plan', 'danger');
    }
    include_once "common/header.php";
?>

    <div id="origin" class="container py-3">
        <?= front_view("common/alert"); ?>
        <div class="row">
            <?= front_view('common/home-menu'); ?>
            <div class="col-sm-9">
                <div class="bg-white p-3 shadow-sm1 rounded">
                    <div class="text-center mb-3">
                        <h5>प्लाट स्थान</h5>
                        <hr />
                    </div>

                    <div class="row">
                        <div class="col-sm-8 m-auto">
                            <div v-if="errmsg.length>0" class="alert" :class="errcls">{{ errmsg }}</div>
                        </div>
                    </div>
                    <div class="row mb-2 align-items-center">
                        <div class="col-sm-5 text-end">
                            <label class="text-end">अपना जिला चुने </label>
                        </div>
                        <div class="col-sm-4">
                            <select @change="set_zones" v-model="dist_id" class="form-select">
                                <option value="">Select</option>
                                <?php
                                foreach ($dists as $item) {
                                ?>
                                    <option value="<?= $item->id; ?>"><?= $item->dist_name; ?></option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-4 align-items-center">
                        <div class="col-sm-5 text-end">
                            <label class="text-end">अपना अंचल चुने </label>
                        </div>
                        <div class="col-sm-4">
                            <select v-model="zone_id" class="form-select">
                                <option value="">Select</option>
                                <option v-for="item in zones" :value="item.id">{{ item.zone_name }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3 text-center">
                        <p>बिना दलाल या ब्रोकर के बिना अपना जमीन बेचें।</p>
                        <p>यदि आप किसी दलाल या ब्रोकर से संपर्क करना चाहते है तो <a href="brokers.php">यहां क्लिक करें</a></p>
                        <button @click="save_proceed" class="btn btn-primary" :class="btncls"><span></span> Next</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
} else {
    redirect("pan-verification.php");
}
?>
<script>
    let vm = new Vue({
        el: '#origin',
        data: {
            zones: [],
            dist_id: '',
            zone_id: '',
            btncls: '',
            errmsg: '',
            errcls: '',
            gps_on: false,
            lat: '',
            lng: ''
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
            save_proceed: function() {
                this.errmsg = '';
                if (this.dist_id == '' || this.zone_id == '') {
                    this.errcls = 'alert-danger';
                    this.errmsg = 'Please fill all required fields';
                    return;
                }
                this.btncls = 'btn-loading';
                api_call('save-proceed', {
                    dist_id: this.dist_id,
                    zone_id: this.zone_id,
                    membership: '<?= $type; ?>',
                    lat: this.lat,
                    lng: this.lng
                }).then(resp => {
                    if (resp.success) {
                        this.errcls = 'alert-success';
                        this.errmsg = resp.message;
                        this.btncls = ''
                        setTimeout(() => {
                            window.location = 'property-details.php?id=' + resp.data;
                        }, 1000);
                    } else {
                        this.errcls = 'alert-danger';
                        this.errmsg = resp.message;
                        this.btncls = ''
                    }
                })
            },
            updateLocation: function(position) {
                const {
                    latitude,
                    longitude
                } = position.coords
                this.gps_on = true;
                this.lat = latitude;
                this.lng = longitude;
            }
        },
        created: function() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(this.updateLocation, function() {
                    alert("Bihar Bhumi Seva wants to access your Location.");
                })
            } else {
                alert("GPS Location not supported")
            }
        }
    });
</script>
<?php
include_once "common/footer.php";
?>