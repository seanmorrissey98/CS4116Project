<?php

// Initialize the session
session_start();

if (isset($_SESSION['advanced_search_result'])) {
    unset($_SESSION['advanced_search_result']);
}

// Return to discover page
header("location: discover.php");

?>