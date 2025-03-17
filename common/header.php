<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BIHAR BHUMI SEVA</title>
    <link rel="shortcut icon" href="<?= base_url('assets/front/img/favi.ico') ?>" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@200;300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="<?= base_url('assets/front/css/bootstrap.min.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/front/style.css') ?>">
    <script src="<?= base_url('assets/front/js/jquery-3.6.0.min.js') ?>"></script>
    <script src="<?= base_url('assets/front/js/vue.min.js') ?>"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.0.0/axios.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        var apiUrl = '<?= base_url('api.php') ?>'
        $(document).ready(function() {
            $('.select2').select2();
        });
    </script>
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-84KSFP3MKN">
    </script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());
        gtag('config', 'G-84KSFP3MKN');
    </script>
    <meta name="facebook-domain-verification" content="ssuqbigg7a0r3vd64gc8xhb1ew2yyf" />
    <!-- Meta Pixel Code -->
    <script>
        ! function(f, b, e, v, n, t, s) {
            if (f.fbq) return;
            n = f.fbq = function() {
                n.callMethod ?
                    n.callMethod.apply(n, arguments) : n.queue.push(arguments)
            };
            if (!f._fbq) f._fbq = n;
            n.push = n;
            n.loaded = !0;
            n.version = '2.0';
            n.queue = [];
            t = b.createElement(e);
            t.async = !0;
            t.src = v;
            s = b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t, s)
        }(window, document, 'script',
            'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '1080724049930636');
        fbq('track', 'PageView');
    </script>
    <noscript><img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id=1080724049930636&ev=PageView&noscript=1" /></noscript>
    <!-- End Meta Pixel Code -->
</head>

<body>
    <header class="shadow-sm border-bottom mb-2">
        <div class="header-top">
            <div class="container">
                <div class="d-flex justify-content-between">
                    <div><a href="<?= base_url() ?>">Home</a>
                    </div>
                    <div>
                        <a href="#" data-bs-toggle="modal" data-bs-target="#SellForm">Sell Property</a> |
                        <?php
                        if (is_login()) {
                        ?>
                            <a href="<?= base_url('dashboard/index.php') ?>">Dashboard</a> |
                            <a href="<?= base_url('logout.php') ?>">Logout</a>
                        <?php
                        } else {
                        ?>
                            <a href="#" data-bs-toggle="modal" data-bs-target="#LoginForm">Login</a> |
                            <a href="#" data-bs-toggle="modal" data-bs-target="#SignupForm">Signup</a>
                        <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="logo-header d-flex justify-content-center align-items-center py-3">
            <a href="<?= base_url(); ?>">
                <img src="<?= theme_url('img/logo.png') ?>" width="160" />
            </a>
        </div>
    </header>

    <div class="modal fade" id="LoginForm" data-bs-keyboard="true" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Login</h1>
                    <button v-if="logintype=='password'" @click="logintype='otp'" class="btn btn-xs btn-primary">Switch to Login via OTP</button>
                    <button v-if="logintype=='otp'" @click="logintype='password'" class="btn btn-xs btn-warning">Switch to Login via Password</button>
                </div>
                <div class="modal-body">
                    <div v-if="logintype=='password'">
                        <div v-if="errmsg.length>0" class="alert" :class="errcls">{{ errmsg }}</div>
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
                            <input type="mobile" v-model="mobile_number" class="form-control" placeholder="Mobile Number" maxlength="10" />
                        </div>
                        <div class="mb-2">
                            <label>Password</label>
                            <input v-model="password" type="password" class="form-control" placeholder="xxxxx" maxlength="40" />
                        </div>
                        <div class="mb-2">
                            <button :disabled="is_loading" @click="processLogin" class="btn btn-primary btn-submit">{{ is_loading ? 'Please wait...' : 'Login'}}</button>
                        </div>
                        <div>
                            Forget password? <a href="forget-password.php">Click here</a>
                        </div>
                    </div>
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
            </div>
        </div>
    </div>
    <div class="modal fade" id="SignupForm" data-bs-keyboard="true" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Account Signup</h1>
                </div>
                <div class="modal-body">
                    <div v-if="errmsg.length>0" class="alert" :class="errcls">{{ errmsg }}</div>
                    <div class="mb-2 row">
                        <div class="col-sm-6">
                            <label>First name</label>
                            <input type="text" v-model="first_name" required class="form-control">
                        </div>
                        <div class="col-sm-6">
                            <label>Last name</label>
                            <input type="text" v-model="last_name" required class="form-control">
                        </div>
                    </div>
                    <div class="mb-2">
                        <label>Email Id</label>
                        <input v-model="email_id" type="email" class="form-control" />
                    </div>
                    <div class="row mb-2 align-items-end">
                        <div class="col">
                            <label>Mobile Number</label>
                            <input v-model="mobile" type="text" minlength="10" maxlength="10" class="form-control" required />

                        </div>
                        <div class="col">
                            <button @click="getOTP" class="btn btn-primary">Send OTP</button>
                        </div>
                    </div>
                    <div class="small"><span v-if="is_verified" class="text-success">Mobile Verified</span></div>
                    <div v-if="otp_sent" class="row mb-2 align-items-end">
                        <div class="col">
                            <label>Enter OTP</label>
                            <input v-model="user_otp" type="text" minlength="4" maxlength="4" class="form-control" required />
                        </div>
                        <div class="col">
                            <button @click="verifyOTP" class="btn btn-primary">Verify</button>
                        </div>
                    </div>
                    <div class="mb-2">
                        <label>Password</label>
                        <input type="password" v-model="passwd" class="form-control" required />
                    </div>
                    <button type="submit" :disabled="is_loading" @click="signupNow" class="btn btn-primary">{{ is_loading ? 'Please wait...' : 'Submit'}}</button>
                    <a href="#" data-bs-dismiss="modal" aria-label="Close" class="btn btn-dark">Cancel</a>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="SellForm" data-bs-keyboard="true" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header d-flex justify-content-center">
                    <h1 class="modal-title fs-5 fw-bold" id="staticBackdropLabel">अपना जिला और अंचल चुनें</h1>
                </div>
                <div class="modal-body">
                    <div v-if="errmsg.length>0" class="alert" :class="errcls">{{ errmsg }}</div>
                    <div class="row mb-2 align-items-center">
                        <div class="col-sm-4 text-end">
                            <label class="text-end">अपना जिला चुने </label>
                        </div>
                        <div class="col-sm-6">
                            <select @change="set_zones" v-model="dist_id" class="form-select">
                                <option value="">Select</option>
                                <?php
                                $dists = $db->select('ai_districts', [], false, 'dist_name ASC')->result();
                                foreach ($dists as $item1) {
                                ?>
                                    <option value="<?= $item1->id; ?>"><?= $item1->dist_name; ?></option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-4 align-items-center">
                        <div class="col-sm-4 text-end">
                            <label class="text-end">अपना अंचल चुने </label>
                        </div>
                        <div class="col-sm-6">
                            <select v-model="zone_id" class="form-select">
                                <option value="">Select</option>
                                <option v-for="item in zones" :value="item.id">{{ item.zone_name }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3 text-center">
                        <p>यदि आप किसी दलाल या ब्रोकर से संपर्क करना चाहते है तो <a href="brokers.php">यहां क्लिक करें</a></p>
                        <button @click="save_proceed" class="btn btn-primary" :class="btncls"><span></span> Next</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        new Vue({
            el: '#LoginForm',
            data: {
                errmsg: '',
                errcls: '',
                logintype: 'password',
                mobile: '',
                otp: '',
                otpSent: false,
                mobile_number: '',
                password: '',
                is_loading: false
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
                },
                processLogin: function() {
                    this.errmsg = '';
                    if (this.mobile_number == '' || this.password == '') {
                        this.errcls = 'alert-danger';
                        this.errmsg = 'Enter Mobile/Password';
                        return;
                    }
                    this.is_loading = true;
                    api_call('user-login', {
                        mobile: this.mobile_number,
                        password: this.password
                    }).then(result => {
                        console.log(result);
                        this.errmsg = result.message;
                        if (result.success) {
                            this.errcls = 'alert-success';
                            setTimeout(() => {
                                window.location.href = '<?= site_url('dashboard') ?>'
                            }, 1000)
                        } else {
                            this.errcls = 'alert-danger';
                        }
                        this.is_loading = false;
                    })
                }
            }
        })

        new Vue({
            el: '#SignupForm',
            data: {
                otp_sent: false,
                errmsg: '',
                errcls: '',
                first_name: '',
                last_name: '',
                email_id: '',
                mobile: '',
                passwd: '',
                user_otp: '',
                system_otp: '',
                is_verified: false,
                is_loading: false
            },
            methods: {
                getOTP: function() {
                    this.errmsg = '';
                    if (this.mobile.length != 10) {
                        this.errmsg = "Enter valid 10 digit mobile number";
                        this.errcls = 'alert-danger';
                        return;
                    }
                    this.is_verified = false;
                    api_call('get-signup-otp', {
                        mobile: this.mobile,
                        first_name: this.first_name
                    }).then(result => {
                        if (result.success) {
                            this.errcls = 'alert-success';
                            this.otp_sent = true;
                            this.system_otp = result.data.otp;
                        } else {
                            this.errmsg = result.message;
                            this.errcls = 'alert-danger';
                            this.otp_sent = false;
                        }
                    })
                },
                verifyOTP: function() {
                    this.errmsg = '';
                    if (this.system_otp == '') {
                        this.errcls = 'alert-danger';
                        this.errmsg = 'Click on Get OTP';
                        return;
                    }
                    if (this.user_otp.length != 4) {
                        this.errmsg = 'Enter valid OTP';
                        this.errcls = 'alert-danger';
                        return;
                    }
                    if (this.system_otp == this.user_otp) {
                        this.is_verified = true;
                    } else {
                        this.errcls = 'alert-danger';
                        this.errmsg = "OTP is not matching.";
                        this.is_verified = false;
                    }

                },
                signupNow: function() {
                    this.errmsg = '';
                    if (this.first_name == '' || this.last_name == '' || this.email_id == '' || this.passwd == '') {
                        this.errmsg = 'Please fill all fields';
                        this.errcls = 'alert-danger';
                        return;
                    } else if (this.is_verified == false) {
                        this.errcls = 'alert-danger';
                        this.errmsg = "Your mobile is not verified.";
                        return;
                    } else if (this.passwd.length < 8) {
                        this.errcls = 'alert-danger';
                        this.errmsg = "Password must be min 8 character length.";
                        return;
                    }
                    this.errcls = '';
                    this.is_loading = true;
                    api_call('user-signup', {
                        first_name: this.first_name,
                        last_name: this.last_name,
                        email_id: this.email_id,
                        passwd: this.passwd,
                        mobile: this.mobile
                    }).then(result => {
                        this.errmsg = result.message;
                        console.log(result);
                        if (result.success) {
                            this.errcls = 'alert-success';
                            setTimeout(function() {
                                window.location.reload();
                            }, 3000);
                        } else {
                            this.errcls = 'alert-danger';
                        }
                    })
                }
            }
        })

        new Vue({
            el: '#SellForm',
            data: {
                zones: [],
                dist_id: '',
                zone_id: '',
                btncls: '',
                errmsg: '',
                errcls: '',
                gps_on: false,
                lat: '',
                lng: ''
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
                save_proceed: function() {
                    this.errmsg = '';
                    if (this.dist_id == '' || this.zone_id == '') {
                        this.errcls = 'alert-danger';
                        this.errmsg = 'Please fill all required fields';
                        return;
                    }
                    this.btncls = 'btn-loading';
                    api_call('save-proceed', {
                        dist_id: this.dist_id,
                        zone_id: this.zone_id,
                        membership: '<?= $type; ?>',
                        lat: this.lat,
                        lng: this.lng
                    }).then(resp => {
                        if (resp.success) {
                            this.errcls = 'alert-success';
                            this.errmsg = resp.message;
                            this.btncls = ''
                            setTimeout(() => {
                                window.location = '/property-details.php?id=' + resp.data;
                            }, 1000);
                        } else {
                            this.errcls = 'alert-danger';
                            this.errmsg = resp.message;
                            this.btncls = ''
                        }
                    })
                },
                updateLocation: function(position) {
                    const {
                        latitude,
                        longitude
                    } = position.coords
                    this.gps_on = true;
                    this.lat = latitude;
                    this.lng = longitude;
                }
            },
            created: function() {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(this.updateLocation, function() {
                        alert("Bihar Bhumi Seva wants to access your Location.");
                    })
                } else {
                    alert("GPS Location not supported")
                }
            }
        });
    </script>