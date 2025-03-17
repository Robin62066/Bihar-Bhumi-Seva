<?php

use Razorpay\Api\Api;

include "config/autoload.php";
$states = $db->select('ai_states', [], false, 'state_name ASC')->result();
$dists = $db->select('ai_districts', [], false, 'dist_name ASC')->result();
$items = $db->select('ai_sites', ['status' => 1], "0, 10", "id DESC")->result();
$step = $_GET['step'] ?? 1;
$app_id = session()->app_id;
if ($app_id == null && $step != 1) {
    redirect('apply-mutation.php?step=1', "Something wrong, Plese fill the details", 'danger');
} else if ($app_id != null) {
    $app = $db->select('ai_mutations', ['id' => $app_id], 1)->row();
    if ($app == null) {
        session()->remove('app_id');
        redirect('apply-mutation.php?step=1', "Form already submitted successfully. Wait for approval.", 'danger');
    } else if ($step > ($app->step + 1)) {
        redirect('apply-mutation.php?step=' . ($app->step + 1), "Please fill details");
    }
}
if ($step == 6) {
    $app = $db->select('ai_mutations', ['id' => $app_id], 1)->row();
    if (isset($_POST['clicked'])) {
        $token = "BS" . str_pad(time() . $app_id, 10, '0', STR_PAD_LEFT);
        $doc = do_upload('pdf');
        add_mutation_data($app_id, 'pdf', $doc);
        $db->update('ai_mutations', ['step' => 6, 'token' => $token], ['id' => $app_id]);

        // Update payment order;
        $amount = 500; // Rs 5
        $api = new Api(RAZOR_KEY_ID, RAZOR_KEY_SECRET);
        $sb = [];
        $sb['created'] = date("Y-m-d H:i:s");
        $sb['amount'] = $amount;
        $sb['status'] = 0;
        $sb['receipt_id'] = $receipt_id = 'bs-' . str_pad($app_id, 8, '0', STR_PAD_LEFT);
        $sb['cust_name'] = $app->fname;
        $sb['cust_mobile'] = $app->mobile;
        $sb['cust_email'] = $app->email_id;
        $sb['notes'] = 'mutations';
        $sb['user_id'] = $user_id;
        $db->insert("ai_orders", $sb);
        $id = $db->id();

        $item = $api->order->create(['receipt' => $receipt_id, 'amount' => $amount * 100, 'currency' => 'INR', 'notes' => array('key1' => 'value3')]);
        if ($item->status == 'created') {
            $rzp_id = $item->id;
            $db->update('ai_orders', ['rzp_order_id' => $rzp_id], ['id' => $id]);
            $sb['rzp_order_id'] = $rzp_id;
            $db->update('ai_mutations', ['order_id' => $id], ['id' => $app_id]);
        }

        redirect('apply-confirm.php', "Application Details Saved successfully. Please pay to Proceed");
    }
}
include "common/header.php";
?>
<style>
    .form-input {
        padding: 3px 8px;
    }

    .form-small {
        width: 200px;
    }

    .table-input {
        width: 120px;
        padding: 2px 5px;
    }
</style>
<?php

