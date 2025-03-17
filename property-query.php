<?php
include "config/autoload.php";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($_POST['submit']) {
        $form = $_POST['form'];
        $form['created'] = date("Y-m-d H:i:s");
        $db->insert("ai_property_enquiry", $form);
        redirect("property-view.php", "Successfully submitted");
    }
} else {
    redirect("property-view.php");
}
