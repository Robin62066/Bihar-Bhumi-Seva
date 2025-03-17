<?php
include "config/autoload.php";
include "common/header.php";
?>
<!-- <style>
    .pricing-wrapper {
        text-align: center;
        right: 0;
        left: 0;
        text-align: center;
        bottom: 20px;
        display: flex;
        justify-content: center;
    }

    .buy1,
    .buy2 {
        width: 300px;
        height: 80px;
    }

    .plan-body {
        padding: 20px 0;
    }

    .plan-body ul {
        list-style: none;
        margin: 0;
        padding: 0;
    }

    .plan-body ul li {
        background: url('./assets/front/img/001-check.png') left center no-repeat;
        padding-left: 20px;
        margin-bottom: 20px;
    }

    .popular-band {
        -webkit-transform: rotate(45deg);
        float: right;
        position: absolute;
        right: -34px;
        width: 148px;
        top: 17px;
    }
</style> -->
<style>
    body {
        background-color: #f8f9fa;
        font-family: Arial, sans-serif;
    }

    .container {
        text-align: center;
    }

    .pricing-table {
        border: 1px solid #dee2e6;
        border-radius: 5px;
        padding: 20px;
        margin: 20px;
        background-color: #ffffff;
    }

    .price {
        font-size: 24px;
        font-weight: bold;
        margin: 20px 0;
    }

    .features {
        text-align: left;
        margin-bottom: 20px;
    }

    .highlight {
        font-size: 20px;
        color: #28a745;
        font-weight: bold;
    }

    .btn-custom {
        background-color: #28a745;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        font-size: 18px;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .btn-custom:hover {
        background-color: #218838;
    }
</style>
<div class="container py-4">
    <h4>अपना सदस्यता योजना चुनें / Choose Your Membership Plan</h4>
    <div class="row">

        <div class="col-md-6">
            <div class="pricing-table h-100">
                <h3>फ्री योजना / Free Plan</h3>
                <div class="price">₹0 / मुफ्त</div>
                <p class="highlight">पहले 30 दिनों के लिए सभी योजनाएं मुफ्त / All Plans FREE for First 30 Days</p>
                <div class="features">
                    <ul>
                        <li>बुनियादी / Basic</li>
                        <li>स्टार्टर / Starter</li>
                        <li>10 संदेश प्राप्त करें / Get 10 Messages</li>
                        <li>10 लीड प्राप्त करें / Get 10 Leads</li>
                        <li>10 कॉल प्राप्त करें / Get 10 Calls</li>
                        <li>10 व्हाट्सएप लीड प्राप्त करें / Get 10 WhatsApp Leads</li>
                        <li>2 विज्ञापन पोस्ट करें / Post 2 Ads</li>
                        <li>1,000 लोगों के साथ विज्ञापन इंटरैक्ट करें / 1k People Interact Ads</li>
                    </ul>
                </div>
                <div class="text-center">
                    <a href="<?= base_url('buy-plan.php?plan=1') ?>" class="btn btn-custom">अब सदस्यता लें / Subscribe Now </a>
                </div>

            </div>
        </div>

        <div class="col-md-6">
            <div class="pricing-table h-100">
                <h3>पॉपुलर योजना / Popular Plan</h3>
                <div class="price">₹499 / सालाना</div>
                <p class="highlight">बेहतर परिणाम / Better Results</p>
                <div class="features">
                    <ul>
                        <li>सभी बुनियादी सुविधाएँ / All Basic Features</li>
                        <li>विशेष भूमि रिपोर्ट / Special Land Reports</li>
                        <li>कस्टम सेवाएँ / Custom Services</li>
                        <li>सहायता के लिए प्राथमिकता / Priority Support</li>
                        <li>अनलिमिटेड संदेश प्राप्त करें / Get Unlimited Messages</li>
                        <li>अनलिमिटेड लीड प्राप्त करें / Get Unlimited Leads</li>
                        <li>अनलिमिटेड कॉल प्राप्त करें / Get Unlimited Calls</li>
                        <li>अनलिमिटेड व्हाट्सएप लीड प्राप्त करें / Get Unlimited WhatsApp Leads</li>
                        <li>अनलिमिटेड विज्ञापन पोस्ट करें / Post Unlimited Ads</li>
                        <li>अनलिमिटेड लोगों के साथ विज्ञापन इंटरैक्ट करें / Unlimited People Interact Ads</li>
                    </ul>
                </div>
                <div class="text-center">
                    <a href="<?= base_url('buy-plan.php?plan=2') ?>" class="btn btn-custom ">अब सदस्यता लें / Subscribe Now </a>
                </div>

            </div>
        </div>

    </div>
    <?php
    /* 
    <div class="clearfix position-relative d-none">
        <div class="text-center mb-4">
            <h1 class="h3">Choose your Membership Plan</h1>
        </div>
        <div class="text-center mb-4">All Plan FREE for First 30 Days</div>
        <div class="row">
            <div class="col-sm-8 m-auto">
                <div class="row">
                    <div class="col-6">
                        <div class="card p-4 border-0 shadow-sm h-100">
                            <div class="text-center">
                                <h5><b>Basic</b></h5>
                                <div>Starter</div>
                                <h2 class="h5">1 Month Free</h2>
                            </div>
                            <div class="plan-body">
                                <ul>
                                    <li>Get 100 Messages</li>
                                    <li>Get 100 Leads</li>
                                    <li>Get 100 Calls</li>
                                    <li>Get 100 WhatsApp Lead</li>
                                    <li>Post 2 Ad</li>
                                    <li>50k People Interact Ads</li>
                                </ul>
                            </div>
                            <div class="text-center">
                                <a href="<?= base_url('buy-plan.php?plan=1') ?>" class="btn btn-primary">Choose Plan</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="card p-4 border-0 shadow-sm overflow-hidden h-100">
                            <div class="bg-primary p-2 text-white popular-band text-center">Popular</div>
                            <div class="text-center">
                                <h5><b>Standard</b></h5>
                                <div>Better Results</div>
                                <h2 class="h5">6500/- Yearly</h2>
                            </div>
                            <div class="plan-body">
                                <ul>
                                    <li>Get Unlimited Messages</li>
                                    <li>Get Unlimited Leads</li>
                                    <li>Get Unlimited Calls</li>
                                    <li>Get Unlimited WhatsApp Lead</li>
                                    <li>Post Unlimited Ad</li>
                                    <li>Unlimited People Interact Ads</li>
                                </ul>
                            </div>
                            <div class="text-center">
                                <a href="<?= base_url('buy-plan.php?plan=2') ?>" class="btn btn-primary">Choose Plan</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- <img src="<?= base_url('assets/front/img/pricing.jpg') ?>" class="img-fluid w-100" />
        <div class="position-absolute pricing-wrapper">
            <a href="<?= base_url('buy-plan.php?plan=1') ?>">
                <div class="buy1"></div>
            </a>
            <div style="width: 50px;"></div>
            <a href="<?= base_url('buy-plan.php?plan=2') ?>">
                <div class="buy2"></div>
            </a>
        </div> -->
    </div>
    */
    ?>
</div>
<?php
include "common/footer.php";
