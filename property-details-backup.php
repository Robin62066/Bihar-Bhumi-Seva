<?php
include "config/autoload.php";
if (!is_login()) redirect('login.php', 'You must login to view', 'error');
if (!isset($_GET['id'])) redirect('index.php', 'Invalid access not allowed', 'error');

$id = $_GET['id'];
$site = $db->select('ai_sites', ['id' => $id], 1)->row();
if ($site == null) {
    redirect('index.php', 'Opps!! Some error occured. Try again', 'danger');
} else if ($site->status == 1) {
    redirect('property-view.php?id=' . $id);
}
$me = $db->select('ai_users', ['id' => user_id()], 1)->row();
$ownerName = '-';
if ($site->owner_id > 0) {
    $own = $db->select('ai_users', ['id' => $site->owner_id], 1)->row();
    $ownerName = $own->first_name . ' ' . $own->last_name;
}

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
    $db->update('ai_sites', $sb, ['id' => $id]);

    redirect(base_url('dashboard/properties.php'), 'Your property has been saved successfully', 'success');
}
include_once "common/header.php";
?>
<div id="origin" class="container py-3">
    <?= front_view('common/alert'); ?>
    <form action="" method="post" class="mb-3" enctype="multipart/form-data">
        <div class="bg-white p-3 shadow-sm1 rounded">
            <div class="row mb-3">
                <div class="col-sm-8">
                    <div class="mb-2 d-flex gap-4 align-items-center border p-2">
                        <div class="text-success fs-4">
                            <i class="bi-check2-square"></i>
                        </div>
                        <div class="flex-fill">
                            जमीन मालिक का विवरण डालें ।
                        </div>
                        <div>
                            <button type="button" @click="setModel('owner')" class="btn btn-sm btn-primary text-warning" data-bs-toggle="modal" data-bs-target="#exampleModal">VERIFY</button>
                            <!-- <?php
                                    // if ($site->owner_id == 0 && $me->user_type != USER_LAND_OWNER) {
                                    ?>
                            <?php
                            // } else {
                            ?>
                                <button disabled class="btn btn-sm btn-success text-warning">VERIFIED</button>
                                <button type="button" @click="setModel('owner')" class="btn btn-sm btn-primary text-warning" data-bs-toggle="modal" data-bs-target="#exampleModal">CHANGE</button>
                            <?php
                            // }
                            ?> -->

                        </div>
                    </div>
                    <div class="mb-2 d-flex gap-4 align-items-center border p-2">
                        <div class="text-success fs-4">
                            <i class="bi-check2-square"></i>
                        </div>
                        <div class="flex-fill">
                            अपना दलाल या ब्रोकर का विवरण डालें ।
                        </div>
                        <div>
                            <button type="button" class="btn btn-sm btn-success text-warning">VERIFIED</button>
                            <button type="button" @click="setModel('broker')" class="btn btn-sm btn-primary text-warning" data-bs-toggle="modal" data-bs-target="#exampleModal">CHANGE</button>
                            <?php
                            // if ($site->broker_id > 0) {
                            ?>
                            <?php
                            // } else {
                            ?>
                            <!-- <button type="button" @click="setModel('broker')" class="btn btn-sm btn-primary text-warning" data-bs-toggle="modal" data-bs-target="#exampleModal">VERIFY</button> -->
                            <?php
                            // }
                            ?>

                        </div>
                    </div>
                    <div class="mb-2 d-flex gap-4 align-items-center border p-2">
                        <div class="text-success fs-4">
                            <i class="bi-check2-square"></i>
                        </div>
                        <div class="flex-fill">
                            जमीन के रजिस्ट्री के मुंसी का विवरण दर्ज़ करें ।
                        </div>
                        <div>
                            <?php
                            if ($site->munsi_id > 0) {
                            ?>
                                <button type="button" disabled class="btn btn-sm btn-success text-warning">VERIFIED</button>
                                <button type="button" @click="setModel('munsi')" class="btn btn-sm btn-primary text-warning" data-bs-toggle="modal" data-bs-target="#exampleModal">CHANGE</button>
                            <?php
                            } else {
                            ?>
                                <button type="button" @click="setModel('munsi')" class="btn btn-sm btn-primary text-warning" data-bs-toggle="modal" data-bs-target="#exampleModal">VERIFY</button>
                            <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="profile-info-owner text-center border p-3">
                        <div class="mb-2">Property Id: <?= $id; ?></div>
                        <div class="mb-2"><?= $ownerName; ?></div>
                        <label for="id_rasid" class="mb-1">
                            भूमि रसीद <span class="text-danger"><u>अपलोड करें</u></span>
                        </label>
                        <input type="file" required name="rasid" accept="application/pdf" id="id_rasid" class="form-control p-1 mb-1">
                        <?php
                        if ($site->rasid_photo != '') {
                        ?>
                            <a href="<?= base_url(upload_dir($site->rasid_photo)) ?>"><?= $site->rasid_photo; ?></a>
                        <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
            <h5>अपने जमीन के चारो कोनो का फोटो बनायें, दाएं बाएं सामने और पीछे का फोटो दर्ज़ करें।</h5>
            <hr />
            <div class="row mb-4">
                <div class="col-sm-3">
                    <?php
                    if ($site->photo_front != '') {
                    ?>
                        <div class="land-photo">
                            <img src="<?= base_url(upload_dir($site->photo_front)) ?>" class="img-fluid" />
                        </div>
                    <?php
                    } else {
                    ?>
                        <div class="border p-5 bg-light">
                        </div>
                    <?php
                    }
                    ?>
                    <input type="file" name="pic_a" accept="image/*" class="form-control" required>
                </div>
                <div class="col-sm-3">
                    <?php
                    if ($site->photo_back != '') {
                    ?>
                        <div class="land-photo">
                            <img src="<?= base_url(upload_dir($site->photo_back)) ?>" class="img-fluid" />
                        </div>
                    <?php
                    } else {
                    ?>
                        <div class="border p-5 bg-light">
                        </div>
                    <?php
                    }
                    ?>
                    <input type="file" name="pic_b" accept="image/*" class="form-control">
                </div>
                <div class="col-sm-3">
                    <?php
                    if ($site->photo_left != '') {
                    ?>
                        <div class="land-photo">
                            <img src="<?= base_url(upload_dir($site->photo_left)) ?>" class="img-fluid" />
                        </div>
                    <?php
                    } else {
                    ?>
                        <div class="border p-5 bg-light">
                        </div>
                    <?php
                    }
                    ?>
                    <input type="file" name="pic_c" accept="image/*" class="form-control">
                </div>
                <div class="col-sm-3">
                    <?php
                    if ($site->photo_right != '') {
                    ?>
                        <div class="land-photo">
                            <img src="<?= base_url(upload_dir($site->photo_right)) ?>" class="img-fluid" />
                        </div>
                    <?php
                    } else {
                    ?>
                        <div class="border p-5 bg-light">
                        </div>
                    <?php
                    }
                    ?>
                    <input type="file" name="pic_d" accept="image/*" class="form-control">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-sm-3">
                    <label>Property Type</label>
                    <select v-model="property_type" class="form-select" name="form[property_type]">
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
                    <select v-model="rent" class="form-select" name="form[property_for]">
                        <option value="Rent">Rent</option>
                        <option value="Sell">Sell</option>
                    </select>
                </div>
                <div class="col-sm-3">
                    <label for="">Upload Video</label>
                    <input type="file" name="video" accept="video/*" style="width: 250px;">
                    <span class="text-danger"> *Video Size less then 50MB</span>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-9">
                    <label>Title </label>
                    <input type="text" name="form[site_title]" required value="<?= $site->site_title; ?>" class="form-control">
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-9">
                    <label>Property Details </label>
                    <textarea name="form[details]" rows="2" #required class="form-control"><?= $site->details; ?></textarea>
                </div>
            </div>

            <h5>Details </h5>

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
                <div v-if="rent === 'Rent'" class="col-sm-3">
                    <label>मौजा</label>
                    <input type="text" required name="form[mauja]" class="form-control" value="<?= $site->mauja; ?>">
                </div>

                <div v-if="rent === 'Sell'" class="col-sm-3">
                    <label>मौजा</label>
                    <input type="text" name="form[mauja]" class="form-control" value="<?= $site->mauja; ?>">
                </div>
            </div>
            <div v-if="rent === 'Rent'" class="row mb-3">
                <div class="col-sm-2">
                    <label>जमाबंदी संख्या </label>
                    <input type="text" required name="form[jamabandi_no]" value="<?= $site->jamabandi_no; ?>" class="form-control">
                </div>
                <div class="col-sm-2">
                    <label>भाग बर्तमान </label>
                    <input type="text" required name="form[bhag_vartman]" value="<?= $site->bhag_vartman; ?>" class="form-control">
                </div>
                <div class="col-sm-2">
                    <label>पृष्ठ संख्या </label>
                    <input type="text" required name="form[page_no]" value="<?= $site->page_no; ?>" class="form-control">
                </div>
                <div class="col-sm-2">
                    <label>मौजा थाना संख्या</label>
                    <input type="text" #required name="form[thana_no]" value="<?= $site->thana_no; ?>" class="form-control">
                </div>
            </div>
            <div v-if="rent === 'Sell'" class="row mb-3">
                <div class="col-sm-2">
                    <label>जमाबंदी संख्या </label>
                    <input type="text" name="form[jamabandi_no]" value="<?= $site->jamabandi_no; ?>" class="form-control">
                </div>
                <div class="col-sm-2">
                    <label>भाग बर्तमान </label>
                    <input type="text" name="form[bhag_vartman]" value="<?= $site->bhag_vartman; ?>" class="form-control">
                </div>
                <div class="col-sm-2">
                    <label>पृष्ठ संख्या </label>
                    <input type="text" name="form[page_no]" value="<?= $site->page_no; ?>" class="form-control">
                </div>
                <div class="col-sm-2">
                    <label>मौजा थाना संख्या</label>
                    <input type="text" name="form[thana_no]" value="<?= $site->thana_no; ?>" class="form-control">
                </div>
            </div>

            <div v-if="rent === 'Rent'" class="row">
                <div class="col-sm-6">
                    <div class="row mb-3">
                        <div class="col-sm-6">
                            <label>जमाबंदी रैयत का नाम</label>
                            <input type="text" required name="form[jamabani_raiyat_name]" value="<?= $site->jamabani_raiyat_name; ?>" class="form-control">
                        </div>
                        <div class="col-sm-6">
                            <label>अभिभावक का नाम </label>
                            <input type="text" required name="form[guardian_name]" value="<?= $site->guardian_name; ?>" class="form-control">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-6">
                            <label>खता संख्या </label>
                            <input type="text" name="form[khata_no]" value="<?= $site->khata_no; ?>" class="form-control">
                        </div>
                        <div class="col-sm-6">
                            <label>खेसरा संख्या</label>
                            <input type="text" name="form[khesra_no]" value="<?= $site->khesra_no; ?>" class="form-control">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-6">
                            <label>एकड/डिसमिल </label>
                            <input type="text" required name="form[total_area]" value="<?= $site->total_area; ?>" class="form-control">
                        </div>
                        <div class="col-sm-6">
                            <label>कुल राशि </label>
                            <input type="text" required name="form[total_amount]" value="<?= $site->total_amount; ?>" class="form-control">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-6">
                            <label>कुल क्षेत्र </label>
                            <select required name="form[area_unit]" class="form-select">
                                <option <?= ($site->area_unit == 'dismile') ? 'selected' : '' ?> value="dismile">डिसमिल </option>
                                <option <?= ($site->area_unit == 'kattha') ? 'selected' : '' ?> value="kattha">कट्ठा </option>
                                <option <?= ($site->area_unit == 'sqft') ? 'selected' : '' ?> value="sqft">वर्ग फुट </option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-12">
                            <label>प्लाट स्थान का नाम जोड़ें</label>
                            <input type="text" id="map-adrs" required name="form[address]" value="<?= $site->address; ?>" readonly class="form-control">
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="border" id="mapview" style="height: 300px;"></div>
                    <div class="small text-danger">Please Drag to Set your Plot location.</div>
                </div>
            </div>

            <div v-if="rent === 'Sell'" class="row">
                <div class="col-sm-6">
                    <div class="row mb-3">
                        <div class="col-sm-6">
                            <label>जमाबंदी रैयत का नाम</label>
                            <input type="text" name="form[jamabani_raiyat_name]" value="<?= $site->jamabani_raiyat_name; ?>" class="form-control">
                        </div>
                        <div class="col-sm-6">
                            <label>अभिभावक का नाम </label>
                            <input type="text" name="form[guardian_name]" value="<?= $site->guardian_name; ?>" class="form-control">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-6">
                            <label>खता संख्या </label>
                            <input type="text" name="form[khata_no]" value="<?= $site->khata_no; ?>" class="form-control">
                        </div>
                        <div class="col-sm-6">
                            <label>खेसरा संख्या</label>
                            <input type="text" name="form[khesra_no]" value="<?= $site->khesra_no; ?>" class="form-control">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-6">
                            <label>एकड/डिसमिल </label>
                            <input type="text" name="form[total_area]" value="<?= $site->total_area; ?>" class="form-control">
                        </div>
                        <div class="col-sm-6">
                            <label>कुल राशि </label>
                            <input type="text" name="form[total_amount]" value="<?= $site->total_amount; ?>" class="form-control">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-6">
                            <label>कुल क्षेत्र </label>
                            <select name="form[area_unit]" class="form-select">
                                <option <?= ($site->area_unit == 'dismile') ? 'selected' : '' ?> value="dismile">डिसमिल </option>
                                <option <?= ($site->area_unit == 'kattha') ? 'selected' : '' ?> value="kattha">कट्ठा </option>
                                <option <?= ($site->area_unit == 'sqft') ? 'selected' : '' ?> value="sqft">वर्ग फुट </option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-12">
                            <label>प्लाट स्थान का नाम जोड़ें</label>
                            <input type="text" id="map-adrs" name="form[address]" value="<?= $site->address; ?>" readonly class="form-control">
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="border" id="mapview" style="height: 300px;"></div>
                    <div class="small text-danger">Please Drag to Set your Plot location.</div>
                </div>
            </div>

            <div class="text-center1">
                <input type="hidden" name="btnupdate" value="Update">
                <button :disabled="!gps_on" class="btn btn-warning btn-submit">SUBMIT</button>
                <a href="index.php" class="btn btn-secondary">CANCEL</a>
            </div>
        </div>
        <input type="hidden" id="latid" name="form[lat]" :value="lat" />
        <input type="hidden" id="lngid" name="form[lng]" :value="lng" />
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
            rent: '<?= $site->property_for; ?>',
            errmsg: '',
            errcls: '',
            title: '',
            utype: '',
            saving: false,
            broker_id: '',
            munsi_id: '',
            owner_id: '',
            id: '<?= $id; ?>',
            zones: [],
            dist_id: '',
            zone_id: '',
            gps_on: false,
            lat: '',
            lng: '',
            opt_val: 1,
            property_type: '<?= $site->property_type; ?>'
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
            }
        },
        created: function() {
            this.dist_id = '<?= $site->dist_id; ?>';
            this.zone_id = '<?= $site->zone_id; ?>';
            this.set_zones();

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
    // function mapDraw(latitude, longitude) {
    //     var myLatlng = new google.maps.LatLng(latitude, longitude);
    //     var myOptions = {
    //         zoom: 14,
    //         center: myLatlng,
    //         mapTypeId: google.maps.MapTypeId.ROADMAP
    //     }
    //     var map = new google.maps.Map(document.getElementById("mapview"), myOptions);
    // }

    function initMap() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                // mapDraw(position.coords.latitude, position.coords.longitude);
                <?php
                if ($site->lat != '' && $site->lng != '') {
                ?>
                    loadDragableMap(<?= $site->lat; ?>, <?= $site->lng; ?>);
                <?php
                } else {
                ?>
                    loadDragableMap(position.coords.latitude, position.coords.longitude);
                <?php
                }
                ?>

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
            zoom: 14,
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
            //infoWindow.close();
            // infoWindow.setContent(
            //     `Pin dropped at: ${position.lat}, ${position.lng}`,
            // );
            //infoWindow.open(draggableMarker.map, draggableMarker);

            let url = `https://maps.googleapis.com/maps/api/geocode/json?latlng=${position.lat},${position.lng}&key=` + '<?= GOOGLE_API_KEY; ?>';
            fetch(url).then(ab => ab.json()).then(resp => {
                console.log(resp);
                if (resp.status == "OK") {
                    let pc = resp.results[0];
                    document.querySelector('#map-adrs').value = pc.formatted_address;
                }
            })
        });
    }
</script>