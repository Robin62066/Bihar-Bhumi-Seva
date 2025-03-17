<?php
include "config/autoload.php";
$mid  = session()->_order_id;

$order = $db->select('ai_orders', ['id' => $mid])->row();
$user = $db->select('ai_users', ['id' => user_id()])->row();

include "common/header.php";
?>
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
    var options = {
        "key": "<?= RAZOR_KEY_ID ?>", // Enter the Key ID generated from the Dashboard
        "amount": "<?= $order->amount * 100; ?>", // Amount is in currency subunits. Default currency is INR. Hence, 50000 refers to 50000 paise
        "currency": "INR",
        "name": "Bihar Bhumi Seva", //your business name
        "description": "Payment for Membership Services",
        "image": "https://biharbhumiseva.in/assets/front/img/logo.png",
        "order_id": "<?= $order->rzp_order_id; ?>", //This is a sample Order ID. Pass the `id` obtained in the response of Step 1
        "callback_url": "https://biharbhumiseva.in/pg-response.php",
        "prefill": { //We recommend using the prefill parameter to auto-fill customer's contact information especially their phone number
            "name": "<?= $order->cust_name; ?>", //your customer's name
            "email": "<?= $order->cust_email; ?>",
            "contact": "<?= $order->cust_mobile; ?>" //Provide the customer's phone number for better conversion rates 
        },
        "notes": {
            "address": "<?= $user->address; ?>"
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
<div class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-sm-5 m-auto">
                <div class="card">
                    <div class="card-header">Pay Online</div>
                    <div class="card-body">
                        <div class="mb-2">
                            <label>Receipt Id</label>
                            <div class="form-control bg-light"><?= $order->receipt_id; ?></div>
                        </div>
                        <div class="mb-2">
                            <label>Plan Amount</label>
                            <div class="form-control bg-light"><?= $order->base_amount; ?></div>
                        </div>
                        <div class="mb-2">
                            <label>GST(18%)</label>
                            <div class="form-control bg-light"><?= $order->gst; ?></div>
                        </div>
                        <div class="mb-2">
                            <label>Amount to Pay</label>
                            <div class="form-control bg-light"><?= $order->amount; ?></div>
                        </div>
                        <button class="btn btn-primary" id="rzp-button1">Pay <?= $order->amount; ?></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
include "common/footer.php";
