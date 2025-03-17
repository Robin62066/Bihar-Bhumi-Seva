<?php
include "config/autoload.php";
$source = $_GET['source'] ?? 'desktop';
$dists = $db->select('ai_districts', [], false, 'dist_name ASC')->result();
if (input_post('btnsend')) {
    $form = input_post('form');
    $form['created'] = date("Y-m-d H:i:s");
    $form['updated'] = date("Y-m-d H:i:s");
    $form['status'] = 1;
    if (is_login()) {
        $form['user_id'] = user_id();
    } else {
        $form['user_id'] = 0;
    }
    $db->insert('ai_complains', $form);
    redirect(base_url('complaint.php'), "Your complain submitted successfully", "success");
}
if ($source == "app") {
?>
    <html>

    <head>
        <link rel="shortcut icon" href="<?= base_url('assets/front/img/favi.ico') ?>" type="image/x-icon">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@200;300;400;500&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
        <link rel="stylesheet" href="<?= base_url('assets/front/css/bootstrap.min.css'); ?>">
        <link rel="stylesheet" href="<?= base_url('assets/front/style.css') ?>">
        <script src="<?= base_url('assets/front/js/vue.min.js') ?>"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.0.0/axios.min.js"></script>
        <script>
            var apiUrl = '<?= base_url('api.php') ?>'
            $(document).ready(function() {
                $('.select2').select2();
            });
        </script>
    </head>

    <body>
        <div id="origin" class="container">
            <div class="row g-0">
                <div class="col-sm-12">
                    <div class="bg-white p-3">
                        <?= front_view("common/alert"); ?>
                        <h1 class="h5">Register your complain</h1>
                        <hr />
                        <form action="" method="post" class="mb-3">
                            <div class="row mb-3">
                                <div class="col-sm-6">
                                    <select @change="set_zones" name="form[dist_id]" v-model="dist_id" class="form-select">
                                        <option value="">Select District</option>
                                        <?php
                                        foreach ($dists as $item) {
                                        ?>
                                            <option value="<?= $item->id; ?>"><?= $item->dist_name; ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-sm-6">
                                    <select name="form[zone_id]" v-model="zone_id" class="form-select">
                                        <option value="">Select Anchal</option>
                                        <option v-for="item in zones" :value="item.id">{{ item.zone_name }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-2">
                                <label>Your name <span class="text-danger">*</span> </label>
                                <input name="form[yourname]" required type="text" class="form-control">
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-6">
                                    <label>Mobile No <span class="text-danger">*</span></label>
                                    <input name="form[mobile]" required maxlength="10" type="text" class="form-control">
                                </div>
                                <div class="col-sm-6">
                                    <label>Email Id <span class="text-danger">*</span></label>
                                    <input type="email" required name="form[email_id]" class="form-control">
                                </div>
                            </div>
                            <!-- <div class="row align-items-center mb-2">
                        <label class="col-sm-2">Enter OTP</label>
                        <div class="col-sm-3">
                            <input type="text" class="form-control">
                        </div>
                        <div class="col-sm-2 d-grid">
                            <button class="btn btn-warning">Verify</button>
                        </div>
                    </div> -->
                            <div class="mb-2">
                                <label>Explain your complain <span class="text-danger">*</span></label>
                                <textarea name="form[details]" required type="text" rows="4" class="form-control"></textarea>
                            </div>
                            <button name="btnsend" value="Submit" class="btn btn-primary">Submit</button>
                            <button type="reset" class="btn btn-dark">Reset</button>
                        </form>

                        <p class="small text-danger">After submitting, We may contact you on your given mobile number or email id for details. We will try to resolve your issue with 72hrs. </p>
                    </div>
                </div>
            </div>
        </div>
        <script src="<?= base_url('assets/front/js/custom.js') ?>"></script>
    </body>


    </html>
<?php
} else {

    include "common/header.php";
?>

    <div id="origin" class="container">
        <div class="row g-0">
            <?= front_view('common/home-menu'); ?>
            <div class="col-sm-9">
                <div class="bg-white p-3">
                    <?= front_view("common/alert"); ?>
                    <h1 class="h5">Register your complain</h1>
                    <hr />
                    <form action="" method="post" class="mb-3">
                        <div class="row mb-3">
                            <div class="col-sm-6">
                                <select @change="set_zones" name="form[dist_id]" v-model="dist_id" class="form-select">
                                    <option value="">Select District</option>
                                    <?php
                                    foreach ($dists as $item) {
                                    ?>
                                        <option value="<?= $item->id; ?>"><?= $item->dist_name; ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-sm-6">
                                <select name="form[zone_id]" v-model="zone_id" class="form-select">
                                    <option value="">Select Anchal</option>
                                    <option v-for="item in zones" :value="item.id">{{ item.zone_name }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-2">
                            <label>Your name <span class="text-danger">*</span> </label>
                            <input name="form[yourname]" required type="text" class="form-control">
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-6">
                                <label>Mobile No <span class="text-danger">*</span></label>
                                <input name="form[mobile]" required maxlength="10" type="text" class="form-control">
                            </div>
                            <div class="col-sm-6">
                                <label>Email Id <span class="text-danger">*</span></label>
                                <input type="email" required name="form[email_id]" class="form-control">
                            </div>
                        </div>
                        <!-- <div class="row align-items-center mb-2">
                        <label class="col-sm-2">Enter OTP</label>
                        <div class="col-sm-3">
                            <input type="text" class="form-control">
                        </div>
                        <div class="col-sm-2 d-grid">
                            <button class="btn btn-warning">Verify</button>
                        </div>
                    </div> -->
                        <div class="mb-2">
                            <label>Explain your complain <span class="text-danger">*</span></label>
                            <textarea name="form[details]" required type="text" rows="4" class="form-control"></textarea>
                        </div>
                        <button name="btnsend" value="Submit" class="btn btn-primary">Submit</button>
                        <button type="reset" class="btn btn-dark">Reset</button>
                    </form>

                    <p class="small text-danger">After submitting, We may contact you on your given mobile number or email id for details. We will try to resolve your issue with 72hrs. </p>
                </div>
            </div>
        </div>
    </div>
<?php
    include "common/footer.php";
}
?>
<script>
    let vm = new Vue({
        el: '#origin',
        data: {
            zones: [],
            dist_id: '',
            zone_id: '',
            btncls: '',
            errmsg: '',
            errcls: '',
            pid: 0
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
        },
        created: function() {
            if (this.dist_id != '') {
                this.set_zones();
            }
        }
    });
</script>