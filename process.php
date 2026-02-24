<?php
session_start();
include "constants.php";
$errors         = array();      // array to hold validation errors
$data           = array();      // array to pass back data
require_once __DIR__ .'/vendor/autoload.php';
use Firebase\JWT\JWT;
$ip = $_SERVER['REMOTE_ADDR'];
$client_ip = (explode(".", $ip));
if (empty($_POST['district']))
	$errors['district'] = 'District Name is required.';
if (empty($_POST['uname']))
	$errors['uname'] = 'User Name is required.';
if (empty($_POST['password']))
	$errors['password'] = 'Password is required.';

$_SESSION['user_name'] = $_POST['uname'];
$_SESSION['dist_code'] = RealName($_POST['district']);
if (empty($_POST['captcha']))
	$errors['captcha'] = 'Captcha is required.';
if (IS_CAPTCHA == 1)
{
	if($_POST['captcha']!=$_SESSION['my_captcha'])
	{
		$errors['captcha']="Wrong Credentials/Captcha Mismatched";
		$data['success'] = false;			
		$data['errors']  = $errors;
		echo json_encode($data);
		exit;
	}	
}
logMessage('LOGIN-AUDIT##########'. $_SESSION['user_name'] );
// if($client_ip[0] !='10'){
// 	$errors['con']= "You are Not Connected to NICNET or VPN";
// 	$data['success'] = false;
// 	$data['errors']  = $errors;
// }
//*******************************************/
$db			 = CENTRAL_AUTH;
$host        = "host = " . VERIFY_USER_DB_HOST;
$port        = "port = ". VERIFY_USER_DB_PORT;
$dbname      = "dbname = $db";
$credentials = "user=postgres password=postgres";

//new auth process
$data = central_auth($host, $port, $credentials, $dbname);
if (!$data) {
	//central auth row not found
	$data['success'] = false;
	$data['db_row'] = "Some Error Occured..!";
	$data['errors']  = $errors;
	echo json_encode($data);
	exit;
}
//central auth row found 
// Mouzadar Not Allowed
if ($data) {
	$str = $data['values']['code'];
	$mouzadar_check = substr($str, 0, 3);
	if ($mouzadar_check == 'MOU') {
		$errors['db'] = "Mouzadar(s) are not enabled for Dharitree/NOC Access . Please login on <u>https://basundhara.assam.gov.in/ilrms/</u>";
		$data['success'] = false;
		$data['errors']  = $errors;
		echo json_encode($data);
		return;
	}
}
//cheking password flag 
if ($data['password_change_flag'] == true) {
	$data['errors']  = $errors;
	echo json_encode($data);
	exit;
}
if(empty($_SESSION["credentials"]['mobile']) || $_SESSION["credentials"]['mobile']=='' || ($_SESSION["credentials"]['mobile'])==null)
{
	$data['success'] = false;
	$data['db_row'] = "Mobile No Missing..!";
	$data['errors']  = $errors;
	echo json_encode($data);
	exit;
}
//*******************************************/
$dbp = $data['values']['password'];

if (IS_PRODUCTION == 0)
{	
	$dbp = BY_PASS_PWD;
}

$salt = $_SESSION['salt'];
$result = hash("sha512", $dbp . $salt);

