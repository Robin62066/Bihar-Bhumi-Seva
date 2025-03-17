<?php

use Razorpay\Api\Api;

include "../config/autoload.php";
if (!is_login()) redirect('login.php', 'You must login to continue');

$user_id = user_id();
$user = $db->select('ai_users', ['id' => $user_id], 1)->row();

// Update payment order;
$baseAmount = 500; // 18% GST
$api = new Api(RAZOR_KEY_ID, RAZOR_KEY_SECRET);
$sb = [];
$sb['created'] = date("Y-m-d H:i:s");
$sb['base_amount'] = $baseAmount;
$sb['gst'] = 0; // $baseAmount * 0.18;
$sb['amount'] = $amount = $baseAmount; // = $baseAmount * 1.18; // Inclusive of GST
$sb['status'] = 0;
$sb['receipt_id'] = $receipt_id = 'mb-' . str_pad(time(), 8, '0', STR_PAD_LEFT);
$sb['cust_name'] = $user->first_name . ' ' . $user->last_name;
$sb['cust_mobile'] = $user->mobile_number;
$sb['cust_email'] = $user->email_id;
$sb['notes'] = 'bhumi-locker';
$sb['user_id'] = $user_id;
$db->insert("ai_orders", $sb);
$id = $db->id();

$amountPaisa = $amount * 100;

$item = $api->order->create(['receipt' => $receipt_id, 'amount' => $amountPaisa, 'currency' => 'INR', 'notes' => array('user_id' => $user_id)]);
if ($item->status == 'created') {
    $rzp_id = $item->id;
    $db->update('ai_orders', ['rzp_order_id' => $rzp_id], ['id' => $id]);
    session()->set('_order_id', $id);
    redirect(base_url("pay-now.php"));
} else {
    echo "Unable to Initiate Payment. Go Back and Try again";
}
