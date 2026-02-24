<?php 

session_start();
include "constants.php";
//********************************************/
//validation 
if (empty($_POST['new_password'])){
	echo json_encode([
        "result" => false,
        "msg" => "Password is required."
    ]);
    exit;
}
if (empty($_POST['confirm_new_password'])){
	echo json_encode([
        "result" => false,
        "msg" => "Confirm New Password is required."
    ]);
    exit;
}
if (empty($_POST['mobile_no'])){
	echo json_encode([
        "result" => false,
        "msg" => "Mobile No is required."
    ]);
    exit;
}
if($_POST['new_password'] != $_POST['confirm_new_password']){
    echo json_encode([
        "result" => false,
        "msg" => "New Password And Confirm New Password Mismatched..!"
    ]);
    exit;
}
// Validate password strength
$password = $_POST['new_password'];
$uppercase = preg_match('@[A-Z]@', $password);
$lowercase = preg_match('@[a-z]@', $password);
$number    = preg_match('@[0-9]@', $password);
$specialChars = preg_match('@[^\w]@', $password);
if(!$uppercase || !$lowercase || !$number || !$specialChars || strlen($password) < 8) {
    echo json_encode([
        "result" => false,
        "msg" => "Password should be at least 8 characters in length and should include at least one upper case letter, one number, and one special character....!"
    ]);
    exit;
}
//***************************************************************/
//db connection
$db=CENTRAL_AUTH;
$host        = "host = ".VERIFY_USER_DB_HOST;
$port        = "port = ".VERIFY_USER_DB_PORT;
$dbname      = "dbname = $db" ;
$credentials = "user=postgres password=postgres";
$db = pg_connect("$host $port $dbname $credentials");
if(!$db) {
    echo json_encode([
        "result" => false,
        "msg" => "Error : Database Server Error..!, Please Contact Administrator..!"
    ]);
    exit;
 }
//***************************************************************/
//BCRYPT PASSWORD DURING SAVE USER
//updating central auth table
$options = [
    'cost' => 12,
];
$enc_pass = password_hash($_POST['new_password'], PASSWORD_BCRYPT, $options);
$curl = curl_init();
curl_setopt_array($curl, array(
CURLOPT_URL => PASS_API_UPDATION,
CURLOPT_RETURNTRANSFER => true,
CURLOPT_ENCODING => '',
CURLOPT_MAXREDIRS => 10,
CURLOPT_TIMEOUT => 60,
CURLOPT_SSL_VERIFYHOST => 0,
CURLOPT_SSL_VERIFYPEER => 0,
CURLOPT_FOLLOWLOCATION => true,
CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
CURLOPT_CUSTOMREQUEST => 'POST',
CURLOPT_POSTFIELDS => array(
        'uname'     => $_SESSION['user_name'],
        'cred'      => $enc_pass,
        'dist_code' => $_SESSION['credentials']['dist_code'],
        'mobile'    => $_POST['mobile_no'],
        'dhar_user' => $_SESSION["credentials"]["dharitree"]?$_SESSION["credentials"]["dharitree"]:null,
        'noc_user'  => $_SESSION["credentials"]["noc"]?$_SESSION["credentials"]["noc"]:null
    ),
));
$response = curl_exec($curl);
if($response == false)
   echo 'Curl error: ' . curl_error($curl);
curl_close($curl);
$resp = json_decode($response);
if($resp!=null && $resp[0]->responseType == 2)
{
    echo json_encode([
        "result" => true,
        "msg" => "Data Updation Successfull..!!!"
    ]);
    return;
}
else {
    echo json_encode([
        "result" => false,
        "msg" => $_SESSION['credentials']['dist_code']."Some Error Occured, Please Contact Administrator..!!!! ".$response
    ]);
    return;
}
// $updateData=array(
//     'password_change_flag'=>1,
//     'password' => $enc_pass,
//     'mobile' => $_POST['mobile_no'],
//     'password_change' => date('Y-m-d'),
// );

// if($_SESSION["credentials"]["dharitree"]){
//     $updateQuery=array(
//         'dhar_user'=>$_SESSION['user_name'],
//         'dist_code'=>$_SESSION['dist_code']
//     );
// }else{
//     $updateQuery=array(
//         'noc_user'=>$_SESSION['user_name'],
//         'dist_code'=>$_SESSION['dist_code']
//     );
// }

// $table = 'central_auth';

// // echo $_SESSION['user_name']."-".$enc_pass."-".$_SESSION['credentials']['dist_code']."-".$_POST['mobile_no'];
// // exit;
// if($_SESSION["credentials"]["dharitree"]){
//     $curl = curl_init();
//     curl_setopt_array($curl, array(
//     CURLOPT_URL => PASS_API_UPDATION,
//     CURLOPT_RETURNTRANSFER => true,
//     CURLOPT_ENCODING => '',
//     CURLOPT_MAXREDIRS => 10,
//     CURLOPT_TIMEOUT => 60,
//     CURLOPT_FOLLOWLOCATION => true,
//     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//     CURLOPT_CUSTOMREQUEST => 'POST',
//     CURLOPT_POSTFIELDS => array(
//         'uname'     => $_SESSION['user_name'],
//         'cred'      => $enc_pass,
//         'dist_code' => $_SESSION['credentials']['dist_code'],
//         'mobile'    => $_POST['mobile_no']
//     ),
//     ));

//     $response = curl_exec($curl);
//     curl_close($curl);
//     $resp = json_decode($response);

//     if(isset($resp) && $resp[0]->responseType == 2)
//     {
//         $res = pg_update($db, $table, $updateData, $updateQuery);

//         if(!$res){
//             echo json_encode([
//                 "result" => false,
//                 "msg" => "Some Error Occured, Please Contact Administrator..!"
//             ]);
//             return;
//         }else if(isset($res) && $res === true){
//             echo json_encode([
//                 "result" => true,
//                 "msg" => "Data Updation Successfull..!!!"
//             ]);
//         }
//     }
//     else {
//         echo json_encode([
//             "result" => false,
//             "msg" => "Some Error Occured, Please Contact Administrator..!"
//         ]);
//         return;
//     }
// }else{
//     $res = pg_update($db, $table, $updateData, $updateQuery);
//     if(!$res){
//         echo json_encode([
//             "result" => false,
//             "msg" => "Some Error Occured, Please Contact Administrator..!"
//         ]);
//         return;
//     }else if(isset($res) && $res === true){
//         echo json_encode([
//             "result" => true,
//             "msg" => "Data Updation Successfull..!!!"
//         ]);
//     }
// }
?>
