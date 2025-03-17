<?php
include "config/autoload.php";
if (!is_login()) redirect(base_url('login.php'), 'Please Login to continue', 'danger');
include "common/header.php";
?>
<div class="login-wrapper py-5">
    <div id="origin" class="container">
        <div class="row">
            <div class="col-sm-10 m-auto">
                <ul class="progressbar">
                    <li>Mobile Verification</li>
                    <li>PAN Verification</li>
                    <li class="active">Aadhar Varification</li>
                    <li>Photo Upload</li>
                </ul>
                <div class="bg-white rounded shadow-sm overflow-hidden mb-3 p-3">
                    <div class="row">
                        <h6 class="text-center"><b>Aadhar Varification</b></h6>
                        <div class="col-sm-6">
                            <hr />
                            <div v-if="errmsg.length>0" class="alert" :class="errcls">{{ errmsg }}</div>
                            <div v-if="step==3">
                                <div class="mb-2">
                                    <label>Aadhar Number</label>
                                    <input type="text" :value="aadhar_info.maskedAadhaarNumber" disabled class="form-control" />
                                </div>
                                <div class="mb-2">
                                    <label>Your name (As per Aadhar Card)</label>
                                    <input type="text" :value="aadhar_info.name" disabled class="form-control" />
                                </div>
                                <div class="mb-2">
                                    <label>Father/Husband name</label>
                                    <input type="text" :value="aadhar_info.fatherName" disabled class="form-control" />
                                </div>
                                <div class="mb-2">
                                    <label>Address</label>
                                    <input type="text" :value="aadhar_info.combinedAddress" disabled class="form-control" />
                                </div>
                                <div class="row mb-2">
                                    <div class="col-sm-6">
                                        <label>Date of Birth</label>
                                        <input type="text" :value="aadhar_info.dob" disabled class="form-control" />
                                    </div>
                                    <div class="col-sm-6">
                                        <label>Gender</label>
                                        <input type="text" :value="aadhar_info.gender" disabled class="form-control" />
                                    </div>
                                </div>
                                <button @click="submit_aadhar" :disabled="loading3" class="btn btn-primary">{{ loading3 ? 'Loading...' : 'Submit'}}</button>
                                <button @click="step=1" class="btn btn-dark">Change</button>
                            </div>
                            <div v-if="step==1||step==2">
                                <div class="mb-2">
                                    <label>Enter name (As per Aadhar Card)</label>
                                    <input type="text" v-model="aadhar_name" class="form-control" />
                                </div>
                                <div class="mb-2">
                                    <label>Enter Your Aadhar No.</label>
                                    <input type="text" v-model="aadhar_no" class="form-control" maxlength="12" />
                                </div>
                                <div class="mb-2">
                                    <div class="small text-muted">(OPT will be send on your linked mobile)</div>
                                </div>
                                <div v-if="step==2" class="mb-2" style="width:100px;">
                                    <input type="text" v-model="otp" class="form-control" placeholder="OTP" />
                                </div>
                                <div class="butt">
                                    <button :disabled="step==2 || loading1" @click="get_otp" class="btn btn-success">{{ loading1 ? 'Loading...' : 'Get OTP'}}</button>
                                    <button @click="submit_otp" class="btn btn-primary" :disabled="step==1 || loading2 ">{{ loading2 ? 'Loading...' : 'Submit'}}</button>
                                </div>
                            </div>
                        </div>
                        <div class="border-start col-sm-6 mb-2">
                            <hr>
                            <div class="ridge" align="center">
                                <img src="assets/front/img/adhar-card.png" class="img-fluid">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded p-3">
                    <div>If You are unable to verify Aadhar Number using mobile OTP? <a href="aadhar-upload.php">Click here</a></div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
include "common/footer.php";
?>
<script>
    new Vue({
        el: '#origin',
        data: {
            aadhar_name: '',
            aadhar_no: '',
            step: 1,
            errmsg: '',
            errcls: '',
            loading1: false,
            loading2: false,
            loading3: false,
            otp: '',
            user_id: '<?= user_id(); ?>',
            access_key: '',
            aadhar_info: {
                fatherName: '',
                gender: '',
                dob: '',
                name: '',
                maskedAadhaarNumber: '',
                combinedAddress: ''
            }
        },
        methods: {
            get_otp: function() {
                this.errmsg = '';
                if (this.name == '') {
                    this.errmsg = "Enter your aadhar name";
                    this.errcls = 'alert-danger';
                    return;
                } else if (this.aadhar_no == '' || this.aadhar_no.length != 12) {
                    this.errmsg = "Enter 12 digit aadhar number";
                    this.errcls = 'alert-danger';
                    return;
                }
                this.loading1 = true;
                api_call('aadhar-otp', {
                    user_id: this.user_id,
                    aadhar_no: this.aadhar_no,
                    aadhar_name: this.aadhar_name
                }).then(result => {
                    this.errmsg = result.message;
                    if (result.success) {
                        this.errcls = 'alert-success'
                        this.step = 2;
                        this.access_key = result.data;
                    } else {
                        this.errcls = 'alert-danger'
                    }
                    this.loading1 = false
                })
            },
            submit_otp: function() {
                this.loading2 = true;
                if (this.otp == '') {
                    this.errcls = 'alert-danger'
                    this.errmsg = "Enter OTP Code"
                    return;
                }
                api_call('verify-aadhar-otp', {
                    aadhar_no: this.aadhar_no,
                    otp: this.otp,
                    access_key: this.access_key
                }).then(result => {
                    this.errmsg = result.message;
                    if (result.success) {
                        this.errcls = 'alert-success';
                        this.step = 3;
                        this.aadhar_info = result.data;
                    } else {
                        this.errcls = 'alert-danger';
                    }
                    this.loading2 = false;
                })
            },
            submit_aadhar: function() {
                this.loading3 = true
                const {
                    name,
                    fatherName,
                    gender,
                    dob,
                    combinedAddress,
                    maskedAadhaarNumber
                } = this.aadhar_info;
                api_call('upadate-aadhar-info', {
                    user_id: this.user_id,
                    aadhar_name: name,
                    father_name: fatherName,
                    gender: gender,
                    dob: dob,
                    address: combinedAddress,
                    maskedAadhaarNumber: maskedAadhaarNumber
                }).then(result => {
                    this.errmsg = result.message;
                    if (result.success) {
                        this.errcls = 'alert-success';
                        setTimeout(() => {
                            window.location = '<?= base_url('dashboard/index.php') ?>'
                        }, 1000)
                    } else {
                        this.errcls = 'alert-danger';
                    }
                    this.loading3 = false
                })
            }
        }
    })
</script>