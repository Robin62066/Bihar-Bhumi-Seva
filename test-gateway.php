<?php

use Razorpay\Api\Api;

include "config/autoload.php";
$api = new Api(RAZOR_KEY_ID, RAZOR_KEY_SECRET);
$order = null;
if (isset($_POST['btn'])) {
    $amount = $_POST['amount'];

    $sb = [];
    $sb['created'] = date("Y-m-d H:i:s");
    $sb['amount'] = $amount;
    $sb['status'] = 0;
    $sb['receipt_id'] = $receipt_id = 'bs-' . time();
    $db->insert("ai_orders", $sb);
    $id = $db->id();


    $item = $api->order->create(['receipt' => $receipt_id, 'amount' => $amount, 'currency' => 'INR', 'notes' => array('key1' => 'value3')]);
    if ($item->status == 'created') {
        $rzp_id = $item->id;
        $db->update('ai_orders', ['rzp_order_id' => $rzp_id], ['id' => $id]);
        $sb['rzp_order_id'] = $rzp_id;
    }
    $order = $sb;
}
include "common/header.php";
?>
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<div class="container py-4">
    <div class="page-header">
        <h5>About us</h5>
    </div>

    <div class="bg-white p-4">
        <div class="row">
            <div class="col-sm-6">
                <?php
                if (is_array($order)) {
                ?>

                    <script>
                        var options = {
                            "key": "<?= RAZOR_KEY_ID ?>", // Enter the Key ID generated from the Dashboard
                            "amount": "<?= $order['amount'] * 1000; ?>", // Amount is in currency subunits. Default currency is INR. Hence, 50000 refers to 50000 paise
                            "currency": "INR",
                            "name": "Bihar Bhumi Seva", //your business name
                            "description": "Payment for Mutation Services",
                            "image": "https://biharbhumiseva.in/assets/front/img/logo.png",
                            "order_id": "<?= $order['rzp_order_id']; ?>", //This is a sample Order ID. Pass the `id` obtained in the response of Step 1
                            "callback_url": "https://eneqd3r9zrjok.x.pipedream.net/",
                            "prefill": { //We recommend using the prefill parameter to auto-fill customer's contact information especially their phone number
                                "name": "Gaurav Kumar", //your customer's name
                                "email": "gaurav.kumar@example.com",
                                "contact": "9000090000" //Provide the customer's phone number for better conversion rates 
                            },
                            "notes": {
                                "address": "Razorpay Corporate Office"
                            },
                            "theme": {
                                "color": "#3399cc"
                            }
                        };
                        var rzp1 = new Razorpay(options);
                        window.onload = function() {
                            document.getElementById('rzp-button1').onclick = function(e) {
                                rzp1.open();
                                e.preventDefault();
                            }
                        }
                    </script>
                    <div class="card">
                        <div class="card-header">Payment Gateway</div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label>Payment Amount <?= $order['amount']; ?></label>
                            </div>
                            <button class="btn btn-primary" id="rzp-button1">Pay</button>
                        </div>
                    </div>
                <?php
                } else {
                ?>
                    <div class="card">
                        <div class="card-header">Payment Gateway</div>
                        <div class="card-body">
                            <form action="" method="post">
                                <div class="mb-3">
                                    <label>Enter Amount</label>
                                    <input type="text" name="amount" class="form-control">
                                </div>
                                <button name="btn" value="Send" class="btn btn-primary">SEND</button>
                            </form>
                        </div>
                    </div>
                <?php
                }
                ?>

            </div>
        </div>
    </div>
</div>
<?php
include "common/footer.php";
