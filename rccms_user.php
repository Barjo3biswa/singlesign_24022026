<?php
session_start();
include "constants.php";
require_once __DIR__ .'/vendor/autoload.php';
use Firebase\JWT\JWT;

//require __DIR__ . '\vendor\autoload.php';
//use Firebase\JWT\JWT;
$key = "abcd123haryanasinglesigonapplicationDFFEFSDAFE";
// $db			 = CENTRAL_AUTH;
$host        = "host = " . VERIFY_USER_DB_HOST;
$port        = "port = ". VERIFY_USER_DB_PORT;
$credentials = "user=postgres password=postgres";
$dbname      = "dbname = ".$_SESSION['credentials']['dbname'];
$dist_name_check = $_SESSION['credentials']['dbname'];
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
$db = pg_connect("$host $port $dbname $credentials");
$permissions = [
	'dharitree' => ['AST','DEO',"DCN",'LM','SK','CO','ADC','BO','DC','SDO'],
	'rccms' => ['DC','ADC','CO','AST','DDA','ADA'],
	'noc' => ['AST','LM','SK','CO','ADC','DC'],
];
/////////
$user=$_SESSION['credentials']['username'];
$user_code=$_SESSION['credentials']['code'];
$dist_code=$_SESSION['credentials']['dist_code'];
$subdiv_code=$_SESSION['credentials']['subdiv_code'];
$cir_code=$_SESSION['credentials']['cir_code'];

$query="SELECT permission_allowed,parent_code,u.user_desig_code,u.user_code,u.username FROM loginuser_table lg 
join users u on u.user_code=lg.user_code and lg.dist_code=u.dist_code and lg.subdiv_code=u.subdiv_code and lg.cir_code=u.cir_code where lg.use_name='$user' and lg.dis_enb_option='E' and lg.dist_code='$dist_code' and lg.subdiv_code='$subdiv_code' and lg.cir_code='$cir_code' and  lg.permission_allowed is not null";
$result = pg_query($db,$query);
$row = pg_fetch_row($result);
// logMessage("CHECK-QUERY###".$query);
// logMessage("RESULT###".var_dump($row));
// pg_close($db);
if(!$row){
	echo json_encode(['resonponseType'=>2, 'status'=>'n']); return;
}else{
	/////////////////////////
	if($row[2]!='DC'){
		if(($row[0]==null || empty($row[0])) )
		{
			echo json_encode(['resonponseType'=>2, 'status'=>'n']); 
			return;
		}
	}
	// $ParentUserID='11';
	$DC_OFFICE_USER=['DDA','ADA','DC','ADC'];
	$CO_OFFICE_USER=['CO','AST','CDA'];
	$PARENT_USER=['DC','ADC','CO'];
	if(in_array($row['2'],$DC_OFFICE_USER)){
		$sqlQuery="select use_name from loginuser_table lgt where lgt.dist_code='$dist_code' and lgt.subdiv_code='00' and lgt.user_code='$row[1]' and lgt.dis_enb_option='E'  "; 
	}else if(in_array($row['2'],$CO_OFFICE_USER)){
		$sqlQuery="select use_name from loginuser_table lgt where lgt.dist_code='$dist_code' and subdiv_code='$subdiv_code' and cir_code='$cir_code' and lgt.user_code='$row[1]' and lgt.dis_enb_option='E'  "; 
	}
	// logMessage("CHECK-QUERY###1#####".$sqlQuery);
	$result1 = pg_query($db,$sqlQuery);
	$row1 = pg_fetch_row($result1);
	///////////////////////
	$lgd_code="Select lgd_code,uuid,(select lgd_code from location where dist_code=ll.dist_code and subdiv_code='00') dist_lgd_code from location ll where dist_code='$dist_code' and subdiv_code='$subdiv_code' and cir_code='$cir_code' and mouza_pargona_code='00' ";
	$result2 = pg_query($db,$lgd_code);
	$row2 = pg_fetch_row($result2);
	//////////////////////
	pg_close($db);
	// if(in_array($row['2'],$PARENT_USER)){
	// 	$ParentUserID='11';
	// 	logMessage("PARENT###".$row['2']);
	// }
	// logMessage("PARENTUSERID1###".$ParentUserID);
	// logMessage("PARENTUSERID11###".$row['1']);
	/////////////////////////
	$payload = array(
	    "Sub" => "logintoken",
	    "UserName" => $user,
	    "Dist_code" => $row2[2],
	    "Cir_code" =>  $row2[1],
	    "Name" => 	$row[4],
	    "Designation" => $row['2'],
	    "UserCode" => $row['3'],
	    "ParentUserCode" =>  $row['1'],
	    "ParentUserName" =>  isset($row1['0']) ? $row1['0'] : 'NA',
	    "UserStatusUpdated"=> null,
	    "Client_IP" => get_client_ip(),
	    'Sess_out' => "https://".IP_HOST."/",  
	);
	// logMessage("PARENTUSERID2###".$ParentUserID);
	// logMessage("PARENTUSERID---3###".($ParentUserID !== null) ? $ParentUserID : null);
	$jwt = JWT::encode($payload, $key);
	logMessage("USERID###".$user."###JWT###".$jwt);
	////////////TOKEN INSERTION/////////
	$db=CENTRAL_AUTH;
        $host        = "host = ".VERIFY_USER_DB_HOST;
        $port        = "port = ".VERIFY_USER_DB_PORT;
        $dbname      = "dbname = $db";
        $credentials = "user=postgres password=postgres";
        $conn =pg_connect("$host $port $dbname $credentials") or die ("Could not connect to server\n");
	$date_entry=date('Y-m-d H:i:s');
	$ip=get_client_ip();
	$sql = "INSERT INTO rccms_tokens (date_entry, user_code, token, ip) VALUES ($1, $2, $3, $4)";
	$result = pg_query_params($conn, $sql, array($date_entry, $user, $jwt, $ip));
	if (!$result) {
    	logMessage("TOKEN-INSERTION-FAILED###".$jwt);
	    echo json_encode(['resonponseType'=>2, 'status'=>'n']);
		return;
	}
	pg_close($conn);
	////////////////////
	echo json_encode(['resonponseType'=>2,'status'=>'y','token'=>$jwt]);
	return;
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

