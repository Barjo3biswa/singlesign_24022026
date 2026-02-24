<?php
include "constants.php";
session_start();
$data = array(); 
require_once __DIR__ .'/vendor/autoload.php';
use Firebase\JWT\JWT; 
$ip = $_SERVER['REMOTE_ADDR']; 
//******************************************/
//backend validation 
if (empty($_POST['district'])){
	echo json_encode([
        "result" => false,
        "msg" => "District Name is required."
    ]);
    exit;
}
if (empty($_POST['uname'])){
	echo json_encode([
        "result" => false,
        "msg" => "User Name is required."
    ]);
    exit;
}
if (empty($_POST['password'])){
	echo json_encode([
        "result" => false,
        "msg" => "Password is required."
    ]);
    exit;
}
if (empty($_POST['captcha'])){
	echo json_encode([
        "result" => false,
        "msg" => "Captcha is required."
    ]);
    exit;
}
if (IS_CAPTCHA == 1)
{
	if($_POST['captcha']!=$_SESSION['my_captcha']){
		echo json_encode([
	        "result" => false,
		"msg" => "Wrong Credentialss/Captcha Mismatched",
		"captcha" => $_POST['captcha'],
		"ses_captcha" => $_SESSION['my_captcha']
	    ]);
	    exit;
	}
}
//******************************************/
$db=CENTRAL_AUTH;
//$db='goalpara';
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
//$_POST['dist_code']=RealName($_POST['district']);
$dist_code=RealName($_POST['district']);
//getting central auth data 
$result = pg_query($db,"SELECT dhar_user as use_name,
	noc_user as code ,dist_code as dist_code,subdiv_code,cir_code,mouza_pargona_code,lot_no, 
	password_change_flag,password, mobile
	FROM central_auth where (dhar_user='$_POST[uname]' or noc_user='$_POST[uname]') and dist_code='$dist_code' ");    
$central_auth_row = pg_fetch_row($result);
pg_close($db);
if(!$central_auth_row){	
    //central auth row not found 
	//checking dharitree Login
	////*******CHANGES FOR NEW DBS*********///
	$dist_name_check = trim($_POST['district']);
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
	$dharitreeLogin = doDharitreeLogin($host,$port,$credentials);
	if(!$dharitreeLogin){
		//checking noc login, dharitree login failed
		$nocLogin = nocLogin($host,$port,$credentials);
		if(!$nocLogin){
			//noc login failed, dharitree login failed, central auth failed
			echo json_encode([
                "result" => false,
                "type" => "require_user_map",
                "msg" => 'Wrong Credentials/Captcha Mismatched',
				"login-form" => "NOC"
            ]);
			exit;
		}else{
			//noc login pass, dharitree login failed, central auth failed
			echo json_encode([
                "result" => true,
                "type" => "require_user_map",
                "msg" => 'Old-Login-Success',
				"login-form" => "NOC"
            ]);
			exit;
		}
	}else{
		//dharitree login success
		echo json_encode([
			"result" => true,
			"type" => "require_user_map",
			"msg" => 'Old-Login-Success',
			"login-form" => "Dharitree"
		]);
		
		exit;
	}
}else{	
	//central auth row found 
	$password_change_flag = $central_auth_row[7];
	if($password_change_flag == 0){
		//central auth login 
		$dbp = $central_auth_row[8];
		$pass=md5($_POST['password']);
		$pass1=sha1($_POST['password']);

		if($dbp == $pass || $dbp == $pass1){
			//central auth login success
			if(ENABLE_PASSWORD_CHANGE == 0){
				$database = databaseSwitch($central_auth_row[2]);
				$data['values'] = array(
					'username' => $central_auth_row[0],
					'code' => $central_auth_row[1],
					'dharitree' => $central_auth_row[0],
					'noc' => $central_auth_row[1],
					'bhunaksha' => null,
					'dist_code' => $central_auth_row[2],
					'subdiv_code' => $central_auth_row[3],
					'cir_code' => $central_auth_row[4],
					'mouza_pargona_code' => $central_auth_row[5],
					'lot_no' => $central_auth_row[6],
					'map' => mappedUserChecked($host,$port,$credentials,$central_auth_row[0],$central_auth_row[2]),
					'dn' => $database,
					//'dbname'=>$dbname,
					//'secret'=>$_POST['password'],
					//'password_change_flag' => $row[7],
					//'password' => $password_change_flag[8]
				);
				$_SESSION["credentials"] = $data['values'];
				// FOR CHITHA ENTRY START
				$dbname = "dbname = " . trim($_POST['district']);
				////*******CHANGES FOR NEW DBS*********///
				$dist_name_check = trim($_POST['district']);
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
					$_SESSION["user_desig_code"] = $chitha_data['user_desig_code'];
				}
				// FOR CHITHA ENTRY END
			}
			
			echo json_encode([
				"result" => true,
				"type" => "user_mapped",
				"msg" => 'Old-Login-Success',
				"login-form" => "Central-Auth"
			]);
			exit;
		}
		//checking dharitree login 

		////*******CHANGES FOR NEW DBS*********///
		$dist_name_check = trim($_POST['district']);
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
		$dharitreeLogin = doDharitreeLogin($host,$port,$credentials);
		if(!$dharitreeLogin){
			//checking noc login, dharitree login failed
			$nocLogin = nocLogin($host,$port,$credentials);
			if(!$nocLogin){
				//noc login failed, dharitree login failed, central auth failed
				echo json_encode([
					"result" => false,
					"type" => "user_mapped",
					"msg" => 'Wrong Credentials/Captcha Mismatched',
					"login-form" => "NOC"
				]);
				exit;
			}else{
				//noc login pass, dharitree login failed, central auth failed
				echo json_encode([
					"result" => true,
					"type" => "user_mapped",
					"msg" => 'Old-Login-Success',
					"login-form" => "NOC"
				]);
				exit;
			}
		}else{
			//dharitree login success
			echo json_encode([
				"result" => true,
				"type" => "user_mapped",
				"msg" => 'Old-Login-Success',
				"login-form" => "Dharitree"
			]);
			exit;
		}
	}
}

