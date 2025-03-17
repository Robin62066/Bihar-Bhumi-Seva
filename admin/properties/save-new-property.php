<?php

use function PHPSTORM_META\type;

include "../../config/autoload.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $title = isset($_POST['title']) ? $_POST['title'] : '';
    $property_type = isset($_POST['property_type']) ? $_POST['property_type'] : '';
    $address = isset($_POST['address']) ? $_POST['address'] : '';
    $cost = isset($_POST['cost']) ? $_POST['cost'] : '';
    $description = isset($_POST['description']) ? $_POST['description'] : '';
    $owner = isset($_POST['owner']) ? $_POST['owner'] : '';
    $status = isset($_POST['status']) ? $_POST['status'] : '';
    $property_for = isset($_POST['property_for']) ? $_POST['property_for'] : '';


    // Insert into the users table
    $user = [

        'title'    => $title,
        'property_type'     => $property_type,
        'property_for'     => $property_for,
        'address'     => $address,
        'cost' => $cost,
        'description' => $description,
        'owner' => $owner,
        'status' => $status,

    ];

    if (isset($_FILES['image']['name']) && $_FILES['image']['name'] != '') {
        $user['image'] = do_upload('image');
    }

    if ($db->insert('ai_properties', $user)) {
        $id = $db->id();
        redirect('buy-sell-property.php', "Property saved Sucessfully", "success");
    } else {

        redirect('buy-sell-property.php');
    }
} else {

    redirect('buy-sell-property.php');
}
