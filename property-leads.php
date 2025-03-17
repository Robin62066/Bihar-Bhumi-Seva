<?php
include "config/autoload.php";
// if (!is_login()) redirect('login.php', 'You must login to view', 'error');
// if (!isset($_GET['id'])) redirect('index.php', 'Invalid access not allowed', 'error');

// $id = $_GET['id'];
// $site = $db->select('ai_sites', ['id' => $id], 1)->row();
// if ($site == null) {
//     redirect('index.php', 'Opps!! Some error occured. Try again', 'danger');
// } else if ($site->status == 1) {
//     redirect('property-view.php?id=' . $id);
// }
// $me = $db->select('ai_users', ['id' => user_id()], 1)->row();
// if ($me == null) {
//     redirect('index.php', 'Invalid access not allowed', 'error');
// }
// $ownerName = $me->first_name . ' ' . $me->last_name;

$dists = $db->select('ai_districts', [], false, 'dist_name ASC')->result();
$arr_brokers = $db->select('ai_users', ['user_type' => USER_BROKER, 'kyc_status' => KYC_APPROVED])->result();
$arr_munsi = $db->select('ai_users', ['user_type' => USER_MUNSI, 'kyc_status' => KYC_APPROVED])->result();
$arr_owners = $db->select('ai_users', ['user_type' => USER_LAND_OWNER, 'kyc_status' => KYC_APPROVED])->result();

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

    $rasid = do_upload('rasid');
    if ($rasid != '') {
        $sb['rasid_photo'] = $rasid;
    }

    $video = do_upload('video');
    if ($video != '') $sb['video'] = $video;
    $sb['status'] = 0; // Pending for approved property list
    $mobile = $_POST["mobile"];
    $userFound = $db->select("ai_users", ["mobile_number" => $mobile], 1)->row();
    if (is_object($userFound)) {
        $userid = $userFound->id;
        $chk = $db->select('ai_sites', ['user_id' => $userid], false)->result();
        if ($chk) {
            if (count($chk) < 2) {
                $sb['user_id'] = $userFound->id;
                $sb['created'] = date('Y-m-d H:i:s');
                $sb['details'] = $mobile;
                $db->insert('ai_sites', $sb);
                redirect(base_url('dashboard/properties.php'), 'Your property has been saved successfully', 'success');
            }
            redirect(base_url('dashboard/properties.php'), 'You can post up to 2 properties. Please upgrade your account to add more. click on Membership', 'danger');
        } else {
            $sb['user_id'] = $userFound->id;
            $sb['created'] = date('Y-m-d H:i:s');
            $sb['details'] = $mobile;
            $db->insert('ai_sites', $sb);
            redirect(base_url('dashboard/properties.php'), 'Your property has been saved successfully', 'success');
        }
    }
}
include_once "common/header.php";
?>
<div id="origin" class="container py-3">
    <?= front_view('common/alert'); ?>
    <form action="" @submit="submitForm" method="post" class="mb-3" enctype="multipart/form-data">
        <div class="bg-white p-3 shadow-sm1 rounded" id="errview">

            <h5>अपने जमीन के चारो कोनो का फोटो बनायें, दाएं बाएं सामने और पीछे का फोटो दर्ज़ करें।</h5>
            <hr />
            <!-- <div class="row mb-4">
                <div class="col-sm-3">
                    <div class="land-photo">
                        <img src="" class="img-fluid" />
                    </div>
                    <input type="file" name="pic_a" accept="image/*" class="form-control" >
                </div>
                <div class="col-sm-3">
                    <div class="land-photo">
                        <img src="" class="img-fluid" />
                    </div>
                    <input type="file" name="pic_b" accept="image/*" class="form-control">
                </div>
                <div class="col-sm-3">
                    <div class="land-photo">
                        <img src="" class="img-fluid" />
                    </div>
                    <input type="file" name="pic_c" accept="image/*" class="form-control">
                </div>
                <div class="col-sm-3">

                    <div class="land-photo">
                        <img src="" class="img-fluid" />
                    </div>
                    <input type="file" name="pic_d" accept="image/*" class="form-control">
                </div>
            </div> -->
            <div class="row mb-4">
                <div class="col-sm-3">
                    <div class="land-photo">
                        <img src="" id="img-front" class="img-fluid" />
                    </div>
                    <input type="file" name="pic_a" accept="image/*" class="form-control" required onchange="previewImage(event, 'img-front')">
                </div>
                <div class="col-sm-3">
                    <div class="land-photo">
                        <img src="" id="img-back" class="img-fluid" />
                    </div>
                    <input type="file" name="pic_b" accept="image/*" class="form-control" required onchange="previewImage(event, 'img-back')">
                </div>
                <div class="col-sm-3">
                    <div class="land-photo">
                        <img src="" id="img-left" class="img-fluid" />
                    </div>
                    <input type="file" name="pic_c" accept="image/*" class="form-control" onchange="previewImage(event, 'img-left')">
                </div>
                <div class="col-sm-3">
                    <div class="land-photo">
                        <img src="" id="img-right" class="img-fluid" />
                    </div>
                    <input type="file" name="pic_d" accept="image/*" class="form-control" onchange="previewImage(event, 'img-right')">
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-2">
                    <label>Property Type</label>
                    <select v-model="form.property_type" class="form-select" name="form[property_type]">
                        <option value="">Select</option>
                        <option value="Land">Land</option>
                        <option value="Office">Office</option>
                        <option value="Flat">Flat</option>
                        <option value="House">House</option>
                        <option value="Villa">Villa</option>
                        <option value="Farm House">Farm House</option>
                    </select>
                </div>
                <div class="col-sm-3">
                    <label>Property For</label>
                    <select v-model="form.property_for" class="form-select" name="form[property_for]">
                        <option value="Rent">Rent</option>
                        <option value="Sell">Sell</option>
                    </select>
                </div>
                <div class="col-sm-3">
                    <label for="">Upload Video</label>
                    <input type="file" name="video" accept="video/*" class="form-control">
                    <span class="text-danger"> *Video Size less then 50MB</span>
                </div>
                <div class="col-sm-4">
                    <label for="id_rasid" class="mb-1">
                        भूमि रसीद अपलोड करें
                    </label>

                    <input type="file" required name="rasid" accept="application/pdf" id="id_rasid" class="form-control p-1 mb-1">

                </div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-9">
                    <div class="row">
                        <div class="col-sm-6">
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
                        <div class="col-sm-6">
                            <label>अंचल</label>
                            <select name="form[zone_id]" v-model="zone_id" class="form-select">
                                <option value="">Select</option>
                                <option v-for="item in zones" :value="item.id">{{ item.zone_name }}</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-9">
                    <label>Title </label>
                    <input type="text" name="form[site_title]" v-model="form.site_title" class="form-control">
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-9">
                    <label>Property Details </label>
                    <textarea name="form[details]" rows="4" class="form-control"></textarea>
                </div>
            </div>
            <di class="row">
                <div class="col-sm-7">
                    <fieldset v-if="form.property_type=='Land'">
                        <legend>Property Details</legend>
                        <div class="row mb-3 g-3">
                            <div class="col-sm-6">
                                <label>मौजा</label>
                                <input type="text" name="form[mauja]" v-model="form.mauja" class="form-control">
                            </div>
                            <div class="col-sm-6">
                                <label>जमाबंदी संख्या </label>
                                <input type="text" name="form[jamabandi_no]" v-model="form.jamabandi_no" class="form-control">
                            </div>
                            <div class="col-sm-6">
                                <label>भाग बर्तमान </label>
                                <input type="text" name="form[bhag_vartman]" v-model="form.bhag_vartman" class="form-control">
                            </div>
                            <div class="col-sm-6">
                                <label>पृष्ठ संख्या </label>
                                <input type="text" name="form[page_no]" v-model="form.page_no" class="form-control">
                            </div>
                            <div class="col-sm-6">
                                <label>मौजा थाना संख्या</label>
                                <input type="text" name="form[thana_no]" v-model="form.thana_no" class="form-control">
                            </div>

                            <div class="col-sm-6">
                                <label>जमाबंदी रैयत का नाम</label>
                                <input type="text" name="form[jamabani_raiyat_name]" v-model="form.jamabani_raiyat_name" class="form-control">
                            </div>
                            <div class="col-sm-6">
                                <label>अभिभावक का नाम </label>
                                <input type="text" v-model="form.guardian_name" name="form[guardian_name]" class="form-control">
                            </div>
                            <div class="col-sm-6">
                                <label>खाता संख्या </label>
                                <input type="text" v-model="form.khata_no" name="form[khata_no]" class="form-control">
                            </div>
                            <div class="col-sm-6">
                                <label>खेसरा संख्या</label>
                                <input type="text" v-model="form.khesra_no" name="form[khesra_no]" class="form-control">
                            </div>
                        </div>
                    </fieldset>
                    <fieldset v-if="form.property_type!='Land'">
                        <legend>Ammenities</legend>
                        <div class="d-flex gap-2">
                            <input type="text" v-model="itemTitle" class="form-control" placeholder="e.g. Number of Rooms">
                            <button type="button" @click="addItem" class="btn btn-sm btn-primary">ADD</button>
                        </div>
                        <div v-if="items.length > 0">
                            <hr />
                            <p><b>Added List</b></p>
                            <ol>
                                <li v-for="(item1, indx) in items" class="mb-1">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span>{{item1}}</span>
                                        <button type="button" @click="removeItem(indx)" class="badge bg-danger border-0">Remove</button>
                                    </div>
                                </li>
                            </ol>
                        </div>
                    </fieldset>
                    <div class="row mb-3 g-3">
                        <div class="col-sm-6">
                            <label>कुल क्षेत्र </label>
                            <input type="text" v-model="form.total_area" name="form[total_area]" class="form-control">
                        </div>
                        <div class="col-sm-6">
                            <label>एकड/डिसमिल</label>
                            <select v-model="form.area_unit" name="form[area_unit]" class="form-select">
                                <option value="">Select</option>
                                <option value="acre">एकड </option>
                                <option value="dismile">डिसमिल </option>
                                <option value="kattha">कट्ठा </option>
                                <option value="sqft">वर्ग फुट </option>
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <label>कुल राशि </label>
                            <input type="text" name="form[total_amount]" v-model="form.total_amount" class="form-control">
                        </div>
                        <div v-if="sendOtp=='otp'">
                            <div class="mb-2">
                                <label>Mobile Number</label>
                                <input type="mobile" maxlength="10" v-model="mobile" name="mobile" class="form-control" placeholder="Mobile Number" required />
                            </div>
                            <div v-if="otpSent" class="mb-2">
                                <label>Enter OTP</label>
                                <input type="mobile" maxlength="6" v-model="otp" class="form-control" placeholder="OTP" required />
                            </div>
                            <div class="text-danger">
                                {{otpMsg}}
                            </div>

                            <div class="mb-2">
                                <button type="button" @click="getOTP" :disabled="otpSent" class="btn btn-primary">Get OTP</button>
                                <button type="button" @click="verifyOTP" :disabled="!otpSent" class="btn btn-warning">Verify OTP</button>
                            </div>

                        </div>

                    </div>
                </div>
                <div class="col-sm-5">
                    <div class="border" id="mapview" style="height: 300px;"></div>
                    <div class="my-2">
                        <input type="text" id="map-adrs" name="form[address]" value="" readonly class="form-control">
                    </div>
                    <div class="small text-danger">Please Drag to Set your Plot location.</div>
                </div>
            </di>

            <div class="text-center1">
                <input type="hidden" name="btnupdate" value="Update">
                <button :disabled="!isSubmit" class="btn btn-warning btn-submit1">SUBMIT</button>
                <a href="index.php" class="btn btn-secondary">CANCEL</a>
            </div>
        </div>
        <input type="hidden" id="latid" name="form[lat]" :value="lat" />
        <input type="hidden" id="lngid" name="form[lng]" :value="lng" />
        <input type="hidden" name="form[amenities]" :value="amlist">
    </form>

    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">{{ title }}</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div v-if="utype=='owner'">
                        <?php
                        // if (in_array($me->user_type, [USER_BROKER, USER_MUNSI, USER_AMIN])) {
                        ?>
                        <div class="mb-2">
                            <div class="mb-2">
                                <label for="sjm">
                                    <input type="radio" v-model="opt_val" name="owner" value="1" id="sjm" /> आप स्वयं जमीन मालिक है यहाँ क्लिक करें ।
                                </label>
                            </div>
                            <div class="mb-2">
                                <label for="sjm1">
                                    <input type="radio" v-model="opt_val" id="sjm1" name="owner" value="2" /> नया अकाउंट बनाने के लिए <a href="signup.php">यहां क्लिक करें</a> ।
                                </label>
                            </div>
                            <div class="mb-1">
                                <label><input type="radio" v-model="opt_val" name="owner" value="3" /> {{ title }}</label>
                            </div>
                        </div>
                        <?php
                        // }
                        ?>
                        <select :disabled="opt_val!=3" v-model="owner_id" class="form-select">
                            <option value="" disabled readonly>Select</option>
                            <?php
                            foreach ($arr_owners as $item) {
                            ?>
                                <option value="<?= $item->id; ?>"><?= $item->aadhar_name; ?> (<?= $item->mobile_number; ?>)</option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                    <div v-if="utype=='broker'">
                        <?php
                        // if (in_array($me->user_type, [USER_LAND_OWNER, USER_MUNSI, USER_AMIN])) {
                        ?>
                        <div class="mb-2">
                            <div class="mb-2">
                                <label for="sjm">
                                    <input type="radio" v-model="opt_val" name="broker" value="1" id="sjm" /> आप स्वयं जमीन ब्रोकर है यहाँ क्लिक करें ।
                                </label>
                            </div>
                            <div class="mb-2">
                                <label for="sjm1">
                                    <input type="radio" v-model="opt_val" id="sjm1" name="broker" value="2" /> नया अकाउंट बनाने के लिए <a href="signup.php">यहां क्लिक करें</a> ।
                                </label>
                            </div>
                            <div class="mb-1">
                                <label><input type="radio" v-model="opt_val" name="broker" value="3" /> {{ title }}</label>
                            </div>
                        </div>
                        <?php
                        // }
                        ?>
                        <select :disabled="opt_val!=3" v-model="broker_id" class="form-select">
                            <option value="">Select</option>
                            <?php
                            foreach ($arr_brokers as $item) {
                            ?>
                                <option value="<?= $item->id; ?>"><?= $item->aadhar_name; ?> (<?= $item->mobile_number; ?>)</option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                    <div v-if="utype=='munsi'">
                        <div class="mb-1">
                            <label>{{ title }}</label>
                        </div>
                        <select v-model="munsi_id" class="form-select">
                            <option value="">Select</option>
                            <?php
                            foreach ($arr_munsi as $item) {
                            ?>
                                <option value="<?= $item->id; ?>"><?= $item->aadhar_name; ?> (<?= $item->mobile_number; ?>)</option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" @click="saveAction" :disabled="saving" class="btn btn-sm btn-primary">{{ saving ? 'Saving...' : 'Save changes' }}</button>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include_once "common/footer.php";
?>

<script>
    let vm = new Vue({
        el: '#origin',
        data: {
            errmsg: '',
            errcls: '',
            mobile: '',
            isSubmit: false,
            otp: '',
            otpSent: false,
            sendOtp: 'otp',

            title: '',
            utype: '',
            saving: false,
            broker_id: '',
            munsi_id: '',
            owner_id: '',
            id: 0,
            zones: [],
            dist_id: '',
            zone_id: '',
            gps_on: false,
            lat: '',
            lng: '',
            opt_val: 1,
            errors: [],
            otpMsg: "",
            form: {
                property_type: '',
                property_for: '',
                site_title: '',
                details: '',
                mauja: '',
                jamabandi_no: '',
                bhag_vartman: '',
                page_no: '',
                thana_no: '',
                jamabani_raiyat_name: '',
                guardian_name: '',
                khata_no: '',
                khesra_no: '',
                total_area: '',
                total_amount: '',
                area_unit: '',
                address: ''
            },
            itemTitle: '',
            items: [],
            amlist: ''
        },
        methods: {
            submitForm: function(e) {
                let form = this.form;
                let errors = [];
                if (form.site_title.trim() == '') errors.push("Plese enter Title");
                // if (form.details.trim() == '') errors.push("Plese enter Property Details");

                if (this.form.property_type == 'Land') {
                    if (form.mauja.trim() == '') errors.push("Plese enter Mauja");
                    if (form.jamabandi_no.trim() == '') errors.push("Plese enter Jamabandi Number");
                    if (form.bhag_vartman.trim() == '') errors.push("Plese enter Bhag Vartman");
                    if (form.page_no.trim() == '') errors.push("Plese enter Page Number");
                    if (form.thana_no.trim() == '') errors.push("Plese enter Thana Number");
                    if (form.jamabani_raiyat_name.trim() == '') errors.push("Plese enter Jamabandi Raiyat Name");
                    if (form.guardian_name.trim() == '') errors.push("Plese enter Guardian Name");
                    if (form.khata_no.trim() == '') errors.push("Plese enter Khata Number");
                    if (form.khesra_no.trim() == '') errors.push("Plese enter Khesra Number");

                } else if (this.form.property_type == '') {
                    errors.push("Plese select Property Type");
                }

                if (form.total_area.trim() == '') errors.push("Plese enter Total Area");
                if (form.total_amount.trim() == '') errors.push("Plese enter Total Amount");
                if (form.area_unit.trim() == '') errors.push("Plese enter Acre/Dismil");
                // if (form.address.trim() == '') errors.push("Plese set Location on Map");

                if (errors.length > 0) {
                    this.errors = errors;
                    e.preventDefault();
                    document.getElementById('errview').scrollIntoView();
                }
            },
            removeItem: function(sl) {
                this.items.splice(sl, 1);
                this.amlist = this.items.toString();
            },
            addItem: function() {
                if (this.itemTitle.trim() != '') {
                    this.items.push(this.itemTitle);
                    this.itemTitle = '';
                    this.amlist = this.items.toString();
                }
            },
            set_zones: function() {
                this.zones = [];
                api_call('zones', {
                    dist_id: this.dist_id
                }).then(resp => {
                    if (resp.success) this.zones = resp.data;
                })
            },
            setModel: function(utype) {
                this.utype = utype;
                if (utype == 'broker') {
                    this.title = "Select Broker";
                } else if (utype == 'owner') {
                    this.title = "Select Land Owner";
                } else if (utype == 'munsi') {
                    this.title = "Select Munsi";
                }
                this.saving = false
            },
            saveAction: function() {
                this.saving = true;
                let field_name = null;
                let field_value = null;
                if (this.utype == 'broker') {
                    field_name = 'broker_id';
                    if (this.opt_val == 1) {
                        field_value = '<?= user_id(); ?>';
                    } else {
                        field_value = this.broker_id
                    }
                } else if (this.utype == 'munsi') {
                    field_name = 'munsi_id';
                    field_value = this.munsi_id
                } else if (this.utype == 'owner') {
                    field_name = 'owner_id';
                    if (this.opt_val == 1) {
                        field_value = '<?= user_id(); ?>';
                    } else {
                        field_value = this.owner_id
                    }
                }
                api_call('site-update', {
                    prop_id: this.id,
                    field_name: field_name,
                    field_value: field_value
                }).then(result => {
                    if (result.success) {
                        window.location.reload();
                    } else {
                        alert(result.message);
                    }
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
            },
            getOTP: function() {
                if (this.mobile.length != 10) {
                    this.otpMsg = "Enter 10 Digit mobile number only."
                    return;
                }
                api_call('get-leads-otp', {
                    mobile: this.mobile
                }).then(resp => {
                    if (resp.success) {
                        this.otpSent = true;
                    } else {
                        alert(resp.message);
                    }
                })
            },
            verifyOTP: function() {
                if (this.otp.length != 4) {
                    alert("Enter OTP received on your mobile.");
                    return;
                }
                api_call('verify-otp', {
                    mobile: this.mobile,
                    otp: this.otp
                }).then(resp => {
                    if (resp.success) {
                        this.otpSent = false;
                        this.isSubmit = true;
                        alert("varification successfully");

                    } else {
                        alert(resp.message);
                    }
                })
            }

        },
        created: function() {
            this.dist_id = '';
            this.zone_id = '';
            this.set_zones();
            if (this.property_type == '') this.property_type = 'Land';
            // if (navigator.geolocation) {
            //     navigator.geolocation.getCurrentPosition(this.updateLocation, function() {
            //         alert("Bihar Bhumi Seva wants to access your Location.");
            //     })
            // } else {
            //     alert("GPS Location not supported")
            // }

        }
    })
</script>

<script async src="https://maps.googleapis.com/maps/api/js?key=<?= GOOGLE_API_KEY ?>&loading=async&callback=initMap">
</script>
<script>
    function initMap() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {

                loadDragableMap(position.coords.latitude, position.coords.longitude);


            }, function() {
                alert("Bihar Bhumi Seva wants to access your Location.");
            })
        } else {
            alert("GPS Location not supported")
        }
    }

    async function loadDragableMap(latitude, longitude) {
        // Request needed libraries.
        const {
            Map,
            InfoWindow
        } = await google.maps.importLibrary("maps");
        const {
            AdvancedMarkerElement
        } = await google.maps.importLibrary("marker");
        const map = new Map(document.getElementById("mapview"), {
            center: {
                lat: latitude,
                lng: longitude
            },
            zoom: 12,
            mapId: "4504f8b37365c3d0",
        });
        const infoWindow = new InfoWindow();
        const draggableMarker = new AdvancedMarkerElement({
            map,
            position: {
                lat: latitude,
                lng: longitude
            },
            gmpDraggable: true,
            title: "Drag to Reposition",
        });

        draggableMarker.addListener("dragend", (event) => {
            const position = draggableMarker.position;
            document.querySelector('#latid').value = position.lat;
            document.querySelector('#lngid').value = position.lng;

            let url = `https://maps.googleapis.com/maps/api/geocode/json?latlng=${position.lat},${position.lng}&key=` + '<?= GOOGLE_API_KEY; ?>';
            fetch(url).then(ab => ab.json()).then(resp => {
                if (resp.status == "OK") {
                    let pc = resp.results[0];
                    document.querySelector('#map-adrs').value = pc.formatted_address;
                }
            })
        });
    }
</script>
<script>
    function previewImage(event, imgId) {
        const file = event.target.files[0];
        const reader = new FileReader();

        reader.onload = function(e) {
            // Update the image source with the uploaded file
            document.getElementById(imgId).src = e.target.result;
        };

        if (file) {
            reader.readAsDataURL(file);
        }
    }
</script>