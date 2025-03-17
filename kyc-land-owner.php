<?php
include "config/autoload.php";
if (!isset($_SESSION['user'])) {
    $_SESSION['error_msg'] = 'Please login to continue';
    header('location: login.php');
}
$user = $_SESSION['user'];
$user_id = $user->id;
include_once "common/header.php";

?>
<div id="origin" class="container">
    <main class="row py-5">
        <div class="col-sm-8 m-auto">
            <div class="bg-white rounded shadow-sm overflow-hidden mb-3">
                <div class="p-3">
                    <h5>KYC Complete - Land Owner Signup</h5>
                    <hr />
                    <div v-if="errmsg.length>0" class="alert" :class="errcls">{{ errmsg }}</div>
                    <!-- Form Start -->
                    <form action="kyc_user_action.php" method="post">
                        <div class="mb-2">
                            <label>Name(As per Aadhar Card)</label>
                            <input type="text" v-model="user.aadhar_name" class="form-control" name="aadhar_name">
                        </div>
                        <div class="mb-2">
                            <label>Father/Wife/Husband Name</label>
                            <input type="text" class="form-control" name="father_name">
                        </div>
                        <div class="mb-2">
                            <label>Address</label>
                            <input type="text" class="form-control" name="aadress">
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-4">
                                <label>CITY</label>
                                <input type="text" class="form-control" name="city" required />
                            </div>
                            <div class="col-sm-4">
                                <label>PINCODE</label>
                                <input type="text" class="form-control" name="pincode" required />
                            </div>
                            <div class="col-sm-4">
                                <label>Date of Birth</label>
                                <input type="date" class="form-control" name="dob">
                            </div>
                        </div>

                        <h6>Verification Details</h6>
                        <hr />
                        <div class="mb-3">
                            <label>Aadhar Verify</label>
                            <div class="d-flex gap-3 w-50 mb-3">
                                <input type="text" v-model="user.aadhar_number" class="form-control" name="aadhar_number">
                                <div v-if="aadhar_step==1">
                                    <button type="button" @click="submit_step_1" v-if="user.aadhar_verified==0" class="btn btn-warning">Verify</button>
                                    <button disabled v-if="user.aadhar_verified==1" class="btn btn-success">Verified</button>
                                </div>
                                <div class="d-flex gap-1" v-if="aadhar_step==2">
                                    <input type="text" placeholder="OTP" class="form-control">
                                    <button @click="submit_otp" class="btn btn-info">Verify</button>
                                </div>
                            </div>
                            <label>PAN Verify</label>
                            <div class="d-flex gap-3 w-50">
                                <input type="text" v-model="user.pan_number" v-model="pan_number" class="form-control">
                                <button type="button" v-if="user.pan_verified==0" @click="verifyPan" class="btn btn-warning">{{ pan_verifying ? 'Verifying...' : 'Verify'}}</button>
                                <button disabled v-if="user.pan_verified==1" class="btn btn-success">Verified</button>
                            </div>
                        </div>

                        <div class="mb-3">
                            <b>Disclaimer: </b>A views expressed disclaimer asserts that the opinions expressed in an article or any written material are those of the author and not the opinion of the website. Publishers usually use this to protect themselves from liability. Also, persons belonging to an organization use this disclaimer to clarify that anything they say is their individual opinion, not their organization’s official stance.
                        </div>
                        <button type="submit" class="btn btn-primary" value="submit" name="btn_submit">Submit</button>
                        <a href="login.php" class="btn btn-dark">Cancel</a>
                    </form>
                    <!-- Form End -->
                </div>
            </div>
        </div>
    </main>
</div>
<?php
include_once "common/footer.php";
?>
<script>
    var apiurl = 'api.php';
    let vm = new Vue({
        el: '#origin',
        data: {
            user_id: '<?= user_id(); ?>',
            user: {
                pan_verified: false,
                aadhar_verified: false,
                aadhar_name: '',
                aadhar_number: '',
                pan_number: ''
            },
            pan_verifying: false,
            aadhar_verifying: false,
            aadhar_step: 1,
            errmsg: '',
            errcls: ''
        },
        methods: {
            load_user: function() {
                api_call('userinfo', {
                    user_id: this.user_id
                }).then(result => {
                    if (result.success) {
                        this.user = result.data;
                    }
                })
            },
            verifyPan: function() {
                if (this.user.pan_number == '') {
                    this.errmsg = "Enter PAN Number"
                    this.errcls = 'alert-danger'
                    return;
                }
                this.pan_verifying = true;
                api_call('pan-verify', {
                    pan: this.user.pan_number,
                    user_id: this.user_id
                }).then(result => {
                    if (result.success) {
                        this.load_user();
                    } else {
                        alert(result.message);
                    }
                    this.pan_verifying = false
                })
            },
            submit_step_1: function() {
                if (this.user.aadhar_name == '') {
                    this.errmsg = 'Enter Name';
                    this.errcls = 'alert-danger';
                    return;
                } else if (this.user.aadhar_number == '') {
                    this.errcls = 'alert-danger';
                    this.errmsg = "Enter 12 digit aadhar number";
                    return;
                }
                this.errcls = 'alert-info'
                this.errmsg = "Checking...";
                this.aadhar_step = 2;
            },
            submit_otp: function() {
                this.aadhar_step = 1;
                this.user.aadhar_verified = 1;
            }
        },
        created: function() {
            this.load_user()
        }
    })
</script>