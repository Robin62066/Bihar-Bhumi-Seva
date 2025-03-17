<?php
session_start();
$site_live = false;
$config = [];

if ($site_live) {
    error_reporting(E_ERROR);
    ini_set('display_errors', '0');
    $config['base_url'] = 'https://www.biharbhumiseva.in/';
} else {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
    $config['base_url'] = 'http://localhost/biharbhumi/';
}
$config['admin_folder'] = 'admin';
$config['upload_folder'] = 'assets/uploads';

define('GOOGLE_API_KEY', 'AIzaSyB5ebdW5ZYc8Q2m9qQOJcRl1fG_bMZcpvM');