?>
<div id="origin" class="container">
    <div class="py-3" id="alertdiv">
        <h1 class="h5 mb-4 text-center text-success">Apply for Mutations</h1>
        <!-- <div class="search-area bg-white mb-2 shadow shadow-sm p-2 rounded">
            <form action="" method="get">
                <div class="row">
                    <div class="col-sm-3">
                        <select @change="set_zones" name="dist" v-model="dist_id" class="form-select">
                            <option value="">Select District</option>
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
                        <select name="zone" v-model="zone_id" class="form-select">
                            <option value="">Select Circle</option>
                            <option v-for="item in zones" :value="item.id">{{ item.zone_name }}</option>
                        </select>
                    </div>
                    <div class="col-sm-2 d-grid">
                        <button name="search" value="Search" class="btn btn-primary btn-sm">Proceed</button>
                    </div>
                </div>
            </form>
        </div> -->
    </div>
    <?php
    include "common/alert.php";
    ?>
    <div v-if="errmsg.length>0" class="alert" :class="errcls">{{errmsg}}</div>
    <div class="alert alert-success p-2 application-alert-flow">
        <a href="apply-mutation.php?step=1" class="btn btn-xs <?= $step == 1 ? 'btn-success' : ''; ?>">Application Details</a>
        <span class="sep">|</span>
        <a href="apply-mutation.php?step=2" class="btn btn-xs <?= $step == 2 ? 'btn-success' : ''; ?>">Document Details</a>
        <span class="sep">|</span>
        <a href="apply-mutation.php?step=3" class="btn btn-xs <?= $step == 3 ? 'btn-success' : ''; ?>">Buyer Details</a>
        <span class="sep">|</span>
        <a href="apply-mutation.php?step=4" class="btn btn-xs <?= $step == 4 ? 'btn-success' : ''; ?>">Seller Details</a>
        <span class="sep">|</span>
        <a href="apply-mutation.php?step=5" class="btn btn-xs <?= $step == 5 ? 'btn-success' : ''; ?>">Plot Details</a>
        <span class="sep">|</span>
        <a href="apply-mutation.php?step=6" class="btn btn-xs <?= $step == 6 ? 'btn-success' : ''; ?>">Upload Details</a>
    </div>
    <div v-if="step==1" class="clearfix bg-white mb-3 p-4 shadow-sm rounded-2">
        <h5>Application Details</h5>
        <hr />
        <h6>All fields are mandatory</h6>
        <div class="row g-2 mb-2">
            <div class="col-ss-6">
                <label>Name</label>
                <input v-model="form.fname" class="form-control form-control-sm" />
            </div>
            <div class="col-ss-6">
                <label>Father/Husband/Represented Through</label>
                <input v-model="form.guardian" class="form-control form-control-sm" />
            </div>
        </div>
        <div class="row g-2 mb-2">
            <div class="col-ss-6">
                <label>Relation</label>
                <input v-model="form.relation" class="form-control form-control-sm" />
            </div>
            <div class="col-ss-6">
                <label>Case Year</label>
                <input v-model="form.case_year" class="form-control form-control-sm" />
            </div>
        </div>
        <h6>All fields are mandatory</h6>
        <hr />
        <div class="row">
            <div class="col-sm-6">
                <p><b>Present Address</b></p>
                <table class="table">
                    <tbody>
                        <tr>
                            <td>Village/Town</td>
                            <td><input v-model="form.village" class="form-control form-control-sm" /></td>
                        </tr>
                        <tr>
                            <td>Address</td>
                            <td><textarea v-model="form.address" class="form-control form-control-sm"></textarea></td>
                        </tr>
                        <tr>
                            <td>State</td>
                            <td>

                                <select v-model="form.state_id" @change="setDist" class="form-select form-control-sm">
                                    <option value="">Select</option>
                                    <?php
                                    foreach ($states as $st) {
                                    ?>
                                        <option value="<?= $st->id; ?>"><?= $st->state_name; ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>District</td>
                            <td><select v-model="form.dist_id" class="form-select form-control-sm">
                                    <option value="">Select</option>
                                    <option v-for="item in dists" :value="item.id">{{ item.dist_name }}</option>
                                </select></td>
                        </tr>
                        <tr>
                            <td>Pincode</td>
                            <td><input v-model="form.pincode" class="form-control form-control-sm" /></td>
                        </tr>
                        <tr>
                            <td>Email Id</td>
                            <td><input v-model="form.email_id" class="form-control form-control-sm" /></td>
                        </tr>
                        <tr>
                            <td>Mobile Number</td>
                            <td><input v-model="form.mobile" class="form-control form-control-sm" /></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-sm-6">
                <p class="d-flex justify-content-between">
                    <b>Permanent Address</b>
                    <label>
                        <input type="checkbox" v-model="same_check" name="same" /> Same as Present
                    </label>
                </p>
                <table class="table">
                    <tbody>
                        <tr>
                            <td>Village/Town</td>
                            <td><input class="form-control form-control-sm" /></td>
                        </tr>
                        <tr>
                            <td>Address</td>
                            <td><textarea class="form-control form-control-sm"></textarea></td>
                        </tr>
                        <tr>
                            <td>State</td>
                            <td><select class="form-select form-control-sm">
                                    <?php
                                    foreach ($states as $st) {
                                    ?>
                                        <option value="<?= $st->id; ?>"><?= $st->state_name; ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>District</td>
                            <td><select class="form-select form-control-sm"></select></td>
                        </tr>
                        <tr>
                            <td>Pincode</td>
                            <td><input class="form-control form-control-sm" /></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="text-center">
            <button :disabled="!gps_on || loading" @click="formInit" class="btn btn-primary">{{ loading ? 'Saving... Please wait !!' : 'SAVE AND CONTINUE' }}</button>
        </div>
    </div>
    <div v-if="step==2" class="clearfix bg-white mb-3 p-4 shadow-sm rounded-2">
        <h5>Document Uploads</h5>
        <hr />
        <div class="d-flex gap-2 align-items-center">
            <label>Documents required to be uploaded</label>
            <select v-model="form2.doc_type" class="form-select form-small">
                <option value="">Select</option>
                <option value="register">Register Deed</option>
                <option value="interim">Interim Deed</option>
            </select>
        </div>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Document Type</th>
                    <th>Document Number</th>
                    <th>Date</th>
                    <th>Amount</th>
                    <th>Court name/Issuing Authority</th>
                    <th>District</th>
                    <th>Registrar</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(f1,sl) in form2_items">
                    <td><input type="text" v-model="f1.doc_type" class="form-control table-input"> </td>
                    <td><input type="text" v-model="f1.doc_number" class="form-control table-input"> </td>
                    <td><input type="date" v-model="f1.doc_date" class="form-control table-input"> </td>
                    <td><input type="text" v-model="f1.amount" class="form-control table-input"> </td>
                    <td><input type="text" v-model="f1.authority" class="form-control table-input"> </td>
                    <td><input type="text" v-model="f1.dist_name" class="form-control table-input"> </td>
                    <td>
                        <button @click="remove_form2_item(sl)" class="btn btn-xs btn-danger">Remove</button>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>
                        <button @click="addForm2" class="btn btn-sm btn-primary">ADD NEW</button>
                    </td>
                </tr>
            </tbody>
        </table>
        <div class="text-center">
            <button :disabled="loading" @click="saveStep2" class="btn btn-primary">{{ loading ? 'Saving...' : 'SAVE AND CONTINUE'}}</button>
        </div>
    </div>
    <div v-if="step==3" class="clearfix bg-white mb-3 p-4 shadow-sm rounded-2">
        <h5>Buyer Details</h5>
        <hr />
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Guardian name</th>
                    <th>Relation </th>
                    <th>Caste</th>
                    <th>Gender</th>
                    <th>Mobile</th>
                    <th>Address</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(f1,sl) in form3_items">
                    <td><input type="text" v-model="f1.name" class="form-control table-input"> </td>
                    <td><input type="text" v-model="f1.guardian" class="form-control table-input"> </td>
                    <td><input type="text" v-model="f1.relation" class="form-control table-input"> </td>
                    <td><input type="text" v-model="f1.caste" class="form-control table-input"> </td>
                    <td><select v-model="f1.gender" class="form-select table-input">
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Others">Others</option>
                        </select>
                    </td>
                    <td><input type="text" v-model="f1.mobile" class="form-control table-input"> </td>
                    <td><input type="text" v-model="f1.address" class="form-control table-input"> </td>
                    <td>
                        <button @click="remove_form3_item(sl)" class="btn btn-xs btn-danger">Remove</button>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>
                        <button @click="addForm3" class="btn btn-sm btn-primary">ADD NEW</button>
                    </td>
                </tr>
            </tbody>
        </table>
        <div class="text-center">
            <button :disabled="loading" @click="saveStep3" class="btn btn-primary">{{ loading ? 'Saving...' : 'SAVE AND CONTINUE'}}</button>
        </div>
    </div>
    <div v-if="step==4" class="clearfix bg-white mb-3 p-4 shadow-sm rounded-2">
        <h5>Seller Details</h5>
        <hr />
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Guardian name</th>
                    <th>Relation </th>
                    <th>Caste</th>
                    <th>Gender</th>
                    <th>Mobile</th>
                    <th>Address</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(f1,sl) in form4_items">
                    <td><input type="text" v-model="f1.name" class="form-control table-input"> </td>
                    <td><input type="text" v-model="f1.guardian" class="form-control table-input"> </td>
                    <td><input type="text" v-model="f1.relation" class="form-control table-input"> </td>
                    <td><input type="text" v-model="f1.caste" class="form-control table-input"> </td>
                    <td><select v-model="f1.gender" class="form-select table-input">
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Others">Others</option>
                        </select>
                    </td>
                    <td><input type="text" v-model="f1.mobile" class="form-control table-input"> </td>
                    <td><input type="text" v-model="f1.address" class="form-control table-input"> </td>
                    <td>
                        <button @click="remove_form4_item(sl)" class="btn btn-xs btn-danger">Remove</button>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>
                        <button @click="addForm4" class="btn btn-sm btn-primary">ADD NEW</button>
                    </td>
                </tr>
            </tbody>
        </table>
        <div class="text-center">
            <button :disabled="loading" @click="saveStep4" class="btn btn-primary">{{ loading ? 'Saving...' : 'SAVE AND CONTINUE'}}</button>
        </div>
    </div>
    <div v-if="step==5" class="clearfix bg-white mb-3 p-4 shadow-sm rounded-2">
        <h5>All fields are mandatory</h5>
        <hr />
        <div class="row mb-3">
            <div class="col-sm-2">District</div>
            <div class="col-sm-2">
                <select v-model="form5.dist_id" class="form-select">
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
            <div class="col-sm-2">Sub Division</div>
            <div class="col-sm-2">
                <input type="text" v-model="form5.sub_division" class="form-control">
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-sm-2">Circle</div>
            <div class="col-sm-2">
                <input type="text" v-model="form5.circle" class="form-control">
            </div>
            <div class="col-sm-2">Halka</div>
            <div class="col-sm-2">
                <input type="text" v-model="form5.halka" class="form-control">
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-sm-2">Mauja </div>
            <div class="col-sm-2">
                <input type="text" v-model="form5.mauja" class="form-control">
            </div>
            <div class="col-sm-2">Thana name</div>
            <div class="col-sm-2">
                <input type="text" v-model="form5.thana" class="form-control">
            </div>
        </div>
        <h6>Specific Information for Mutation</h6>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Khata no</th>
                    <th>Plot no</th>
                    <th>Transcate Area1 (Acre) </th>
                    <th>Transcate Area2 (Dismil) </th>
                    <th>Chauhhadi North</th>
                    <th>Chauhhadi South</th>
                    <th>Chauhhadi East</th>
                    <th>Chauhhadi West</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>
                        <button class="btn btn-sm btn-primary">ADD NEW</button>
                    </td>
                </tr>
            </tbody>
        </table>
        <div class="text-center">
            <button :disabled="loading" @click="saveStep5" class="btn btn-primary">{{ loading ? 'Saving...' : 'SAVE AND CONTINUE'}}</button>
        </div>
    </div>
    <div v-if="step==6" class="clearfix bg-white mb-3 p-4 shadow-sm rounded-2">
        <h5>Upload Document Details</h5>
        <hr />
        <form action="" method="post" enctype="multipart/form-data">
            <input type="hidden" name="clicked" value="1">
            <div class="row mb-3">
                <div class="col-sm-5">
                    <input type="file" required accept="application/pdf" name="pdf" class="form-control">
                </div>
            </div>
            <div class="mb-3">
                <label>
                    <input type="checkbox" v-model="iagree" /> Any information provide by me here are true and I will be sole responsible for any erroneous or fake information.
                </label>
            </div>
            <div class="text-center">
                <button :disabled="!iagree" name="submit" value="Save" class="btn btn-primary btn-submit">SAVE AND FINISH</button>
            </div>
        </form>
    </div>
</div>
<?php
include "common/footer.php";
?>
<script>
    let vm = new Vue({
        el: '#origin',
        data: {
            iagree: false,
            nextPageUrl: 'apply-mutation.php?step=',
            zones: [],
            dists: [],
            dist_id: '',
            zone_id: '',
            dist_id1: '',
            zone_id1: '',
            btncls: '',
            errmsg: '',
            errcls: '',
            search_by: 'case_no',
            step: '<?= $step ?>',
            lat: '',
            lng: '',
            form: {
                fname: '',
                village: '',
                address: '',
                state_id: '',
                dist_id: '',
                pincode: '',
                guardian: '',
                relation: '',
                case_year: '',
                email_id: '',
                mobile: '',
                mutation_type: ''
            },
            form2: {
                doc_type: ''
            },
            form2_items: [{
                doc_type: '',
                doc_number: '',
                doc_date: '',
                amount: '',
                authority: '',
                dist_name: ''
            }],
            form3_items: [{
                name: '',
                guardian: '',
                relation: '',
                caste: '',
                gender: '',
                mobile: '',
                address: ''
            }],
            form4_items: [{
                name: '',
                guardian: '',
                relation: '',
                caste: '',
                gender: '',
                mobile: '',
                address: ''
            }],
            form5: {
                dist_id: '',
                sub_division: '',
                circle: '',
                mauja: '',
                halka: '',
                thana: ''
            },
            same_check: false,
            loading: false,
            gps_on: false,
            app_id: '<?= session()->app_id; ?>'
        },
        methods: {
            setDist: function() {
                this.dists = [];
                api_call('districts', {
                    state_id: this.form.state_id
                }).then(resp => {
                    if (resp.success) this.dists = resp.data;
                })
            },
            set_zones: function() {
                this.zones = [];
                api_call('zones', {
                    dist_id: this.dist_id
                }).then(resp => {
                    if (resp.success) this.zones = resp.data;
                })
            },
            set_zones1: function() {
                this.zones = [];
                api_call('zones', {
                    dist_id1: this.dist_id1
                }).then(resp => {
                    if (resp.success) this.zones = resp.data;
                })
            },
            formInit: function() {

                this.loading = true;
                let form = this.form;
                form.lat = this.lat;
                form.lng = this.lng;
                api_call('add-mutations', form).then(resp => {
                    this.errmsg = resp.message;
                    if (resp.success) {
                        this.errcls = 'alert-success'
                        let order = resp.data;
                        window.location = this.nextPageUrl + order.step
                    } else {
                        this.errcls = 'alert-danger';
                    }
                    this.loading = false
                    document.getElementById('alertdiv').scrollIntoView();
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
            },
            addForm2: function() {
                this.form2_items.push({
                    doc_type: '',
                    doc_number: '',
                    doc_date: '',
                    amount: '',
                    authority: '',
                    dist_name: ''
                })
            },
            remove_form2_item: function(sl) {
                this.form2_items.splice(sl, 1)
            },
            saveStep2: function() {
                this.loading = true;
                let form = this.form2;
                form.inputs = JSON.stringify(this.form2_items);
                form.app_id = this.app_id;
                api_call('update-mutations-step2', form).then(resp => {
                    console.log(resp)
                    this.errmsg = resp.message;
                    if (resp.success) {
                        this.errcls = 'alert-success'
                        let order = resp.data;
                        window.location = this.nextPageUrl + order.step;
                    } else {
                        this.errcls = 'alert-danger';
                    }
                    this.loading = false
                    document.getElementById('alertdiv').scrollIntoView();
                })
            },
            addForm3: function() {
                this.form3_items.push({
                    name: '',
                    guardian: '',
                    relation: '',
                    caste: '',
                    gender: '',
                    mobile: '',
                    address: ''
                })
            },
            remove_form3_item: function(sl) {
                this.form3_items.splice(sl, 1)
            },
            saveStep3: function() {
                this.loading = true;
                let form = {};
                form.inputs = JSON.stringify(this.form3_items);
                form.app_id = this.app_id;
                api_call('update-mutations-step3', form).then(resp => {
                    this.errmsg = resp.message;
                    if (resp.success) {
                        this.errcls = 'alert-success'
                        let order = resp.data;
                        window.location = this.nextPageUrl + order.step
                    } else {
                        this.errcls = 'alert-danger';
                    }
                    this.loading = false
                    document.getElementById('alertdiv').scrollIntoView();
                })
            },
            addForm4: function() {
                this.form4_items.push({
                    name: '',
                    guardian: '',
                    relation: '',
                    caste: '',
                    gender: '',
                    mobile: '',
                    address: ''
                })
            },
            remove_form4_item: function(sl) {
                this.form4_items.splice(sl, 1)
            },
            saveStep4: function() {
                this.loading = true;
                let form = {};
                form.inputs = JSON.stringify(this.form4_items);
                form.app_id = this.app_id;
                api_call('update-mutations-step4', form).then(resp => {
                    if (resp.success) {
                        this.errcls = 'alert-success'
                        let order = resp.data;
                        setTimeout(() => {
                            window.location = this.nextPageUrl + order.step
                        }, 1000)
                    } else {
                        this.errcls = 'alert-danger';
                    }
                    this.loading = false
                    document.getElementById('alertdiv').scrollIntoView();
                })
            },
            saveStep5: function() {
                this.loading = true;
                let form = this.form5;
                form.app_id = this.app_id;
                api_call('update-mutations-step5', form).then(resp => {
                    console.log(resp)
                    if (resp.success) {
                        this.errcls = 'alert-success'
                        let order = resp.data;
                        window.location = this.nextPageUrl + order.step
                    } else {
                        this.errcls = 'alert-danger';
                    }
                    this.loading = false
                    document.getElementById('alertdiv').scrollIntoView();
                })
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