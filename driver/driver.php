<?php
session_start();
require_once '../config/config.php'; 
require_once '../login/verify_authentication.php';

// VERIFY AUTHORIZATION
verify_login('driver', '../index.php');
?>