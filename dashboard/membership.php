<?php
include "../config/autoload.php";
if (!is_login()) redirect(base_url('login.php'), 'You must login to continue');
$user_id = user_id();
include "../common/header.php";
?>
<style type="text/css">
    .bhumi {
        background-color: darkcyan;
        color: white;
        text-align: center;
    }



    .plans {
        display: flex;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 10px;
    }

    .plan-card {
        background-color: white;
        height: 392px;
        width: 100%;
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 20px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        text-align: center;
        transition: transform 0.3s ease;
    }



    .plan-card h3 {
        font-size: 16px;
        margin-bottom: 10px;
        color: #003366;
        font-weight: 600;
    }

    .price {
        font-size: 25px;
        color: #003366;
        margin-bottom: 5px;
    }

    .duration {
        font-size: 18px;
        color: #555;
        margin-bottom: 7px;
    }

    .plan-description {
        font-size: 16px;
        color: #666;
        margin-bottom: 20px;
    }


    .modal {
        display: none;
        position: fixed;
        z-index: 1;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.4);
        overflow: auto;
    }

    .modal-content {
        background-color: #fff;
        margin: 15% auto;
        padding: 10px;
        border-radius: 8px;
        width: 40%;
    }

    .modal-header {
        font-size: 24px;
        font-weight: bold;
        margin-bottom: 20px;
    }

    .modal-body {
        font-size: 16px;
        margin-bottom: 20px;

    }


    @media (max-width: 768px) {
        .plan-card {

            height: 300px;
        }

        .modal-content {
            width: 100%;
        }
    }

    @media (max-width: 480px) {
        .plan-card {
            width: 100%;
        }
    }