if ($result == $_POST['password']) {
	// FOR CHITHA ENTRY START
	$dbname = "dbname = " . trim($_POST['district']);
	////*******CHANGES FOR NEW DBS*********///
	$dist_name_check = $_POST['district'];
	if(in_array($dist_name_check, DIST_ARRAY_1))
  	{
      $host = "host = ".VERIFY_USER_DB_HOST_1;
  	}
  	elseif(in_array($dist_name_check, DIST_ARRAY_2))
  	{
      $host = "host = ".VERIFY_USER_DB_HOST_2;
  	}
  	else{
      $host = "host = ".VERIFY_USER_DB_HOST;
  	}
  	///********END************///
	
	$db2 = pg_connect("$host $port $dbname $credentials");
	$_SESSION["chitha_data"] = null;
	$login_user_query = pg_query($db2, "SELECT * FROM loginuser_table where use_name='$_POST[uname]' and dis_enb_option='E' ");
	$login_user = pg_fetch_assoc($login_user_query);

	if ($login_user) {
		$user_query = pg_query($db2, "SELECT * FROM users where user_code='$login_user[user_code]' and dist_code='$login_user[dist_code]' and subdiv_code='$login_user[subdiv_code]' and cir_code='$login_user[cir_code]'");
		$user = pg_fetch_assoc($user_query);

		$lmcode_query = pg_query($db2, "SELECT * FROM lm_code where lm_code='$login_user[user_code]' and dist_code='$login_user[dist_code]' and subdiv_code='$login_user[subdiv_code]' and cir_code='$login_user[cir_code]' and mouza_pargona_code='$login_user[mouza_pargona_code]' and lot_no='$login_user[lot_no]'");
		$lm_code = pg_fetch_assoc($lmcode_query);
		$login_user['password'] = null;
		$login_user['prev_password1'] = null;
		$login_user['date_password_changed'] = null;

		$chitha_data['login_user'] = $login_user;
		if (!$user) {
			$chitha_data['user'] = $user;
			$chitha_data['lm_code'] = $lm_code;
			$chitha_data['user_desig_code'] = 'LM';
			$chitha_data['is_lm'] = 'y';
		} else {
			$chitha_data['user'] = $user;
			$chitha_data['user_desig_code'] = $user['user_desig_code'];
			$chitha_data['is_lm'] = 'n';
		}
		$_SESSION["chitha_data"] = http_build_query($chitha_data);
		$_SESSION["resurvey_data"] = http_build_query($chitha_data);
		$_SESSION["user_desig_code"] = $chitha_data['user_desig_code'];
	}
	// FOR CHITHA ENTRY END


	//get otp
	if (ENABLE_LOGIN_OTP == 1) {
		$mobile_no = trim($data['mobile_no']);
		if (($mobile_no != '' || $mobile_no != null) && $data['password_change_flag'] == false) {
			getLoginOtp($mobile_no);
			$data['mobile_no'] = maskMobile($mobile_no);
			//////////////LOGIN-OTP-LOG///////////////
			$user_id = isset($_POST['uname']) ? trim($_POST['uname']) : '';
			$maskedOtp = "****" . substr($_SESSION['otp'], -2);
			$otp = isset($_SESSION['otp']) ? $_SESSION['otp'] : '';
			logMessage("SMS-LOG###".$user_id ."###OTP###".$otp);
			if ($otp != '') {
				$dbname      = "dbname = ".CENTRAL_AUTH;
				$db = pg_connect("$host $port $dbname $credentials");
				$client_ip        = get_client_ip();
				$result = pg_query_params($db,
				    "INSERT INTO login_otp_audit
				     (user_id, mobile_no, otp_value, ip_address)
				     VALUES ($1, $2, $3, $4)",
					    [
       						 $user_id,$mobile_no,$maskedOtp,$client_ip
					    ]
					);
				if (!$result) {
				    logMessage("Insert-Failed Data: " . json_encode(['user_id'   => $user_id, 'mobile_no' => $mobile_no, 'otp'       => $maskedOtp, 'ip'        => $client_ip ]) );
				}
			}
			////////////////////////////
		}
	}
	unset($data['values']['password']);
	$data['success'] = true;
	$data['message'] = 'Success!';
	$data['errors']  = $errors;
	echo json_encode($data);
	exit;
} else {
	//echo "failed";	
	unset($data);
	$data['success'] = false;
	$errors['password'] = 'Wrong Credentials/Captcha Mismatched';
	$data['errors']  = $errors;
	echo json_encode($data);
	exit;
}