//noc login
function nocLogin($host,$port,$credentials){
	$dbname = "dbname =".NOC_MASTER;
	$host = "host = ".VERIFY_USER_DB_HOST;
	$db = pg_connect("$host $port $dbname $credentials");
	$pass=md5($_POST['password']);
	$result = pg_query($db,"SELECT usnm as use_name,usroll as code,distcode as dist_code,subdivcode,circlecode,mouzacode,lotno,user_map 
	FROM user1 where usnm='$_POST[uname]' and userstat='A' and passwd='$pass'");
	$row = pg_fetch_row($result);
	if(!$row){
		return false;
	}else{
		$data['values']=array(
				'username'=>$row[0],
				'code'=>$row[1],
				'dharitree'=>null,
				'noc'=>$row[0],
				'bhunaksha'=>null,
				'dist_code'=>$row[2],
				'subdiv_code'=>$row[3],
				'cir_code'=>$row[4],
				'mouza_pargona_code'=>$row[5],
				'lot_no'=>$row[6],
				'map'=>mappedUserChecked($host,$port,$credentials,$row[0],$row[2]),
				'database'=>$_POST['district'],
				'dbname'=>$dbname,
				'secret'=>$_POST['password'],
				'mobile' => null,
		);
		$_SESSION["credentials"]=$data['values'];
		$payload = $_SESSION["credentials"];
		$jwt = JWT::encode($payload, KEY);
		$_SESSION['token'] = $jwt;
		if(ENABLE_PASSWORD_CHANGE == 0){
			// FOR CHITHA ENTRY START
			$dbname = "dbname = " . trim($_POST['district']);
		        ////*******CHANGES FOR NEW DBS*********///
			$dist_name_check = trim($_POST['district']);
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
				$_SESSION["user_desig_code"] = $chitha_data['user_desig_code'];
			}
			// FOR CHITHA ENTRY END
		}
		return true;
	}
	pg_close($db);
}