</style>
<div class="dashboard">
    <div class="container py-5">
        <div class="user-panel">
            <div class="row">
                <?php
                $subpage = 'membership';
                include_once "dashboard-menu.php"; ?>
                <div class="col-sm-9">
                    <div id="origin" class="h-100">
                        <div class="bg-white p-3 shadow-sm rounded-sm">
                            <div class="page-header">
                                <h5>Membership</h5>
                            </div>
                            <?php
                            $chkMembership = $db->select('ai_membership', ['user_id' => $user_id, 'plan_id' => 1], 1)->row();
                            if (is_object($chkMembership)) {
                                if ($chkMembership->status == 1) {
                            ?>
                                    <div class="alert p-3 alert-info">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span>Your Account Membership is FREE Subscribe Now</span>
                                            <!-- <a href="<?= base_url('buy-plan.php?plan=2')  ?>" class="btn btn-sm btn-primary">UPGRADE</a> -->
                                        </div>
                                    </div>
                            <?php
                                }
                            }
                            ?>
                            <div class="container">
                                <div class="card">

                                    <div class="row p-4 ">
                                        <div class="col-md-3">
                                            <div class="plan-card" onclick="openModal('basic')">
                                                <h3>Monthly Plan</h3>
                                                <div class="price">₹ 199</div>
                                                <div class="duration">1 Month</div>
                                                <div class="plan-description">
                                                    Access all the basic services for a month. Ideal for short-term needs.
                                                </div>
                                                <button class="btn btn-primary mt-4">Subscribe Now</button>
                                            </div>
                                        </div>
                                        <div class="col-md-3 mt-2">
                                            <div class="plan-card " onclick="openModal('premium')">
                                                <h3>Quarterly Plan</h3>
                                                <div class="price">₹ 499</div>
                                                <div class="duration">3 Months</div>
                                                <div class="plan-description">
                                                    Save more with this quarterly plan. Get full access for three months.
                                                </div>
                                                <button class="btn btn-primary mt-4">Subscribe Now</button>
                                            </div>
                                        </div>
                                        <div class="col-md-3 mt-2">
                                            <div class="plan-card" onclick="openModal('gold')">
                                                <h3>Half-Yearly Plan</h3>
                                                <div class="price">₹ 899</div>
                                                <div class="duration">6 Months</div>
                                                <div class="plan-description">
                                                    Enjoy a discount and extended services for six months. Best value for long-term usage.
                                                </div>
                                                <button class="btn btn-primary">Subscribe Now</button>
                                            </div>
                                        </div>
                                        <div class="col-md-3 mt-2 ">
                                            <div class="plan-card" onclick="openModal('plan')">
                                                <h3>Yearly Plan</h3>
                                                <div class="price">₹ 1599</div>
                                                <div class="duration">12 Months</div>
                                                <div class="plan-description">
                                                    Get the best value with our yearly plan. Unlock all services for the whole year.
                                                </div>
                                                <button class="btn btn-primary mt-1">Subscribe Now</button>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div id="basic" class="modal">
                                    <div class="modal-content">

                                        <div class="modal-header">Basic Plan
                                            <span class="close" onclick="closeModal('basic')">&times;</span>
                                        </div>

                                        <div class="modal-body">
                                            <p><strong>Price:</strong> ₹199.00</p>
                                            <p><strong>Features:</strong></p>
                                            <ul>
                                                <li>बुनियादी / Basic</li>
                                                <li>स्टार्टर / Starter</li>
                                                <li>10 संदेश प्राप्त करें / Get 10 Messages</li>
                                                <li>10 लीड प्राप्त करें / Get 10 Leads</li>
                                                <li>10 कॉल प्राप्त करें / Get 10 Calls</li>
                                                <li>10 व्हाट्सएप लीड प्राप्त करें / Get 10 WhatsApp Leads</li>
                                                <li>2 विज्ञापन पोस्ट करें / Post 2 Ads</li>
                                                <li>1,000 लोगों के साथ विज्ञापन इंटरैक्ट करें / 1k People Interact Ads</li>
                                                <a href="<?= base_url('buy-plan.php?plan=2') ?>" class="btn btn-primary ">Pay Now</a>
                                            </ul>
                                        </div>
                                    </div>
                                </div>


                                <div id="premium" class="modal">
                                    <div class="modal-content">
                                        <div class="modal-header">Quarterly Plan
                                            <span class="close" onclick="closeModal('premium')">&times;</span>
                                        </div>
                                        <div class="modal-body">
                                            <p><strong>Price:</strong> ₹499.00</p>
                                            <p><strong>Features:</strong></p>
                                            <ul>
                                                <li>बुनियादी / Basic</li>
                                                <li>स्टार्टर / Starter</li>
                                                <li>50 संदेश प्राप्त करें / Get 50 Messages</li>
                                                <li>50 लीड प्राप्त करें / Get 50 Leads</li>
                                                <li>50 कॉल प्राप्त करें / Get 50 Calls</li>
                                                <li>50 व्हाट्सएप लीड प्राप्त करें / Get 50 WhatsApp Leads</li>
                                                <li>5 विज्ञापन पोस्ट करें / Post 5 Ads</li>
                                                <li>5,000 लोगों के साथ विज्ञापन इंटरैक्ट करें / 5k People Interact Ads</li>
                                                <a href="<?= base_url('buy-plan.php?plan=3') ?>" class="btn btn-primary mt-4">Pay Now</a>
                                            </ul>
                                        </div>
                                    </div>
                                </div>


                                <div id="gold" class="modal">
                                    <div class="modal-content">

                                        <div class="modal-header">Half-Yearly Plan
                                            <span class="close" onclick="closeModal('gold')">&times;</span>
                                        </div>
                                        <div class="modal-body">
                                            <p><strong>Price:</strong> ₹899.00</p>
                                            <p><strong>Features:</strong></p>
                                            <ul>
                                                <li>बुनियादी / Basic</li>
                                                <li>स्टार्टर / Starter</li>
                                                <li>200 संदेश प्राप्त करें / Get 200 Messages</li>
                                                <li>200 लीड प्राप्त करें / Get 200 Leads</li>
                                                <li>200 कॉल प्राप्त करें / Get 200 Calls</li>
                                                <li>200 व्हाट्सएप लीड प्राप्त करें / Get 200 WhatsApp Leads</li>
                                                <li>10 विज्ञापन पोस्ट करें / Post 10 Ads</li>
                                                <li>10,000 लोगों के साथ विज्ञापन इंटरैक्ट करें / 10k People Interact Ads</li>
                                                <a href="<?= base_url('buy-plan.php?plan=4') ?>" class="btn btn-primary  mt-4">Pay Now</a>
                                            </ul>
                                        </div>
                                    </div>
                                </div>


                                <div id="plan" class="modal">
                                    <div class="modal-content">

                                        <div class="modal-header">Yearly Plan
                                            <span class="close" onclick="closeModal('plan')">&times;</span>
                                        </div>
                                        <div class="modal-body">
                                            <p><strong>Price:</strong> ₹1599.00</p>
                                            <p><strong>Features:</strong></p>
                                            <ul>
                                                <li>बुनियादी / Basic</li>
                                                <li>विशेष भूमि रिपोर्ट / Special Land Reports</li>
                                                <li>कस्टम सेवाएँ / Custom Services</li>
                                                <li>सहायता के लिए प्राथमिकता / Priority Support</li>
                                                <li>अनलिमिटेड संदेश प्राप्त करें / Get Unlimited Messages</li>
                                                <li>अनलिमिटेड लीड प्राप्त करें / Get Unlimited Leads</li>
                                                <li>अनलिमिटेड कॉल प्राप्त करें / Get Unlimited Calls</li>
                                                <li>अनलिमिटेड व्हाट्सएप लीड प्राप्त करें / Get Unlimited WhatsApp Leads</li>
                                                <li>अनलिमिटेड विज्ञापन पोस्ट करें / Post Unlimited Ads</li>
                                                <li>अनलिमिटेड लोगों के साथ विज्ञापन इंटरैक्ट करें / Unlimited People Interact Ads</li>
                                                <a href="<?= base_url('buy-plan.php?plan=5') ?>" class="btn btn-primary ">Pay Now</a>
                                            </ul>
                                        </div>
                                    </div>
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
include "../common/footer.php";
?>
<script>
    function openModal(plan) {
        document.getElementById(plan).style.display = "block";
    }

    function closeModal(plan) {
        document.getElementById(plan).style.display = "none";
    }

    window.onclick = function(event) {
        var modals = document.getElementsByClassName("modal");
        for (var i = 0; i < modals.length; i++) {
            if (event.target == modals[i]) {
                modals[i].style.display = "none";
            }
        }
    }
</script>