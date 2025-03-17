<?php
header('Content-Type: application/json');
include "config/autoload.php";
sendSMS("Kamal Kr", "225544", "9334628120", "usermsg");
die;

/*
Abhimanyu
Singh
Dsips8236k
17/11/1992
sabhimanyu27@gmail.com
8433812226
Sunita pack house Krishna Puri warisaliganj ps warisaliganj Nawada.805130
*/
$first_name = "ABHIMANYU VINAY";
$last_name = "SINGH";
$pan_number = "DSIPS8236K";
$dob  = "1992-11-17";
$email = "sabhimanyu27@gmail.com";
$mobile = "8433812226";
$shop_name = "Biharbhumi Sewa";

$eko = new Eko();
$address = $eko->ekoFormattedAddress('Sunita pack house Krishna Puri', 'Nawada', 'Bihar', '805130');
$resp = $eko->onboard($first_name, $last_name, $pan_number, $dob, $email, $mobile, $shop_name, $address);
// $resp = $eko->activateService(34922001, 4); // service code = 4 for pan
$resp = $eko->verify_pan($pan_number);
// $resp = $eko->aadharInit('401925585375', 'KAMAL KUMAR');
/*
stdClass Object
(
    [response_status_id] => 0
    [data] => stdClass Object
        (
            [access_key_validity] => 1704540971
            [access_key] => 6bb5b4e6-bdd8-42b0-8fd7-76de4c4944e9
            [aadhar] => 
            [message] => Consent Accepted
        )

    [response_type_id] => 1617
    [message] => Karza Aadhaar Consent Signed
    [status] => 0
)
*/
// $resp = $eko->getAadharOTP('401925585375', '93faea37-0ec7-4a57-b513-d87fc5717c44');
/*
stdClass Object
(
    [response_status_id] => 0
    [data] => stdClass Object
        (
            [access_key_validity] => 1704540971
            [access_key] => 6bb5b4e6-bdd8-42b0-8fd7-76de4c4944e9
            [message] => OTP sent to registered mobile number
        )

    [response_type_id] => 1619
    [message] => Karza Aadhaar Otp Sent
    [status] => 0
)
*/

// $resp = $eko->getAadharDetails('401925585375', '93faea37-0ec7-4a57-b513-d87fc5717c44', '548554');
/*
stdClass Object
(
    [response_status_id] => 0
    [data] => stdClass Object
        (
            [fatherName] => 
            [gender] => M
            [dob] => 1986-02-14
            [name] => Kamal Kumar
            [generatedDateTime] => 2023-12-07 17:23:26.524
            [maskedAadhaarNumber] => XXXX XXXX 5375
            [combinedAddress] => Plot - 612, Jagriti Nagar, Dugdugia Toli, Ranchi, Pundag, Deepatoli, Pundag, Jharkhand, India, 834004
        )

    [response_type_id] => 1621
    [message] => Aadhaar File Downloaded
    [status] => 0
)
*/
// $resp = sendSMS("Kamal Kr", "1234", "9334628120", "signup");
print_r($resp);