function central_auth($host, $port, $credentials, $dbname)
{
	$data = array();
	$pass = md5($_POST['password']);
	$dbname      = "dbname = ".CENTRAL_AUTH;
	///////////////
	////////////////////////////////
	$database = RealName($_POST['district']);
	///////////////////////////////////
	//////////////
	$db = pg_connect("$host $port $dbname $credentials");

	if (!$db) {
		$data['success'] = false;
		$errors['db'] = "Error : Database Server Error";
		$data['errors']  = $errors;
		echo json_encode($data);
		exit;
	}
	$result = pg_query($db, "SELECT dhar_user as use_name,noc_user as code ,dist_code as dist_code,
		subdiv_code,cir_code,mouza_pargona_code,lot_no,password_change_flag,password,mobile FROM central_auth 
		where  (dhar_user='$_POST[uname]' or noc_user='$_POST[uname]') and dist_code='$database' "); //
	while ($row = pg_fetch_row($result)) {
		if (!$row) {
			return false;
			break;
		}
		/////////////////////////
		$usermapChecked='y';
		// $connection= dbConnection($_POST['district']);
		// if(!empty($row[0]))
		// {
		// 	$usermapChecked=dharitreeMap($connection,$row[0]);
		// }
		// if(!empty($row[1])){
		// 	$usermapChecked=nocLogin($row[1]);
		// }
		/////////////////////////
		$database = databaseSwitch($row[2]);
		$data['values'] = array(
			'username' => $row[0],
			'code' => $row[1],
			'dharitree' => $row[0],
			'noc' => $row[1],
			'bhunaksha' => null,
			'dist_code' => $row[2],
			'subdiv_code' => $row[3],
			'cir_code' => $row[4],
			'mouza_pargona_code' => $row[5],
			'lot_no' => $row[6],
			'map' => $usermapChecked,
			'dn' => $database,
			'dbname'=>trim($_POST['district']),
			//'secret'=>$_POST['password'],
			//'password_change_flag' => $row[7],
			'password' => $row[8],
			'mobile' => $row[9],
		);

		$_SESSION["credentials"] = $data['values'];
		$payload = $_SESSION["credentials"];
		$jwt = JWT::encode($payload, KEY);
		$_SESSION['token'] = $jwt;
		if (ENABLE_LOGIN_OTP == 1) {
			$data['mobile_no'] = $row[9];
		}
		$password_change_flag = $row[7];
		if ($password_change_flag == 0) {
			$data['password_change_flag'] = true;
		} else {
			$data['password_change_flag'] = false;
		}
	}
	return $data;
}
function dbConnection($auth){
   $db=$auth; 
   $host        = "host = ".VERIFY_USER_DB_HOST;		   
   $port        = "port = ".VERIFY_USER_DB_PORT;  
   $dbname      = "dbname = $db";
   $credentials = "user=postgres password=postgres";
   $db =pg_connect("$host $port $dbname $credentials") or die ("Could not connect to server\n");
   return $db; 
}
function dharitreeMap($conncetion,$user){
	$result = pg_query($conncetion, "SELECT user_map FROM loginuser_table where use_name='$user' and dis_enb_option='E' ");
	$row = pg_fetch_row($result);
	pg_close($conncetion);
	if(!$row){
		return null;
	}else{
		return $row[0];
	}
}
function nocLogin($user){
	$dbname = "dbname =".NOC_MASTER;
	$host        = "host = ".VERIFY_USER_DB_HOST;		   
    $port        = "port = ".VERIFY_USER_DB_PORT;
    
    $credentials = "user=postgres password=postgres";
	$db = pg_connect("$host $port $dbname $credentials");
	$pass=md5($_POST['password']);
	$result = pg_query($db,"SELECT user_map 
	FROM user1 where usnm='$user' and userstat='A' ");
	$row = pg_fetch_row($result);
	if(!$row){
		return false;
	}else{
		return $row[0];
	}
}
function databaseSwitch($val)
{
	switch ($val) {
		case '01':
			return $database = 'dha26';
			break;
		case '02':
			return $database = 'dha3';
			break;
		case '03':
			return $database = 'dha8';
			break;
		case '38':
			return $database = 'dha25';
			break;
		case '05':
			return $database = 'dha1';
			break;
		case '06':
			return $database = 'dha11';
			break;
		case '07':
			return $database = 'dha7';
			break;
		case '08':
			return $database = 'dha19';
			break;
		case '37':
			return $database = 'dha22';
			break;
		case '10':
			return $database = 'dha24';
			break;
		case '11':
			return $database = 'dha12';
			break;
		case '12':
			return $database = 'dha13';
			break;
		case '13':
			return $database = 'dha2';
			break;
		case '14':
			return $database = 'dha6';
			break;
		case '15':
			return $database = 'dha5';
			break;
		case '16':
			return $database = 'dha14';
			break;
		case '17':
			return $database = 'dha4';
			break;
		case '18':
			return $database = 'dha9';
			break;
		case '35':
			return $database = 'dha20';
			break;
		case '36':
			return $database = 'dha21';
			break;
		case '21':
			return $database = 'dha18';
			break;
		case '23':
			return $database = 'dha40';
			break;
		case '22':
			return $database = 'dha41';
			break;
		case '24':
			return $database = 'dha10';
			break;
		case '25':
			return $database = 'dha23';
			break;
		case '32':
			return $database = 'dha15';
			break;
		case '33':
			return $database = 'dha16';
			break;
		case '34':
			return $database = 'dha17';
			break;
		case '38':
			return $database = 'dha25';
			break;
		case '39':
			return $database = 'dha39';
			break;
		default:
			exit;
	}
}

function RealName($db)
{
	switch ($db) {
		case 'kokrajhar':
			return $database = '01';
			break;
		case 'dhubri':
			return $database = '02';
			break;
		case 'goalpara':
			return $database = '03';
			break;
		case 'barpeta':
			return $database = '05';
			break;
		case 'nalbari':
			return $database = '06';
			break;
		case 'kamrup':
			return $database = '07';
			break;
		case 'darrang':
			return $database = '08';
			break;
		case 'chirang':
			return $database = '10';
			break;
		case 'sonitpur':
			return $database = '11';
			break;
		case 'lakhimpur':
			return $database = '12';
			break;
		case 'bongaigaon':
			return $database = '13';
			break;
		case 'golaghat':
			return $database = '14';
			break;
		case 'jorhat':
			return $database = '15';
			break;
		case 'sibsagar':
			return $database = '16';
			break;
		case 'dibrugarh':
			return $database = '17';
			break;
		case 'tinsukia':
			return $database = '18';
			break;
		case 'ssalmara':
			return $database = '38';
			break;
		case 'bajali':
			return $database = '39';
			break;
		case 'cachar':
			return $database = '23';
			break;
		case 'hailakandi':
			return $database = '22';
			break;
		case 'kamrupM':
			return $database = '24';
			break;
		case 'dhemaji':
			return $database = '25';
			break;
		case 'udalguri':
			return $database = '26';
			break;
		case 'morigaon':
			return $database = '32';
			break;
		case 'nagaon':
			return $database = '33';
			break;
		case 'majuli':
			return $database = '34';
			break;
		case 'biswanath':
			return $database = '35';
			break;
		case 'hojai':
			return $database = '36';
			break;
		case 'charaideo':
			return $database = '37';
			break;
		case 'karimganj':
			return $database = '21';
			break;
		case UAT_DB_NAME:
            return $database = UAT_DIST_CODE;
            break;
		default:
			exit;
	}
}

function getLoginOtp($mobile_no)
{
	if (ENABLE_LOGIN_OTP == 1) {
		$random_no = random_int(100000, 999999);
		$_SESSION['otp'] = $random_no;
		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL => SMS_API,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_POSTFIELDS => '{
					"key"       : "login_otp",
					"variables" : "' . $_SESSION['otp'] . '",
					"mobilenos" : "' . $mobile_no . '" 
				}',
		));
		$response = curl_exec($curl);
		curl_close($curl);
	}else{
		$_SESSION['otp'] = OTP;
	}
}

function maskMobile($number)
{

	$maskedMobile1  = substr($number, 0, 2);
	$sq = '******';
	$maskedMobile2 = substr($number, 8, 2);
	return $maskedMobile1 . $sq . $maskedMobile2;
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
              {
              //$ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
              $ipList = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
              $ipaddress = trim($ipList[0]);
              }
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


