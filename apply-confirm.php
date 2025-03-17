<?php
include "config/autoload.php";
include "common/header.php";
$app_id = session()->app_id;
if ($app_id == null) {
    $app_id = $_GET['order'] ??  null;
    if ($app_id == null) {
        redirect('apply-mutation.php?step=1', "Something wrong, Plese fill the details", 'danger');
    }
}
$app = $db->select('ai_mutations', ['id' => $app_id], 1)->row();
if ($app == null) {
    $app = $db->select('ai_mutations_app', ['id' => $app_id], 1)->row();
    $token = $app->case_no;
} else {
    $token = $app->token;
}
$order = $db->select('ai_orders', ['id' => $app->order_id], 1)->row();
?>
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
    var options = {
        "key": "<?= RAZOR_KEY_ID ?>", // Enter the Key ID generated from the Dashboard
        "amount": "<?= $order->amount * 100; ?>", // Amount is in currency subunits. Default currency is INR. Hence, 50000 refers to 50000 paise
        "currency": "INR",
        "name": "Bihar Bhumi Seva", //your business name
        "description": "Payment for Mutation Services",
        "image": "https://biharbhumiseva.in/assets/front/img/logo.png",
        "order_id": "<?= $order->rzp_order_id; ?>", //This is a sample Order ID. Pass the `id` obtained in the response of Step 1
        "callback_url": "https://biharbhumiseva.in/pg-response.php",
        "prefill": { //We recommend using the prefill parameter to auto-fill customer's contact information especially their phone number
            "name": "<?= $order->cust_name; ?>", //your customer's name
            "email": "<?= $order->cust_email; ?>",
            "contact": "<?= $order->cust_mobile; ?>" //Provide the customer's phone number for better conversion rates 
        },
        "notes": {
            "address": "<?= $app->address; ?>"
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
<div id="origin" class="container">
    <div class="py-3" id="alertdiv">
        <h1 class="h5 mb-4 text-center text-success">Apply for Mutations</h1>
    </div>
    <?php
    include "common/alert.php";
    ?>
    <div class="bg-white rounded-2 p-4 text-center mb-4 shadow">
        <?php
        if ($app->pay_status == 1 || $app->pay_status == 0) {
        ?>
            <img src="assets/front/img/success.png" class="img-fluid" />
        <?php
        } else if ($app->pay_status == 2) {
        ?>
            <img src="assets/front/img/failed.png" class="img-fluid" />
        <?php
        }
        ?>
        <div class="pt-3 text-success mb-5">
            <?php
            if ($app->pay_status == 0) {
            ?>
                <h6 class="text-primary">
                    Your Application has been saved. Please pay to Confirm your Application. <br />
                    Application Case Number: <?= $token; ?>
                </h6>
                <div class="mt-4">
                    <button class="btn btn-primary" id="rzp-button1">Pay <?= $order->amount; ?></button>
                </div>
            <?php
            } else if ($app->pay_status == 1) {
            ?>
                <h6>Your payment has been confirmed. Kindly note your Application Reference Number</h6>
                <div class="mt-4">
                    <span class="bg-success text-white px-4 p-2 rounded-5 fs-4"> <?= $token; ?></span>
                </div>
            <?php
            } else if ($app->pay_status == 2) {
            ?>
                <h6 class="text-danger">Your payment has been failed. Please contact admin if your account has been deducated</h6>
                <div class="mt-4">
                    <span class="bg-danger text-white px-4 p-2 rounded-5 fs-4"> <?= $token; ?></span>
                </div>
            <?php
            }
            ?>
        </div>
    </div>
    <div class="text-center mb-4">
        <a href="index.php" class="btn btn-primary">Go to Home</a>
    </div>
</div>
<?php
include "common/footer.php";
?>