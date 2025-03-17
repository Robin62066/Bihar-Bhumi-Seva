<?php
include "config/autoload.php";
if (!is_login()) redirect(base_url('login.php'), 'Please login to continue');
$user_id = user_id();
$paidPlan = $db->select("ai_membership", ['user_id' => $user_id, 'plan_id' => 2], 1)->row();
if (is_object($paidPlan) && $paidPlan->status == 1) {
    redirect("sell-property.php?type=paid");
}
$freePlan = $db->select("ai_membership", ['user_id' => $user_id, 'plan_id' => 1], 1)->row();
$msg = '';
$isActive = false;
if (is_object($freePlan) && $freePlan->status == 1) {
    $isActive = true;
}
$user = $db->select('ai_users', ['id' => $user_id], 1)->row();
if ($user->kyc_status == 0) {
    if ($user->pan_verified == 0) {
        redirect(site_url('pan-verification.php'), "You must Verify your KYC to add Property", "danger");
    } else if ($user->aadhar_verified == 0) {
        redirect(site_url('aadhar-verification.php'), "You must Verify your Aadhar Number to add Property", "danger");
    }
}

include "common/header.php";
?>
<style>
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
</style>
<div class="container py-4">
    <div class="row">
        <div class="col-sm-8 m-auto">
            <?php
            if ($msg != '') {
            ?>
                <div class="alert alert-success d-flex justify-content-between">
                    <span><?= $msg; ?></span>

                </div>
            <?php
            }
            ?>
        </div>
    </div>
    <div class="clearfix position-relative">
        <div class="text-center mb-4">
            <h1 class="h3">Choose your Membership Plan</h1>
        </div>
        <!-- <div class="text-center mb-4">All Plan FREE for First 30 Days</div> -->

        <div class="row">
            <div class="col-sm-8 m-auto">
                <?= front_view("common/alert"); ?>
                <div class="row">
                    <div class="col-6">
                        <div class="card p-4 border-0 shadow-sm">
                            <div class="text-center">
                                <h5><b>Basic</b></h5>
                                <div>Starter</div>
                                <h2 class="h5">Basic Free</h2>
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
                                <?php
                                if ($isActive) {
                                ?>
                                    <div>
                                        <a href="sell-property.php?type=free" class="btn btn-info">Continue with FREE</a>
                                    </div>
                                    <span class="text-muted">Current Active Plan</span>
                                <?php
                                } else {
                                ?>
                                    <a href="<?= base_url('buy-plan.php?plan=1&from=sell') ?>" class="btn btn-primary">Choose Plan</a>
                                <?php
                                }
                                ?>

                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="card p-4 border-0 shadow-sm overflow-hidden">
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
                                <a href="<?= base_url('buy-plan.php?plan=2&from=sell') ?>" class="btn btn-primary">Choose Plan</a>
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
</div>
<?php
include "common/footer.php";
