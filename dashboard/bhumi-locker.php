<?php
require('../config/autoload.php');
if (!is_login()) redirect(base_url('login.php'), 'You must login to continue');

$user_id = user_id();
$isLogin = session()->bhumilogin !== null;

$files = $db->select("ai_bhumifiles", ['user_id' => user_id(), 'is_deleted' => 0])->result();
$user  = $db->select("ai_users", ['id' => $user_id], 1)->row();
$isKYCVerified = $user->kyc_status == 1 ? true : false;
include "../common/header.php";
?>
<style>
    .otp-input {
        width: 50px;
        height: 50px;
        font-size: 24px;
        text-align: center;
        border: 2px solid #ccc;
        border-radius: 5px;
        outline: none;
        transition: border-color 0.3s;
    }

    .otp-input:focus {
        border-color: #007BFF;
    }
</style>

<div id="originotp" class="dashboard">
    <div class="container py-5">
        <div class="user-panel">
            <div class="row">
                <?php
                $subpage = 'bhumi';
                include_once "dashboard-menu.php"; ?>
                <div class="col-sm-9">
                    <?= front_view('common/alert'); ?>
                    <div id="origin">
                        <?php
                        if (!$isLogin) {
                            $sql = "SELECT * FROM ai_bhumi_login WHERE user_id = '$user_id' AND status = 1 LIMIT 1";
                            $profile = $db->query($sql)->row();
                            if ($profile == null) {
                                if ($isKYCVerified == false) {
                        ?>
                                    <div class="alert alert-danger">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span>You must complate your KYC to Create Bhumi Locker Account</span>
                                            <a href="<?= site_url('pan-verification.php?redirect=dashboard/bhumi-locker.php'); ?>" class="btn btn-sm btn-primary">Complete KYC</a>
                                        </div>
                                    </div>
                                <?php
                                }
                                ?>
                                <div class="card p-5 d-flex align-items-center flex-column">
                                    <h5 class="text-primary my-4">Your Bhumi Locker Account does not Exists. </h5>
                                    <?php
                                    if ($isKYCVerified == false) {
                                    ?>
                                        <button disabled class="btn btn-primary mb-3">Create Now</button>
                                        <small class="text-danger">KYC Not Verified</small>
                                    <?php
                                    } else {
                                    ?>
                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createPIN">
                                            Create Now
                                        </button>

                                    <?php
                                    }
                                    ?>
                                </div>
                            <?php
                            }
                            $chkBhumiProfile = is_object($profile);
                            if ($chkBhumiProfile) {
                            ?>
                                <div class="card p-4">
                                    <div class="row">
                                        <div class="col-sm-5 mx-auto my-5">
                                            <div v-if="errmsg.length>0" class="alert p-2" :class="errcls">{{ errmsg }}</div>
                                            <div class="mb-3">
                                                <h6 class="text-center mb-4">Enter PIN to Login</h6>
                                                <div class="d-flex gap-2 justify-content-center mb-4">
                                                    <input type="password" maxlength="1" v-model="pa1" class="form-control text-center" oninput="moveToNext(this, 'otp21')" id="otp11">
                                                    <input type="password" maxlength="1" v-model="pb1" class="form-control text-center" oninput="moveToNext(this, 'otp31')" id="otp21">
                                                    <input type="password" maxlength="1" v-model="pc1" class="form-control text-center" oninput="moveToNext(this, 'otp41')" id="otp31">
                                                    <input type="password" maxlength="1" v-model="pd1" class="form-control text-center" oninput="moveToNext(this, '')" id="otp41">
                                                </div>
                                                <div class="text-center mb-3">
                                                    <button :disabled="loading" @click="LoginAccount" class="btn btn-primary">{{ loading ? 'Please wait...' : 'LOGIN'}}</button>
                                                </div>
                                                <div class="text-center">
                                                    Forget PIN ? <a href="<?= site_url('dashboard/reset-bhumi-pin.php') ?>">Click here</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php
                            }
                            ?>
                        <?php
                        } else {
                        ?>
                            <div class="bg-white p-3 mb-3 ">
                                KYC सत्यापन और मालिकाना हक मे समानता जरुरी है। गलत जानकारी देने पर आवेदन निरस्त हो सकता है।
                            </div>
                            <div class="bg-white p-4 rounded-1">
                                <div class="page-header">
                                    <h5>Properties</h5>
                                    <a href="<?= base_url('dashboard/add-document.php') ?>" class="btn btn-sm btn-primary"><i class="bi-plus"></i> Add Property</a>
                                </div>
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>रैयत का नाम</th>
                                                <th> अभिभावक का नाम </th>
                                                <th>खाता सख्या</th>
                                                <th>खेसरा सख्या</th>
                                                <th>जमाबंदी सख्या</th>
                                                <th>रकबा / डिसमिल </th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $sl = 1;
                                            foreach ($files as $item) {
                                            ?>
                                                <tr>
                                                    <td><?= $item->id; ?></td>
                                                    <td><?= $item->raiyat_name; ?></td>
                                                    <td><?= $item->guardian_name; ?></td>
                                                    <td><?= $item->khata_no; ?></td>
                                                    <td><?= $item->khesra_no; ?></td>
                                                    <td><?= $item->jamabandi; ?></td>
                                                    <td><?= $item->rakba; ?></td>
                                                    <td>
                                                        <a href="<?= site_url('dashboard/view-files.php?id=' . $item->id); ?>" class="btn btn-xs btn-primary">View Details</a>
                                                    </td>
                                                </tr>
                                            <?php
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="createPIN" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Create PIN</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div v-if="errmsg.length>0" class="alert p-2" :class="errcls">{{ errmsg }}</div>
                    <div class="mb-3">
                        <label>Verify Mobile OTP</label>
                        <div class="d-flex gap-3">
                            <input type="text" class="form-control" value="<?= $user->mobile_number; ?>" disabled />
                            <button @click="GetOtp" class="btn btn-primary">GET&nbsp;OTP</button>
                        </div>
                    </div>
                    <div v-if="otpSent" class="mb-3">
                        <label>Enter OTP Code</label>
                        <div class="d-flex gap-3">
                            <input type="text" v-model="userotp" class="form-control" maxlength="4" />
                            <button @click="VerifyOTP" class="btn btn-primary">VERIFY</button>
                        </div>
                    </div>
                    <div v-if="otpVerified" class="mb-3">
                        <label>Create PIN</label>
                        <div class="d-flex gap-2 justify-content-center">
                            <input type="password" maxlength="1" v-model="pa" class="form-control text-center" oninput="moveToNext(this, 'otp2')" id="otp1">
                            <input type="password" maxlength="1" v-model="pb" class="form-control text-center" oninput="moveToNext(this, 'otp3')" id="otp2">
                            <input type="password" maxlength="1" v-model="pc" class="form-control text-center" oninput="moveToNext(this, 'otp4')" id="otp3">
                            <input type="password" maxlength="1" v-model="pd" class="form-control text-center" oninput="moveToNext(this, '')" id="otp4">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button :disabled="!otpVerified" type="button" @click="CreateAccount" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
include "../common/footer.php";
?>

<script>
    function moveToNext(currentInput, nextInputId) {
        if (currentInput.value.length === 1 && nextInputId) {
            document.getElementById(nextInputId).focus();
        }
    }

    var appApiUrl = 'https://services.biharbhumiseva.in/api/call/';

    new Vue({
        el: '#originotp',
        data: {
            pa: '',
            pb: '',
            pc: '',
            pd: '',
            pa1: '',
            pb1: '',
            pc1: '',
            pd1: '',
            otpSent: false,
            otpVerified: false,
            userotp: '',
            errmsg: '',
            errcls: '',
            msgotp: '',
            loading: false
        },
        methods: {
            GetOtp: function() {
                let url = appApiUrl + 'create-bhumi-pin';
                axios.post(url, {
                    user_id: '<?= user_id(); ?>',
                    mobile_no: '<?= $user->mobile_number; ?>'
                }).then(result => {
                    let resp = result.data;
                    this.errmsg = resp.message;
                    if (resp.success) {
                        this.errcls = 'alert-success';
                        this.msgotp = resp.data.user_pin;
                        this.otpSent = true;
                    } else {
                        this.errcls = 'alert-danger';
                        this.otpSent = false;
                    }

                })
            },
            VerifyOTP: function() {
                if (this.userotp == this.msgotp) {
                    this.otpVerified = true;
                    this.errmsg = 'OTP Verified Successfully';
                    this.errcls = 'alert-success';
                } else {
                    this.errcls = 'alert-danger';
                    this.errmsg = "OTP is invalid.";
                    this.otpVerified = false;
                }
            },
            CreateAccount: function() {
                let pin = this.pa + '' + this.pb + '' + this.pc + '' + this.pd;
                if (pin.length != 4) {
                    this.errcls = 'alert-danger';
                    this.errmsg = 'Enter 4 digit PIN';
                    return;
                }
                this.errmsg = 'Creating account...';
                this.errcls = 'alert-info';

                let url = appApiUrl + 'create-bhumi-login';
                axios.post(url, {
                    user_id: '<?= user_id(); ?>',
                    pin: pin
                }).then(result => {
                    let resp = result.data;
                    this.errmsg = resp.message;
                    if (resp.success) {
                        this.errcls = 'alert-success';
                        setTimeout(() => {
                            window.location.reload();
                        }, 1000)
                    } else {
                        this.errcls = 'alert-danger';
                    }
                })
            },
            LoginAccount: function() {
                let url = apiUrl + 'bhumi-login';
                let pin = this.pa1 + '' + this.pb1 + '' + this.pc1 + '' + this.pd1;
                if (pin.length != 4) {
                    return;
                }
                this.loading = true;
                api_call('bhumi-login', {
                    user_id: '<?= user_id(); ?>',
                    pin: pin
                }).then(result => {
                    this.errmsg = result.message;
                    if (result.success) {
                        this.errcls = 'alert-success';
                        window.location.reload();
                    } else {
                        this.errcls = 'alert-danger';
                        this.pa1 = this.pb1 = this.pc1 = this.pd1 = '';
                    }
                    this.loading = false;
                })
            }
        }
    })
</script>