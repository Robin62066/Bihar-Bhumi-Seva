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
                    <li class="active">PAN Verification</li>
                    <li>Aadhar Verification</li>
                    <li>Photo Upload</li>
                </ul>
                <div class="bg-white rounded shadow-sm overflow-hidden mb-3 p-3">
                    <div class="row">
                        <h6 class="text-center"><b>PAN Verification</b></h6>
                        <div class="col-sm-6">
                            <hr />
                            <div v-if="errmsg.length>0" class="alert" :class="errcls">{{ errmsg }}</div>
                            <div class="mb-3">
                                <label>Account Type</label>
                                <select v-model="user_type" class="form-select">
                                    <option value="">Select Account Type</option>
                                    <option value="1">Customer</option>
                                    <option value="2">Land Owner</option>
                                    <option value="3">Broker/Builder</option>
                                    <option value="4">Munsi</option>
                                    <option value="5">Amin</option>
                                    <option value="8">Bhumi Locker</option>
                                    <option value="9">Labour</option>
                                    <option value="10">Brick Manufacture</option>
                                    <option value="11">Sand Supplier</option>
                                </select>
                            </div>
                            <div v-if="step==1">
                                <div class="mb-2">
                                    <label>Enter your PAN Number</label>
                                    <input type="text" v-model="pan_no" class="form-control text-uppercase" maxlength="12" />
                                </div>
                                <div class="butt">
                                    <button @click="get_pan_details" :disabled="loading" value="Login" class="btn btn-primary">{{ loading ? 'Loading...' : 'Submit'}}</button>
                                </div>
                            </div>
                            <div v-if="step==2">
                                <div class="mb-2">
                                    <label>Your Name</label>
                                    <input type="text" :value="user.full_name" class="form-control text-uppercase" disabled />
                                </div>
                                <div class="mb-2">
                                    <label>PAN Number</label>
                                    <input type="text" :value="user.pan_number" class="form-control text-uppercase" disabled />
                                </div>
                                <div class="butt">
                                    <button @click="submit_pan" :disabled="loading" value="Login" class="btn btn-primary">{{ loading ? 'Loading...' : 'Submit'}}</button>
                                </div>
                            </div>
                        </div>
                        <div class="border-start col-sm-6 mb-2">
                            <hr>
                            <div class="ridge" align="center">
                                <img src="assets/front/img/pan-card.png">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
include "common/footer.php";
?>
<script>
    let vm = new Vue({
        el: '#origin',
        data: {
            errmsg: '',
            errcls: '',
            pan_no: '',
            loading: false,
            user_id: '<?= user_id(); ?>',
            user_type: '',
            user: {
                first_name: '',
                last_name: '',
                middle_name: '',
                pan_number: '',
                full_name: ''
            },
            step: 1
        },
        methods: {
            get_pan_details: function() {
                this.errmsg = '';
                if (this.pan_no == '' || this.pan_no.length < 10 || this.pan_no.length > 12) {
                    this.errmsg = "Please enter valid PAN Number";
                    this.errcls = 'alert-danger'
                    return;
                }
                this.loading = true;
                this.errcls = 'alert-info'
                this.errmsg = "Checking... Please wait!!"
                api_call('get-pan-details', {
                    pan: this.pan_no,
                    user_id: this.user_id
                }).then(result => {
                    this.errmsg = result.message;
                    console.log(result);
                    if (result.success) {
                        this.user = result.data;
                        const {
                            first_name,
                            last_name,
                            middle_name
                        } = result.data;
                        this.user.full_name = first_name + ' ' + middle_name + ' ' + last_name;
                        this.errcls = 'alert-success';
                        this.step = 2;
                    } else {
                        this.errcls = 'alert-danger';
                    }
                    this.loading = false
                })
            },
            submit_pan: function() {
                const {
                    first_name,
                    last_name,
                    middle_name,
                    pan_number,
                } = this.user;

                let user_type = this.user_type;
                this.errmsg = '';
                if (user_type == '') {
                    this.errmsg = "You must select Account Type";
                    this.errcls = 'alert-danger'
                    return;
                }

                this.loading = true;
                api_call('pan-verify', {
                    user_id: this.user_id,
                    first_name,
                    middle_name,
                    last_name,
                    pan_number,
                    user_type
                }).then(result => {
                    this.errmsg = result.message;
                    if (result.success) {
                        this.errcls = 'alert-success';
                        setTimeout(() => {
                            window.location = 'aadhar-verification.php'
                        }, 1000)
                    } else {
                        this.errcls = 'alert-danger';
                    }
                    this.loading = false
                })
            }
        }
    })
</script>