//dharitree login
function doDharitreeLogin($host,$port,$credentials){
	$dist_name=trim($_POST['district']);
	$dbname = "dbname = $dist_name" ;
	$db = pg_connect("$host $port $dbname $credentials");
	$pass=md5($_POST['password']);
	$pass1=sha1($_POST['password']);
	/////////////////
	$result = pg_query($db, "SELECT use_name as use_name,user_code as code,nocuser as noc,dist_code as dist_code,subdiv_code,cir_code,mouza_pargona_code,lot_no,user_map,password,user_code FROM 
	loginuser_table where use_name='$_POST[uname]' and dis_enb_option='E'");
    $row = pg_fetch_row($result);
    if(!$row){
    	return false;
    }
    $dbp = $row[9];
    $bcrypt_flag = password_verify($_POST['password'], $dbp);
    $login_flag = false;
    $password_change_flag = 0;
    if($bcrypt_flag){
    	$login_flag = true;
    	$password_change_flag = 1;
    }else if($dbp == $pass){
    	$login_flag = true;
    	$password_change_flag = 0;
    }else if($dbp == $pass1){
    	$login_flag = true;
    	$password_change_flag = 0;
    }else{
    	$login_flag = false;
    	$password_change_flag = 0;
    }

    //FOR_BYPASS
    if (IS_PRODUCTION==0)
    {
    	$login_flag=true;
    }
 //    /////////////////////
 //    $user_query = pg_query($db, "SELECT phone_no FROM users where user_code='$row[1]' and dist_code='$row[3]' and subdiv_code='$row[4]' and cir_code='$row[5]'");
	// $user_phone = pg_fetch_row($user_query);
	// if(!$user_phone){
 //    	return false;
 //    }
	// $result = pg_query($db, "SELECT use_name as use_name,user_code as code,nocuser as noc,dist_code as dist_code,subdiv_code,cir_code,mouza_pargona_code,lot_no,user_map,password FROM 
	// loginuser_table where use_name='$_POST[uname]' and dis_enb_option='E' and (password='$pass' or password='$pass1')");	
	// $row = pg_fetch_row($result);
	if(!$login_flag){
		return false;
	}else{
		$user_code=$row[10];
		if(substr($user_code,0,1) == "M"){
			if(substr($user_code,0,2) == "MO"){
				$phone_no = null;
			}else{
				$result_lc = pg_query($db, "SELECT phone_no from lm_code where lm_code='$user_code' and dist_code='$row[3]' and subdiv_code='$row[4]' and cir_code='$row[5]' and mouza_pargona_code='$row[6]' and lot_no='$row[7]' ");
				$row_lc = pg_fetch_row($result_lc);
				$phone_no = $row_lc[0];
			}
		}else{
			$result_uesrs = pg_query($db, "SELECT phone_no from users where user_code='$user_code' and dist_code='$row[3]' and subdiv_code='$row[4]' and cir_code='$row[5]' ");
			$row_users = pg_fetch_row($result_uesrs);
			$phone_no = $row_users[0];
		}

		$database=databaseSwitch($row[3]);
		$data['values']=array(
			'username'=>$row[0],
			'code'=>$row[1],
			'dharitree'=>1,
			'noc'=>$row[2],
			//'bhunaksha'=>$row[3],
			'dist_code'=>$row[3],
			'subdiv_code'=>$row[4],
			'cir_code'=>$row[5],
			'mouza_pargona_code'=>$row[6],
			'lot_no'=>$row[7],
			'map'=>mappedUserChecked($host,$port,$credentials,$row[0],$row[3]),
			'dn'=>$database,
			'dbname'=>$dbname,
			'secret'=>$dbp,
			'mobile'=>$phone_no,
			'password_change_flag'=>$password_change_flag
		);
		$_SESSION["credentials"]=$data['values'];
		$payload = $_SESSION["credentials"];
		$jwt = JWT::encode($payload, KEY);
		$_SESSION['token'] = $jwt;
		if(ENABLE_PASSWORD_CHANGE == 0){
			// FOR CHITHA ENTRY START
			$dbname = "dbname = " . trim($_POST['district']);
			////*******CHANGES FOR NEW DBS*********///
			$dist_name_check = trim($_POST['district']);
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
				$_SESSION["user_desig_code"] = $chitha_data['user_desig_code'];
			}
			// FOR CHITHA ENTRY END
		}
		return true;
	}
	pg_close($db);
}
function mappedUserChecked($host,$port,$credentials,$user,$district){
	$dbname ="dbname =central_auth";
	$host = "host = ".VERIFY_USER_DB_HOST;
	$db = pg_connect("$host $port $dbname $credentials");
	$result = pg_query($db,"SELECT dhar_user as use_name,
    noc_user as code 
    FROM central_auth where (dhar_user='$user' or noc_user='$user') and dist_code='$district' ");  
    $central_auth_row = pg_fetch_row($result);
    pg_close($db);
    if(!$central_auth_row){
    	return 'n';
    }else{
    	return 'y';
    }
}

function databaseSwitch($val){
	switch ($val) {
		case '01':
			return $database='dha26';
			break;
		case '02':
			return $database='dha3';
			break;
		case '03':
			return $database='dha8';
			break;
		case '38':
			return $database='dha25';
			break;
		case '05':
			return $database='dha1';
			break;
		case '06':
			return $database='dha11';
			break;
		case '07':
			return $database='dha7';
			break;
		case '08':
			return $database='dha19';
			break;
		case '37':
			return $database='dha22';
			break;
		case '10':
			return $database='dha24';
			break;
		case '11':
			return $database='dha12';
			break;
		case '12':
			return $database='dha13';
			break;
		case '13':
			return $database='dha2';
			break;
		case '14':
			return $database='dha6';
			break;
		case '15':
			return $database='dha5';
			break;
		case '16':
			return $database='dha14';
			break;
		case '17':
			return $database='dha4';
			break;
		case '18':
			return $database='dha9';
			break;
		case '35':
			return $database='dha20';
			break;
		case '36':
			return $database='dha21';
			break;
		case '21':
			return $database='dha18';
			break;
		case '23':
			return $database='dha40';
			break;
		case '24':
			return $database='dha10';
			break;
		case '25':
			return $database='dha23';
			break;
		case '32':
			return $database='dha15';
			break;
		case '33':
			return $database='dha16';
			break;
		case '34':
			return $database='dha17';
			break;
		case '38':
			return $database='dha25';
			break;
		case '39':
			return $database='dha39';
			break;
		case '22':
			return $database='dha41';
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
?>
