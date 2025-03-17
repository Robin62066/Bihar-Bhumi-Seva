<?php
include "config/autoload.php";

if (is_login()) {
    redirect(site_url('dashboard'));
}
if (isset($_POST['btn_login'])) {
    $mobile_number = $_POST['mobile_number'];
    $password = $_POST['password'];
    // $type = $_POST['user_type'];
    $result = $db->select('ai_users', ['mobile_number' => $mobile_number, 'passwd' => $password]);
    if ($result->count() == 1) {
        // Authentication successful
        $user = $result->row();
        $type = $user->user_type;
        set_userdata('user', $user);
        if ($user->mobile_verified == 0) {
            $otp = rand(1111, 9999);
            $db->update('ai_users', ['mobile_otp' => $otp], ['id' => $user->id]);
            $_SESSION['verification_id'] = $user->id;
            sendSMS($user->first_name, $otp, $user->mobile_number);
            redirect('account-verify.php', "Please verify your mobile");
        }
        redirect('dashboard/index.php');
    } else {
        redirect('login.php', "Authentication failed. Please check your mobile number and password.", 'danger');
    }
}
include_once "common/header.php";
?>

<div class="login-wrapper py-5">
    <div id="origin" class="container">
        <div class="row">
            <div class="col-sm-10 m-auto">
                <?php
                include "common/alert.php";
                ?>
                <div class="bg-white rounded shadow-sm overflow-hidden mb-3 p-3">
                    <div class="row">
                        <div class="col-sm-6 d-none d-sm-block border-end">
                            <h6 class="text-center"><b>दिशानिर्देश</b></h6>
                            <hr />
                            <p> यदि आप नए उपयोगकर्ता है तो पहले "साइन अप" पर क्लिक कर अपना खता बनायें फिर "लॉगिन" करें। और यदि आपका खता है तो नीचे दिए गए दिशानिर्देश का पालन करें। </p>
                            1. लॉगिन करें के लिए खता प्रकार चुनें। <br />
                            2. मोबाइल नंबर बॉक्स में आप अपना मोबाइल नंबर डालें। <br />
                            3. सुरक्षा कोड बॉक्स में अपना सुरक्षा पासवर्ड दर्ज़ करें। <br />
                            4. लॉगिन बटन पर क्लिक करें।
                        </div>
                        <div class="mb-2 col-sm-6">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5>Login</h5>
                                <button v-if="logintype=='password'" @click="logintype='otp'" class="btn btn-xs btn-primary">Switch to Login via OTP</button>
                                <button v-if="logintype=='otp'" @click="logintype='password'" class="btn btn-xs btn-warning">Switch to Login via Password</button>
                            </div>
                            <hr />
                            <form v-if="logintype=='password'" action="" method="post">
                                <!-- <div class="mb-2">
                                    <label>Account Type</label>
                                    <select required name="user_type" class="form-select">
                                        <option value="">Select Account</option>
                                        <option value="1">Customer</option>
                                        <option value="2">Land Owner</option>
                                        <option value="3">Broker/Builder</option>
                                        <option value="4">Munsi</option>
                                        <option value="5">Amin</option>
                                        <option value="8">Bhumi Locker</option>
                                    </select>
                                </div> -->
                                <div class="mb-2">
                                    <label>Mobile Number</label>
                                    <input type="mobile" name="mobile_number" class="form-control" placeholder="Mobile Number" required />
                                </div>
                                <div class="mb-2">
                                    <label>Password</label>
                                    <input name="password" type="password" class="form-control" placeholder="xxxxx" required />
                                </div>
                                <div class="mb-2">
                                    <input type="hidden" name="btn_login" value="Login">
                                    <button class="btn btn-primary btn-submit">Login</button>
                                    <a href="signup.php" class="btn btn-warning">Create an account</a>
                                </div>
                                <div>
                                    Forget password? <a href="forget-password.php">Click here</a>
                                </div>
                            </form>
                            <div v-if="logintype=='otp'">
                                <div class="mb-2">
                                    <label>Mobile Number</label>
                                    <input type="mobile" maxlength="10" v-model="mobile" class="form-control" placeholder="Mobile Number" required />
                                </div>
                                <div v-if="otpSent" class="mb-2">
                                    <label>Enter OTP</label>
                                    <input type="mobile" maxlength="6" v-model="otp" class="form-control" placeholder="OTP" required />
                                </div>

                                <div class="mb-2">
                                    <button @click="getOTP" :disabled="otpSent" class="btn btn-primary">Get OTP</button>
                                    <button @click="verifyOTP" :disabled="!otpSent" class="btn btn-warning">Verify OTP</button>
                                </div>
                                <div>
                                    Forget password? <a href="forget-password.php">Click here</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 d-block d-sm-none">
                            <h6 class="text-center"><b>दिशानिर्देश</b></h6>
                            <hr />
                            <p> यदि आप नए उपयोगकर्ता है तो पहले "साइन अप" पर क्लिक कर अपना खता बनायें फिर "लॉगिन" करें। और यदि आपका खता है तो नीचे दिए गए दिशानिर्देश का पालन करें। </p>
                            1. लॉगिन करें के लिए खता प्रकार चुनें। <br />
                            2. मोबाइल नंबर बॉक्स में आप अपना मोबाइल नंबर डालें। <br />
                            3. सुरक्षा कोड बॉक्स में अपना सुरक्षा पासवर्ड दर्ज़ करें। <br />
                            4. लॉगिन बटन पर क्लिक करें।
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
include_once "common/footer.php";
?>
<script>
    new Vue({
        el: '#origin',
        data: {
            errmsg: '',
            errcls: '',
            logintype: 'password',
            mobile: '',
            otp: '',
            otpSent: false
        },
        methods: {
            getOTP: function() {
                if (this.mobile.length != 10) {
                    alert("Enter 10 Digit mobile number only.");
                    return;
                }
                api_call('get-otp', {
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
                        window.location.href = 'dashboard/index.php';
                    } else {
                        alert(resp.message);
                    }
                })
            }
        }
    })
</script>