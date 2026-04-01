<?php
session_start();
include "constants.php";
include "activity_logger.php";
require_once __DIR__ . '/CentralAuthPasswordManager.php';
$pdo = new PDO("pgsql:host=" . VERIFY_USER_DB_HOST . ";port=" . VERIFY_USER_DB_PORT . ";dbname=" . CENTRAL_AUTH, "postgres", "postgres");
$pm = new CentralAuthPasswordManager($pdo);
if (empty($_POST['user_name'])) {
    echo json_encode([
        "result" => false,
        "msg" => "User name not found..!"
    ]);
    exit;
}
if (empty($_POST['fp_new_password'])) {
    echo json_encode([
        "result" => false,
        "msg" => "Please Enter The New Password..!"
    ]);
    exit;
}
if (empty($_POST['fp_confirm_new_password'])) {
    echo json_encode([
        "result" => false,
        "msg" => "Please Enter The Confirm New Password..!"
    ]);
    exit;
}
if (empty($_POST['fpcaptcha'])) {
    echo json_encode([
        "result" => false,
        "msg" => "Please Enter The Captcha..!"
    ]);
    exit;
}
if (trim($_POST['fpcaptcha']) != trim($_SESSION['my_captcha'])) {
    logMessage("CAPTCHA###".$_POST['fpcaptcha'] ."###SESSION###".$_SESSION['my_captcha']."#####UNAME###".$_POST['user_name']);
    $errors['captcha'] = " ";
    echo json_encode([
        "result" => false,
        "msg" => "Captcha Mismatched, Please Enter The Captcha Correctly..!"
    ]);
    exit;
}
if ($_POST['user_name'] != $_SESSION['user_name']) {
    echo json_encode([
        "result" => false,
        "msg" => "User name not matched..!"
    ]);
    exit;
}
//**************************************************************/
$options = [
    'cost' => 12,
];
$enc_pass = password_hash($_POST['fp_new_password'], PASSWORD_BCRYPT, $options);
// echo "uname- : ".$_POST['user_name']."-enc_pass-".$enc_pass. 
//      "-dist_code-".$_SESSION['credentials']['dist_code']."-mobile-".
//      $_SESSION['credentials']['mobile']."-dhar_user-".$_SESSION["credentials"]["dharitree"].
//     "-noc_user-".$_SESSION["credentials"]["noc"];        
$curl = curl_init();
curl_setopt_array($curl, array(
    CURLOPT_URL => PASS_API_UPDATION."/updateUserPassword",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 60,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => array(
        'uname'     => $_POST['user_name'],
        'cred'      => $enc_pass,
        'dist_code' => $_SESSION['credentials']['dist_code'],
        'mobile'    => $_SESSION['credentials']['mobile'],
        'dhar_user' => $_SESSION["credentials"]["dharitree"] ? $_SESSION["credentials"]["dharitree"] : null,
        'noc_user'  => $_SESSION["credentials"]["noc"] ? $_SESSION["credentials"]["noc"] : null
    ),
));

$response = curl_exec($curl);
curl_close($curl);
$resp = json_decode($response);
// var_dump($resp);
if ($resp != null && $resp[0]->responseType == 2) {
    log_request_activity(
        $_SESSION['credentials']['dist_code'], 
        'central_auth', 
        [
            'dhar_user' => $_SESSION['credentials']['username'],
            'noc_user'  => $_SESSION['credentials']['username']
        ],
        $_SESSION['credentials']['username']  ,
        'Password-changed'  
    );
    echo json_encode([
        "result" => true,
        "msg" => "Password Updated Successfully..!!!"
    ]);
    exit;
} else {
    echo json_encode([
        "result" => false,
        "msg" => "Some Error Occured, Please Contact Administrator..!"
    ]);
    exit;
}
function logMessage($message)
{
    $timestamp = date('Ymd');
    $logFile=LOG_FILE.$timestamp.".log";
    file_put_contents($logFile, "$timestamp $message" . PHP_EOL, FILE_APPEND);
}

