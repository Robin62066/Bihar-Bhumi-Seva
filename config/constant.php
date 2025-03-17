<?php
if ($site_live) {
    define('HOSTNAME', 'localhost');
    define('USERNAME', 'biharohi_db');
    define('PASSWORD', 'xqBIFTaM+TNh');
    define('DATABASE', 'biharohi_db');
} else {
    define('HOSTNAME', 'localhost');
    define('USERNAME', 'root');
    define('PASSWORD', '');
    define('DATABASE', 'biharohi_db');
}

define('KYC_PENDING', 0);
define('KYC_PROCESSING', 2);
define('KYC_APPROVED', 1);
define('KYC_REJECTED', 3);

define('USER_CUSTOMER', 1);
define('USER_LAND_OWNER', 2);
define('USER_BROKER', 3);
define('USER_MUNSI', 4);
define('USER_AMIN', 5);
define('USER_CO', 6);
define('USER_SDO', 7);
define('USER_BHUMI_LOCKER', 8);
define('USER_LABOUR', 9);
define('USER_BRICKS_MFG', 10);
define('USER_SAND_SUPPLIER', 11);
define('USER_BUILDER_CONSTRUCTON',12);

define('Customer', 1);
define('Land Owner', 2);
define('Broker/Builder', 3);

define('RAZOR_MERCHANT_ID', 'NMh6aFaEU7AUgW');

// Live mode
define('RAZOR_KEY_ID', 'rzp_live_xRvHEVPtHfTwMn');
define('RAZOR_KEY_SECRET', 'fhzqMNVuGRIh8tlpM05Dd5mo');

// Test mode
// define('RAZOR_KEY_ID', 'rzp_test_txk6WTt0DwgsR6');
// define('RAZOR_KEY_SECRET', '5HFkOSfUQJJCWZwbvhQyWSTK');
