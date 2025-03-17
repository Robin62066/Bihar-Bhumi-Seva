<?php
include "config/autoload.php";
unset($_SESSION['user']);
redirect('login.php');
