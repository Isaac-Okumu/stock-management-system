<?php
require_once 'php_action/core.php';

// Remove all session variables
session_unset();

// Destroy session
session_destroy();

// Redirect to login page
header("Location: " . $store_url . "index.php");
exit();
