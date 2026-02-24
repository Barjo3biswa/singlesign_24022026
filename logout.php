<?php
session_start();
session_unset(); 
session_destroy(); 
include "constants.php";
header("Location: https://".IP_HOST ."/". SINGLESIGN." ");
exit;
?>