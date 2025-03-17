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
                            <h5>Recover your mobile no</h5>
                            <hr />
                            <div>
                                <div v-if="errmsg.length>0" class="alert" :class="errcls">{{errmsg}}</div>
                                <div class="mb-2">
                                    <label>Enter your PAN Number</label>
                                    <input type="mobile" v-model="pan" class="form-control text-uppercase mb-2" placeholder="PAN Number" required maxlength="12" />
                                </div>
                                <div class="mb-2">
                                    <button @click="sendUserid" :disabled="clicked||pan.length==0" class="btn btn-primary">{{ clicked ? 'Please wait' : 'Submit'}}</button>
                                    <a href="login.php" class="btn btn-warning">Cancel</a>
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
            pan: '',
            clicked: false
        },
        methods: {
            sendUserid: function() {
                this.errmsg = '';
                if (this.pan.trim() == '') {
                    this.errmsg = "Enter valid PAN Number";
                }
                if (this.errmsg.length > 0) {
                    this.errcls = 'alert-danger';
                    return;
                }
                this.clicked = true;
                api_call('reset-userid', {
                    pan_no: this.pan
                }).then(result => {
                    console.log(result)
                    this.errmsg = result.message;
                    if (result.success) {
                        this.errcls = 'alert-success';
                    } else {
                        this.errcls = 'alert-danger'
                    }
                    this.clicked = false;
                })
            }
        }
    })
</script>