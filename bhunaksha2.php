<?php
session_start();
include "constants.php";
require_once __DIR__ .'/vendor/autoload.php';
use Firebase\JWT\JWT;
if (!isset($_SESSION['credentials'])) {
    die('Session credentials not found or logged out');
}
/////////////////AUTHENTICATE////////////////////////////////////
//$host        = "host = " . VERIFY_USER_DB_HOST;
$port        = "port = ". VERIFY_USER_DB_PORT;
$credentials = "user=postgres password=postgres";
$dbname      = "dbname = ".$_SESSION['credentials']['dbname'];
$dist_name_check = $_SESSION['credentials']['dbname'];
if(in_array($dist_name_check, DIST_ARRAY_1))
{
        $host = "host = ".VERIFY_USER_DB_HOST_1;
        $link = 'bhunaksha2/';
}
elseif(in_array($dist_name_check, DIST_ARRAY_2))
{
        $host = "host = ".VERIFY_USER_DB_HOST_2;
        $link = '';
}
else{
        $host = "host = ".VERIFY_USER_DB_HOST;
        $link = '';
}
$db = pg_connect("$host $port $dbname $credentials");
$user=$_SESSION['credentials']['username'];
$user_code=$_SESSION['credentials']['code'];
$dist_code=$_SESSION['credentials']['dist_code'];
$subdiv_code=$_SESSION['credentials']['subdiv_code'];
$cir_code=$_SESSION['credentials']['cir_code'];
$mouza_pargona_code=$_SESSION['credentials']['mouza_pargona_code'];
$lot_no=$_SESSION['credentials']['lot_no'];
if($lot_no=='00'){
    $query="SELECT lg.user_code,u.username as name FROM loginuser_table lg
    join users u on u.user_code=lg.user_code and lg.dist_code=u.dist_code and lg.subdiv_code=u.subdiv_code and lg.cir_code=u.cir_code where lg.use_name='$user' and lg.dis_enb_option='E' and lg.dist_code='$dist_code' and lg.subdiv_code='$subdiv_code' and lg.cir_code='$cir_code' ";
}else
{
    $query="SELECT lg.user_code,u.lm_name as name FROM loginuser_table lg
    join lm_code u on u.lm_code=lg.user_code and lg.dist_code=u.dist_code and lg.subdiv_code=u.subdiv_code and lg.cir_code=u.cir_code and lg.mouza_pargona_code=u.mouza_pargona_code and lg.lot_no=u.lot_no where lg.use_name='$user' and lg.dis_enb_option='E' and lg.dist_code='$dist_code' and lg.subdiv_code='$subdiv_code' and lg.cir_code='$cir_code' and lg.mouza_pargona_code='$mouza_pargona_code' and lg.lot_no='$lot_no' ";
}
$result = pg_query($db,$query);
$row = pg_fetch_row($result);
logMessage("CHECK-QUERY###".$query);
logMessage("RESULT###".json_encode($row));
pg_close($db);
if(!$row){
    echo json_encode(['resonponseType'=>2, 'status'=>'USER MIGHT BE DISABLED. KINDLY CHECK THE USER PERMISSION']);
    return;
}
$key = JWT_BHUNAKSHA_SECRET_KEY;
try {
    $levels = $_SESSION['credentials']['dist_code'].",".$_SESSION['credentials']['subdiv_code'];
    $prefix = strtoupper(substr($row[0], 0, 1)); // First character in uppercase
    if ($prefix === 'M') {
        $role = 'LRA';
    } elseif ($prefix === 'S') {
        $role = 'LRS';
    } elseif ($prefix === 'C') {
        $role = 'CO';
    } else {
        $role = 'NA';
    }
    $payload = [
	    "user_code"  => $user_code,
            "name"   => $row[1],
            "user_desig_code"  => $role,
            "use_name"  => $_SESSION['credentials']['username'],
            "dist_code"  => $dist_code,
            "subdiv_code"  => $subdiv_code,
            "cir_code"  => $cir_code,
            "mouza_pargona_code"  => $mouza_pargona_code,
            "lot_no"  => $lot_no,
            "logged_in"  => true,
            "exp"  => time()+300
    ];
    $jwt = JWT::encode($payload, $key);
    logMessage('BHUNAKSHA-USER##########'.$jwt);
    // https://bhunaksha.assam.gov.in/viewMap?token=
    $bhunakshaUrl = BHUNAKSHA_URL ;
    $url = $bhunakshaUrl.$jwt;
    Redirect($url, false);
} catch (Exception $e) {
    die("Token Generation Failed: " . $e->getMessage());
}
function Redirect($url, $permanent = false)
{
    if (headers_sent() === false)
    {
        header('Location: ' . $url, true, ($permanent === true) ? 301 : 302);
        die();
    }
}
function logMessage($message)
{
    $timestamp = date('Ymd');
    $logFile=LOG_FILE.$timestamp.".log";
    file_put_contents($logFile, "$timestamp $message" . PHP_EOL, FILE_APPEND);
}
function get_client_ip()
{
        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP']))
          $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
          $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if (isset($_SERVER['HTTP_X_FORWARDED']))
          $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if (isset($_SERVER['HTTP_FORWARDED_FOR']))
          $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if (isset($_SERVER['HTTP_FORWARDED']))
          $ipaddress = $_SERVER['HTTP_FORWARDED'];
        else if (isset($_SERVER['REMOTE_ADDR']))
          $ipaddress = $_SERVER['REMOTE_ADDR'];
        else
          $ipaddress = 'UNKNOWN';
        return $ipaddress;
}


