<?php

use Razorpay\Api\Api;

include "config/autoload.php";
$planId = $_GET['plan'] ??  1;
if (!is_login()) {
    session()->set('_plan_id', $planId);
    redirect('login.php', 'You must login to continue');
}
$from = $_GET['from'] ?? null;
$user_id = user_id();
$user = $db->select('ai_users', ['id' => $user_id], 1)->row();

if ($planId == 1) {
    $plan = $db->select('ai_membership', ['user_id' => $user_id, 'plan_id' => 1])->row();
    if (is_object($plan)) {
        if ($plan->status == 1) {
            session()->set_flashdata('danger', "You have already FREE membership plan active.");
            redirect('index.php');
        } else {
            session()->set_flashdata('danger', "Your membship has been expired. Upgrade now to enjoy.");
            redirect('buy-plan.php?plan=2');
        }
    }
    $sb = [];
    $sb['user_id'] = user_id();
    $sb['plan_id'] = 1;
    $sb['start_dt'] = date("Y-m-d");
    $sb['end_dt'] = date("Y-m-d", strtotime("+1 months"));
    $sb['created'] = date("Y-m-d H:i:s");
    $sb['status'] = 1;
    $db->insert("ai_membership", $sb);
    session()->set_flashdata("success", "Your FREE membership has been activated");
    if ($from == 'sell') {
        return redirect('sell-property.php?type=free');
    } else {
        return redirect('index.php');
    }
} else if ($planId > 1) {

    if ($planId == 2) {
        // Update payment order;
        $baseAmount = 199; // 18% GST
    } elseif ($planId == 3) {
        $baseAmount = 499; // 18% GST
    } elseif ($planId == 4) {
        $baseAmount = 899; // 18% GST

    } elseif ($planId == 5) {
        $baseAmount = 1599; // 18% GST
    }

    $api = new Api(RAZOR_KEY_ID, RAZOR_KEY_SECRET);
    $sb = [];
    $sb['created'] = date("Y-m-d H:i:s");
    $sb['base_amount'] = $baseAmount;
    $sb['gst'] = $baseAmount * 0;
    $sb['amount'] = $amount = $baseAmount * 1; // Inclusive of GST
    $sb['status'] = 0;
    $sb['receipt_id'] = $receipt_id = 'mb-' . str_pad(time(), 8, '0', STR_PAD_LEFT);
    $sb['cust_name'] = $user->first_name . ' ' . $user->last_name;
    $sb['cust_mobile'] = $user->mobile_number;
    $sb['cust_email'] = $user->email_id;
    $sb['notes'] = 'membership';
    $sb['plan_id'] = $planId;
    $sb['user_id'] = $user_id;
    $db->insert("ai_orders", $sb);
    $id = $db->id();
    $item = $api->order->create(['receipt' => $receipt_id, 'amount' => $amount * 100, 'currency' => 'INR', 'notes' => array('user_id' => $user_id)]);
    if ($item->status == 'created') {
        $rzp_id = $item->id;
        $db->update('ai_orders', ['rzp_order_id' => $rzp_id], ['id' => $id]);
        session()->set('_order_id', $id);
        redirect("pay-now.php");
    } else {
        echo "Unable to Initiate Payment. Go Back and Try again";
    }
}
