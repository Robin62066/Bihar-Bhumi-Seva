<?php
include "config/autoload.php";
include_once "common/header-home.php";
?>
<div class="position-relative">
    <img src="assets/front/img/top.jpg" class="img-fluid w-100" />
    <div class="logo-wrapper-home">
        <div class="logos text-white">
            बिहार भूमि <br />
            खरीद-बिक्री सेवा समिति
        </div>
    </div>
</div>
<div class="container">
    <div class="text-end mt-4">
        <a href="login.php" class="btn btn-outline-primary">SELECT YOUR DISTRICT</a>
    </div>
    <div class="bihar-map py-4 text-center">
        <img src="assets/front/img/map.png" class="m-auto" />
    </div>
    <div class="section-row">
        <div class="heading text-center">
            <h5 class="h4 text-danger">What We Do</h5>
        </div>
        <div class="services">
            <div class="row g-2">
                <div class="col-sm-6 col-md-3">
                    <div class="sbox shadow-sm">
                        <div class="simg">
                            <img src="<?= theme_url('img/property.jpg') ?>" class="w-100" />
                        </div>
                        <div class="p-3">
                            सम्पति को पोस्ट करें
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3">
                    <div class="sbox shadow-sm">
                        <div class="simg">
                            <img src="<?= theme_url('img/mut.jpg') ?>" class="w-100" />
                        </div>
                        <div class="p-3">
                            ऑनलाइन म्युटेशन करें
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3">
                    <div class="sbox shadow-sm">
                        <div class="simg">
                            <img src="<?= theme_url('img/register.jpg') ?>" class="w-100" />
                        </div>
                        <div class="p-3">
                            मुंशी पंजीकरण फॉर्म
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3">
                    <div class="sbox shadow-sm">
                        <div class="simg">
                            <img src="<?= theme_url('img/land.jpg') ?>" class="w-100" />
                        </div>
                        <div class="p-3">
                            प्लॉट एजेंट पंजीकरण
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="section-row">
        <div class="heading text-center">
            <h5 class="h4 text-danger">Top Districts</h5>
        </div>
        <div class="dist-wapper text-center">
            <div class="row g-2">
                <div class="col-sm-2 col-md-2">
                    <a href="#">पटना</a>
                </div>
                <div class="col-sm-2 col-md-2">
                    <a href="#">बेगूसराय</a>
                </div>
                <div class="col-sm-2 col-md-2">
                    <a href="#">गया</a>
                </div>
                <div class="col-sm-2 col-md-2">
                    <a href="#">मुजफ्फरपुर</a>
                </div>
                <div class="col-sm-2 col-md-2">
                    <a href="#">भागलपुर</a>
                </div>
                <div class="col-sm-2 col-md-2">
                    <a href="#">औरंगाबाद</a>
                </div>
                <div class="col-sm-2 col-md-2">
                    <a href="#">दरभंगा</a>
                </div>
                <div class="col-sm-2 col-md-2">
                    <a href="#">नालंदा</a>
                </div>
                <div class="col-sm-2 col-md-2">
                    <a href="#">समस्तीपुर</a>
                </div>
                <div class="col-sm-2 col-md-2">
                    <a href="#">सीतामढ़ी</a>
                </div>
                <div class="col-sm-2 col-md-2">
                    <a href="#">अरवल</a>
                </div>
                <div class="col-sm-2 col-md-2">
                    <a href="#">जहानाबाद</a>
                </div>
            </div>
        </div>
    </div>
    <div class="section-row">
        <div class="heading text-center">
            <h5 class="h4 text-danger">Blogs</h5>
        </div>
        <div class="services">
            <div class="row g-2">
                <div class="col-sm-6 col-md-4">
                    <div class="sbox shadow-sm">
                        <div class="simg">
                            <img src="<?= theme_url('img/blog9.jpg') ?>" class="w-100" />
                        </div>
                        <div class="p-3">
                            Service 1
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-4">
                    <div class="sbox shadow-sm">
                        <div class="simg">
                            <img src="<?= theme_url('img/blog4.jpg') ?>" class="w-100" />
                        </div>
                        <div class="p-3">
                            Service 2
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-4">
                    <div class="sbox shadow-sm">
                        <div class="simg">
                            <img src="<?= theme_url('img/blog11.jpg') ?>" class="w-100" />
                        </div>
                        <div class="p-3">
                            Service 3
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