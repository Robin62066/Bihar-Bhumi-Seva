<?php

$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://staging.eko.in:25004/ekoapi/v2/banks/ifsc:SBIN0003451/accounts/20032251765',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'PUT',
    CURLOPT_POSTFIELDS => 'initiator_id=9962981729&customer_id=7661109875&client_ref_id=AVS20181123194719311&user_code=20810200',
    CURLOPT_HTTPHEADER => array(
        'developer_key: becbbce45f79c6f5109f848acd540567',
        'secret-key: MC6dKW278tBef+AuqL/5rW2K3WgOegF0ZHLW/FriZQw=',
        'secret-key-timestamp: 1516705204593',
        'Content-Type: application/x-www-form-urlencoded'
    ),
));

$response = curl_exec($curl);
var_dump($response);

curl_close($curl);
echo $response;
