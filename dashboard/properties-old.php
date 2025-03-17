<?php
include "../config/autoload.php";
if (!is_login()) redirect(base_url('login.php'), 'Please login to continue');

$user_id = user_id();
$sites = $db->select('ai_sites', ['user_id' => $user_id])->result();
include "../common/header.php";
?>
<script>
    var apiUrl = '<?= base_url('api.php') ?>'
    $(document).ready(function() {
        $('.select2').select2();
    });
</script>
<div class="dashboard">
    <div class="container py-5">
        <div class="user-panel">
            <div class="row">
                <?php
                $subpage = 'properties';
                include_once "dashboard-menu.php"; ?>
                <div class="col-sm-9">
                    <?= front_view('common/alert'); ?>
                    <div class="bg-white p-3 rounded-1">
                        <div class="page-header">
                            <h5>Properties</h5>
                            <!-- <a href="<?= base_url('sell-property.php') ?>" class="btn btn-sm btn-primary">Sell Property</a> -->
                            <a href="#" class="btn btn-primary btn-" data-bs-toggle="modal" data-bs-target="#SellForm">Sell Property</a>
                        </div>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Sl</th>
                                        <th>#</th>
                                        <th>Title</th>
                                        <th>Location</th>
                                        <th>Khata no</th>
                                        <th>Total Area</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sl = 1;
                                    foreach ($sites as $item) {
                                        $editLink = base_url('property-details.php?id=' . $item->id);
                                        if ($item->status == 1) {
                                            $editLink = base_url('property-view.php?id=' . $item->id);
                                        }
                                    ?>
                                        <tr>
                                            <td><?= $sl++; ?></td>
                                            <td>
                                                <a href="<?= $editLink; ?>"><?= $item->id; ?></a>
                                            </td>
                                            <td><?= $item->site_title; ?></td>
                                            <td><?= $item->address; ?></td>
                                            <td><?= $item->khata_no; ?></td>
                                            <td><?= $item->total_area; ?> <?= $item->area_unit; ?></td>
                                            <td><?= $item->total_amount; ?></td>
                                            <td>
                                                <?php
                                                if ($item->status == 0) echo '<span class="badge bg-warning">Pending</span>';
                                                if ($item->status == 1) echo '<span class="badge bg-success">Active</span>';
                                                if ($item->status == 2) echo '<span class="badge bg-danger">Rejected</span>';
                                                ?>
                                            </td>
                                        </tr>
                                    <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>

        </div>
        <div class="modal fade" id="SellForm" data-bs-keyboard="true" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header d-flex justify-content-center">
                        <h1 class="modal-title fs-5 fw-bold" id="staticBackdropLabel">अपना जिला और अंचल चुनें</h1>
                    </div>
                    <div class="modal-body">
                        <div v-if="errmsg.length>0" class="alert" :class="errcls">{{ errmsg }}</div>
                        <div class="row mb-2 align-items-center">
                            <div class="col-sm-4 text-end">
                                <label class="text-end">अपना जिला चुने </label>
                            </div>
                            <div class="col-sm-6">
                                <select @change="set_zones" v-model="dist_id" class="form-select">
                                    <option value="">Select</option>
                                    <?php
                                    $dists = $db->select('ai_districts', [], false, 'dist_name ASC')->result();
                                    foreach ($dists as $item1) {
                                    ?>
                                        <option value="<?= $item1->id; ?>"><?= $item1->dist_name; ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-4 align-items-center">
                            <div class="col-sm-4 text-end">
                                <label class="text-end">अपना अंचल चुने </label>
                            </div>
                            <div class="col-sm-6">
                                <select v-model="zone_id" class="form-select">
                                    <option value="">Select</option>
                                    <option v-for="item in zones" :value="item.id">{{ item.zone_name }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3 text-center">
                            <p>यदि आप किसी दलाल या ब्रोकर से संपर्क करना चाहते है तो <a href="brokers.php">यहां क्लिक करें</a></p>
                            <button @click="save_proceed" class="btn btn-primary" :class="btncls"><span></span> Next</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    new Vue({
        el: '#SellForm',
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
include "../common/footer.php";
