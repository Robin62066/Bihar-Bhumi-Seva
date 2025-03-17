<?php
include "config/autoload.php";
if (!is_login()) redirect(base_url('login.php'), 'Please Login to continue', 'danger');
$us = userdata('user');
$user_id = user_id();
if (isset($_POST['btn'])) {
    $sb = $_POST['form'];
    $sb['image'] = do_upload('image');
    $sb['signup_completed'] = 1;
    if ($sb['image'] != '') {
        $db->update('ai_users', $sb, ['id' => $user_id]);
    }
    $us = $db->select('ai_users', ['id' => $user_id], 1)->row();
    if ($us->pan_verified == 1 && $us->aadhar_verified == 1 && $us->image != '') {
        $sb = [];
        $sb['kyc_status'] = 1;
        $db->update('ai_users', $sb, ['id' => $user_id]);
    }

    // Add Free Basic Plan
    $sb = [];
    $sb['user_id']  = $user_id;
    $sb['plan_id']  = 1;
    $sb['start_dt'] = date("Y-m-d");
    $sb['end_dt']   = date("Y-m-d", strtotime("+1 months"));
    $sb['created']  = date("Y-m-d H:i:s");
    $sb['status']   = 1;
    $db->insert("ai_membership", $sb);

    redirect(site_url('dashboard/index.php'), "Account activated successfully", "success");
}
include "common/header.php";
?>
<style>
    .progressbar li {
        width: 50%;
    }
</style>
<div class="login-wrapper py-5">
    <div id="origin" class="container">
        <div class="row">
            <div class="col-sm-10 m-auto">
                <ul class="progressbar">
                    <li>Mobile Verification</li>
                    <!-- <li>PAN Verification</li> -->
                    <!-- <li>Aadhar Varification</li> -->
                    <li class="active">Personal Info</li>
                </ul>
                <div class="bg-white rounded shadow-sm overflow-hidden mb-3 p-3">
                    <div class="row">
                        <h6 class="text-center"><b>Personal Information</b></h6>
                        <div class="col-sm-6">
                            <hr />
                            <div v-if="errmsg.length>0" class="alert" :class="errcls">{{ errmsg }}</div>
                            <form action="" enctype="multipart/form-data" method="post">
                                <div class="mb-3">
                                    <label>Login Id</label>
                                    <input type="text" disabled value="<?= $us->mobile_number; ?>" class="form-control" />
                                </div>
                                <div class="mb-3">
                                    <label>Password</label>
                                    <input type="password" required name="form[passwd]" class="form-control" />
                                </div>
                                <div class="mb-3">
                                    <label>Email Id</label>
                                    <input type="text" required name="form[email_id]" class="form-control" />
                                </div>
                                <div class="mb-3">
                                    <label>Upload Profile Photo</label>
                                    <input type="file" accept="image/*" required name="image" class="form-control" />
                                </div>
                                <button name="btn" value="upload" class="btn btn-primary">UPLOAD</button>
                            </form>

                        </div>
                        <div class="border-start col-sm-6 mb-2">
                            <hr>
                            <div class="ridge" align="center">
                                <img src="assets/front/img/personal-info.png" class="img-fluid">
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
                fatherName: 'a',
                gender: 'M',
                dob: 'C',
                name: 'D',
                maskedAadhaarNumber: 'e',
                combinedAddress: 'f'
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
                    combinedAddress
                } = this.aadhar_info;
                api_call('upadate-aadhar-info', {
                    user_id: this.user_id,
                    aadhar_name: name,
                    father_name: fatherName,
                    gender: gender,
                    dob: dob,
                    address: combinedAddress
                }).then(result => {
                    this.errmsg = result.message;
                    if (result.success) {
                        this.errcls = 'alert-success';
                        setTimeout(() => {
                            window.location = '<?= base_url('photo-upload.php') ?>'
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