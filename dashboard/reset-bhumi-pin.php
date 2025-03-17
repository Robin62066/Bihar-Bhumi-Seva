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
<div id="originotp" class="dashboard">
    <div class="container py-5">
        <div class="user-panel">
            <div class="row">
                <?php
                $subpage = 'bhumi';
                include_once "dashboard-menu.php"; ?>
                <div class="col-sm-9">
                    <?= front_view('common/alert'); ?>
                    <div class="bg-white p-4">
                        <div class="row">
                            <div class="col-sm-6 m-auto">
                                <div class="bg-white">
                                    <h5>Reset your PIN</h5>
                                    <hr />
                                    <div class="card-body">
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
                                    <button :disabled="!otpVerified" type="button" @click="CreateAccount" class="btn btn-primary">Submit</button>
                                </div>
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
                let url = appApiUrl + 'forget-bhumi-pin';
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

                let url = appApiUrl + 'update-pin';
                axios.post(url, {
                    user_id: '<?= user_id(); ?>',
                    pin: pin
                }).then(result => {
                    let resp = result.data;
                    this.errmsg = resp.message;
                    if (resp.success) {
                        this.errcls = 'alert-success';
                        setTimeout(() => {
                            window.location.href = 'bhumi-locker.php';
                        }, 1000)
                    } else {
                        this.errcls = 'alert-danger';
                    }
                })
            }
        },
        created: function() {
            this.GetOtp();
        }
    })
</script>