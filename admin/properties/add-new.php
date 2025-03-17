<?php
include "../../config/autoload.php";
if (!is_admin_login()) redirect(admin_url('index.php'), 'You must login to continue');

$dists = $db->select('ai_districts', [], false, 'dist_name ASC')->result();
$arr_owners = $db->select('ai_users', ['status' => 1], false, "first_name ASC")->result();
$arr_brokers = $db->select('ai_users', ['status' => 1, 'user_type' => USER_BROKER, 'kyc_status' => 1], false, "first_name ASC")->result();
$arr_munsi = $db->select('ai_users', ['status' => 1, 'user_type' => USER_MUNSI, 'kyc_status' => 1], false, "first_name ASC")->result();

if (isset($_POST['btnupdate'])) {
    $sb = $_POST['form'];
    $pic_a = do_upload('pic_a');
    if ($pic_a != '') $sb['photo_front'] = $pic_a;

    $pic_b = do_upload('pic_b');
    if ($pic_b != '') $sb['photo_back'] = $pic_b;

    $pic_c = do_upload('pic_c');
    if ($pic_c != '') $sb['photo_left'] = $pic_c;

    $pic_d = do_upload('pic_d');
    if ($pic_d != '') $sb['photo_right'] = $pic_d;

    $sb['rasid_photo'] = do_upload('rasid');
    $sb['user_id'] = $sb['owner_id'];
    $sb['status'] = 0; // Pending for approved property list
    $db->insert('ai_sites', $sb);

    $id  = $db->id();
    redirect(admin_url('properties/details.php?id=' . $id), "Property Added Successfully", 'success');
}

