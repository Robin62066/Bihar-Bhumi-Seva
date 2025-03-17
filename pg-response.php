<?php
require "config/autoload.php";
//Array ( [razorpay_payment_id] => pay_NTYbSBlSWdcdry [razorpay_order_id] => order_NTYWzjkPDW5RY5 [razorpay_signature] => 25b0836e865b055578a6c0050fc576f611f7f668f543d3c4aa7371b275990d28 )
if (isset($_POST['razorpay_payment_id'])) {
    $payment_id = $_POST['razorpay_payment_id'];
    $order_id = $_POST['razorpay_order_id'];
    $razorpay_signature = $_POST['razorpay_signature'];

    $generated_signature = hash_hmac('sha256', $order_id . "|" . $payment_id, RAZOR_KEY_SECRET);

    if ($generated_signature == $razorpay_signature) {
        $item = $db->select("ai_orders", ['rzp_order_id' => $order_id], 1)->row();
        if (is_object($item)) {
            $sb = [];
            $sb['txn_id'] = $payment_id;
            $sb['updated'] = date("Y-m-d H:i:s");
            $sb['status'] = 1;
            $db->update('ai_orders', $sb, ['id' => $item->id]);

            if ($item->notes == 'mutations') {

                // update mutation data
                $sb = [];
                $sb['pay_status'] = 1;
                $sb['pay_date'] = date("Y-m-d H:i:s");
                $db->update('ai_mutations', $sb, ['order_id' => $item->id]);
                redirect('apply-confirm.php');
            } else if ($item->notes == 'mutations-app') {
                $sb = [];
                $sb['pay_status'] = 1;
                $sb['pay_date'] = date("Y-m-d H:i:s");
                $db->update('ai_mutations_app', $sb, ['order_id' => $item->id]);
                redirect('apply-confirm.php');
            } else if ($item->notes == 'membership') {
                $count = 0;
                if ($item->plan_id == 2) {
                    $count = 1;
                } elseif ($item->plan_id == 3) {
                    $count = 3;
                } elseif ($item->plan_id == 4) {
                    $count = 6;
                } elseif ($item->plan_id == 5) {
                    $count = 12;
                }
                // Disable free plan
                $db->update('ai_membership', ['status' => 0], ['user_id' => $user_id, 'plan_id' => 1]);

                // Add New membership
                $sb = [];
                $sb['user_id']  = user_id();
                $sb['plan_id']  = $item->plan_id;
                $sb['start_dt'] = date("Y-m-d");
                $sb['end_dt']   = date("Y-m-d", strtotime("+$count months"));
                $sb['created']  = date("Y-m-d H:i:s");
                $sb['status']   = 1;
                $db->insert("ai_membership", $sb);
                session()->set_flashdata("success", "Your Membership has been upgraded");
                return redirect('dashboard/index.php');
            } else if ($item->notes == 'bhumi-locker') {

                // Add New membership
                $sb = [];
                $sb['user_id'] = user_id();
                $sb['plan_id'] = 3;
                $sb['start_dt'] = date("Y-m-d");
                $sb['end_dt'] = date("Y-m-d", strtotime("+99 years"));
                $sb['created'] = date("Y-m-d H:i:s");
                $sb['status'] = 1;
                $db->insert("ai_membership", $sb);

                // Update payment status
                $sb = [];
                $sb['pay_status'] = 1;
                $sb['pay_mode'] = 'online';
                $sb['pay_date'] = date("Y-m-d");
                $db->update('ai_users', $sb, ['id' => user_id()]);

                session()->set_flashdata("success", "Your Bhumi Locker membership has been upgraded");
                return redirect('dashboard/bhumi-locker.php');
            }
        } else {
            redirect('index.php', 'Invalid order. Try again', 'danger');
        }
    } else {
        $sb = [];
        $sb['updated'] = date("Y-m-d H:i:s");
        $sb['status'] = 2;
        $db->update('ai_orders', $sb, ['id' => $item->id]);

        // update mutation data
        $sb = [];
        $sb['pay_status'] = 2;
        $sb['pay_date'] = date("Y-m-d H:i:s");
        $db->update('ai_mutations', $sb, ['order_id' => $item->id]);

        redirect('apply-confirm.php');
    }
} else {
    redirect('index.php', 'Invalid processing order. Try again', 'danger');
}
