<?php
include "config/autoload.php";
include_once "common/header.php";
?>

<div class="login-wrapper py-5">
    <div id="origin" class="container">
        <div class="row">
            <div class="col-sm-6 m-auto">
                <?php
                include "common/alert.php";
                ?>
                <div class="bg-white rounded shadow-sm overflow-hidden mb-3 p-3">
                    <div class="row">
                        <div class="col-sm-12">
                            <h5>Forget Password</h5>
                            <hr />
                            <div>
                                <p>Enter your registered mobile number.</p>
                                <div v-if="errmsg.length>0" class="alert" :class="errcls">{{errmsg}}</div>
                                <div class="mb-2">
                                    <label>Mobile Number</label>
                                    <input type="mobile" v-model="mobile" class="form-control mb-2" placeholder="Mobile Number" required />
                                    <div class="text-end">
                                        Forget mobile no? <a href="recover-mobile.php">Click here</a>
                                    </div>
                                </div>
                                <div class="mb-2">
                                    <button @click="sendPassword" :disabled="clicked" class="btn btn-primary">{{ clicked ? 'Please wait' : 'Submit'}}</button>
                                    <a href="signup.php" class="btn btn-warning">Create an account</a>
                                </div>
                                <div>
                                    Already have password? <a href="login.php">Click here</a>
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
include_once "common/footer.php";
?>
<script>
    let vm = new Vue({
        el: '#origin',
        data: {
            errmsg: '',
            errcls: '',
            mobile: '',
            clicked: false
        },
        methods: {
            sendPassword: function() {
                this.errmsg = '';
                if (this.mobile == '') {
                    this.errmsg = "Enter mobile no";
                } else if (this.mobile.length != 10) {
                    this.errmsg = "Enter valid mobile no";
                }
                if (this.errmsg.length > 0) {
                    this.errcls = 'alert-danger';
                    return;
                }
                this.clicked = true;
                api_call('reset-password', {
                    mobile: this.mobile
                }).then(result => {
                    this.errmsg = result.message;
                    if (result.success) {
                        this.errcls = 'alert-success';
                    } else {
                        this.errcls = 'alert-danger'
                    }
                    this.clicked = false
                })
            }
        }
    })
</script>