$menu = 'properties';
include "../common/header.php";
?>
<div id="origin">
    <div class="page-header">
        <h5>Add New Property</h5>
    </div>
    <div class="card card-body">
        <form action="" method="post" class="mb-3" enctype="multipart/form-data">
            <div class="bg-white p-3 shadow-sm1 rounded">
                <div class="row mb-3">
                    <div class="col-sm-4">
                        <label>Select Owner</label>
                        <select name="form[owner_id]" class="form-select select2">
                            <option value="">Select</option>
                            <?php
                            foreach ($arr_owners as $us) {
                            ?>
                                <option value="<?= $us->id; ?>"><?= $us->first_name . ' ' . $us->last_name . '(' . $us->mobile_number . ')'; ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-sm-4">
                        <label>Select Broker</label>
                        <select name="form[broker_id]" class="form-select select2">
                            <option value="">Select</option>
                            <?php
                            foreach ($arr_brokers as $us) {
                            ?>
                                <option value="<?= $us->id; ?>"><?= $us->first_name . ' ' . $us->last_name . '(' . $us->mobile_number . ')'; ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-sm-4">
                        <label>Select Munsi</label>
                        <select name="form[munsi_id]" class="form-select select2">
                            <option value="">Select</option>
                            <?php
                            foreach ($arr_munsi as $us) {
                            ?>
                                <option value="<?= $us->id; ?>"><?= $us->first_name . ' ' . $us->last_name . '(' . $us->mobile_number . ')'; ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <p>जमीन के चारो कोनो का फोटो बनायें, दाएं बाएं सामने और पीछे का फोटो दर्ज़ करें।</p>
                <hr />
                <div class="row mb-4">
                    <div class="col-sm-3">
                        <div class="border p-5 bg-light">
                        </div>
                        <input type="file" name="pic_a" accept="image/*" class="form-control">
                    </div>
                    <div class="col-sm-3">
                        <div class="border p-5 bg-light">
                        </div>
                        <input type="file" name="pic_b" accept="image/*" class="form-control">
                    </div>
                    <div class="col-sm-3">
                        <div class="border p-5 bg-light">
                        </div>
                        <input type="file" name="pic_c" accept="image/*" class="form-control">
                    </div>
                    <div class="col-sm-3">
                        <div class="border p-5 bg-light">
                        </div>
                        <input type="file" name="pic_d" accept="image/*" class="form-control">
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-9">
                        <label>Title </label>
                        <input type="text" name="form[site_title]" required value="" class="form-control">
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-9">
                        <label>प्लाट स्थान का नाम जोड़ें </label>
                        <input type="text" required name="form[address]" value="" class="form-control">
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-3">
                        <label>जिला</label>
                        <select name="form[dist_id]" @change="set_zones" v-model="dist_id" class="form-select">
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
                    <div class="col-sm-3">
                        <label>अंचल</label>
                        <select name="form[zone_id]" v-model="zone_id" class="form-select">
                            <option value="">Select</option>
                            <option v-for="item in zones" :value="item.id">{{ item.zone_name }}</option>
                        </select>
                    </div>
                    <div class="col-sm-3">
                        <label>मौजा</label>
                        <input type="text" required name="form[mauja]" class="form-control">
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-2">
                        <label>जमाबंदी संख्या </label>
                        <input type="text" required name="form[jamabandi_no]" class="form-control">
                    </div>
                    <div class="col-sm-2">
                        <label>भाग बर्तमान </label>
                        <input type="text" required name="form[bhag_vartman]" class="form-control">
                    </div>
                    <div class="col-sm-2">
                        <label>पृष्ठ संख्या </label>
                        <input type="text" required name="form[page_no]" class="form-control">
                    </div>
                    <div class="col-sm-2">
                        <label>मौजा थाना संख्या</label>
                        <input type="text" required name="form[thana_no]" class="form-control">
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6">
                        <div class="row mb-3">
                            <div class="col-sm-6">
                                <label>जमाबंदी रैयत का नाम</label>
                                <input type="text" required name="form[jamabani_raiyat_name]" class="form-control">
                            </div>
                            <div class="col-sm-6">
                                <label>अभिभावक का नाम </label>
                                <input type="text" required name="form[guardian_name]" class="form-control">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-6">
                                <label>खता संख्या </label>
                                <input type="text" name="form[khata_no]" class="form-control">
                            </div>
                            <div class="col-sm-6">
                                <label>खेसरा संख्या</label>
                                <input type="text" name="form[khesra_no]" class="form-control">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-sm-6">
                                <label>एकड/डिसमिल </label>
                                <input type="text" required name="form[total_area]" class="form-control">
                            </div>
                            <div class="col-sm-6">
                                <label>कुल राशि </label>
                                <input type="text" required name="form[total_amount]" class="form-control">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-sm-6">
                                <label>कुल क्षेत्र </label>
                                <select required name="form[area_unit]" class="form-select">
                                    <option value="dismile">डिसमिल </option>
                                    <option value="kattha">कट्ठा </option>
                                    <option value="sqft">वर्ग फुट </option>
                                </select>
                            </div>
                            <div class="col-sm-6">
                                <label>जगह</label>
                                <input type="text" required name="form[location]" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="border" id="mapview" style="height: 300px;"></div>
                    </div>
                </div>

                <div class="text-center1">
                    <input type="hidden" name="btnupdate" value="Update">
                    <button :disabled="!gps_on" class="btn btn-warning btn-submit">SUBMIT</button>
                    <a href="index.php" class="btn btn-secondary">CANCEL</a>
                </div>
            </div>
            <input type="hidden" name="form[lat]" :value="lat" />
            <input type="hidden" name="form[lng]" :value="lng" />
        </form>
    </div>
</div>
<?php
include "../common/footer.php";
?>
<script>
    let vm = new Vue({
        el: '#origin',
        data: {
            errmsg: '',
            errcls: '',
            title: '',
            utype: '',
            saving: false,
            broker_id: '',
            munsi_id: '',
            owner_id: '',
            id: '',
            zones: [],
            dist_id: '',
            zone_id: '',
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
                    console.log(resp)
                    if (resp.success) this.zones = resp.data;
                })
            },
            updateLocation: function(position) {
                const {
                    latitude,
                    longitude
                } = position.coords
                this.gps_on = true;
                this.lat = latitude
                this.lng = longitude
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
    })
</script>
<script async src="https://maps.googleapis.com/maps/api/js?key=<?= GOOGLE_API_KEY ?>&loading=async&callback=initMap">
</script>
<script>
    function mapDraw(latitude, longitude) {
        var myLatlng = new google.maps.LatLng(latitude, longitude);
        var myOptions = {
            zoom: 14,
            center: myLatlng,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        }
        var map = new google.maps.Map(document.getElementById("mapview"), myOptions);
    }

    function initMap() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                mapDraw(position.coords.latitude, position.coords.longitude);
            }, function() {
                alert("Bihar Bhumi Seva wants to access your Location.");
            })
        } else {
            alert("GPS Location not supported")
        }
    }
</script>