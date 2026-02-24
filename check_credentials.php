
<?php 
    session_start();
    include "constants.php";
    $_POST['district'] = $_POST['dist_code'];
    $_POST['dist_code']=RealName($_POST['dist_code']);
    //******************************************/
    //backend validation 
    if (empty($_POST['uname'])){
        echo json_encode([
            "result" => false,
            "msg" => "User Name is required."
        ]);
        exit;
    }if (empty($_POST['dist_code'])){
        echo json_encode([
            "result" => false,
            "msg" => "District Name is required."
        ]);
        exit;
    }
    //******************************************/
    $db=CENTRAL_AUTH;
    //$db='goalpara';
    $host        = "host = ".VERIFY_USER_DB_HOST;
    $port        = "port = ". VERIFY_USER_DB_PORT;
    $dbname      = "dbname = $db" ;

    $credentials = "user=postgres password=postgres";
    $db = pg_connect("$host $port $dbname $credentials");
    if(!$db) {
        echo json_encode([
            "result" => false,
            "msg" => "Error('#SIERR0001') : Some Error Occured..!, Please Contact Administrator..!"
        ]);
        exit;
    }
    //getting central auth data 
    $result = pg_query($db,"SELECT dhar_user as use_name,
    noc_user as code ,dist_code as dist_code,subdiv_code,cir_code,mouza_pargona_code,lot_no, 
    password_change_flag,password,mobile
    FROM central_auth where (dhar_user='$_POST[uname]' or noc_user='$_POST[uname]') and dist_code='$_POST[dist_code]'");    
    $central_auth_row = pg_fetch_row($result);
    pg_close($db);
    if(!$central_auth_row){	
        //central_auth row not found 
        echo json_encode([
            "result" => true,
            "type" => "password_not_changed",
            "msg" => "password_not_changed"
        ]);
        exit;
    }else{	
        //central auth row found 
        $password_change_flag = $central_auth_row[7];
        if($password_change_flag == 0){
            //password not changed 
            echo json_encode([
                "result" => true,
                "type" => "password_not_changed",
                "msg" => "password_not_changed"
            ]);
            exit;
        }else if($password_change_flag == 1){
            //password chnaged and 
            $dist_code = $central_auth_row[2];
            $dhar_user = $central_auth_row[0];
            $noc_user = $central_auth_row[1];
            $returnData=updateDharDb($dist_code,$dhar_user,$noc_user,$_POST['district']);
            if($returnData['result'] && $returnData['msg'] == 'not-allowed'){
                echo json_encode([
                    "result" => true,
                    "type" => "both-mismatch-in-update",
                    "msg" => "Error('#SIERR0003') : Some Error Occured..!, Please Contact Administrator..!"
                ]);
                exit;
            }

            if(!$returnData['result']){
                echo json_encode([
                    "result" => false,
                    "type" => "password_changed",
                    "msg" => "Error('#SIERR0004') : Some Error Occured..!, Please Contact Administrator..!"
                ]);
                exit;
            }
            ///////////////////////////
            $pass = $central_auth_row[8];
            $arr_salts = explode('$', $pass);
            $salt = null;
            if (!isset($arr_salts[1]) || !isset($arr_salts[2]) || !isset($arr_salts[3])){
                echo json_encode([
                    "result" => false,
                    "type" => "password_changed",
                    "msg" => "Error('#SIERR0002') : Some Error Occured..!, Please Contact Administrator..!"
                ]);
                exit;
            }
            $salt = '$'.$arr_salts[1].'$'.$arr_salts[2].'$'.substr($arr_salts[3], 0,22);          
            echo json_encode([
                "result" => true,
                "type" => "password_changed",
                "msg" => $salt
            ]);
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
function updateDharDb($dist_code,$dhar_user,$noc_user,$district){
        $port = 'port = '.VERIFY_USER_DB_PORT;
        $dist_name=trim($district);
        $dbname = "dbname = '$dist_name'" ;
        $host        = "host = ".VERIFY_USER_DB_HOST;
        ///********NEW DATABASE CONNECTION*********//
        if(in_array($dist_name, DIST_ARRAY_1))
        {
            $host = "host = ".VERIFY_USER_DB_HOST_1;
        }
        elseif(in_array($dist_name, DIST_ARRAY_2))
        {
            $host = "host = ".VERIFY_USER_DB_HOST_2;
        }
        else{
            $host = "host = ".VERIFY_USER_DB_HOST;
        }
        //**********END**************//
        $credentials = "user=postgres password=postgres";
        $db = pg_connect("$host $port $dbname $credentials");
        if(!$db) {
            return ['result' => false, 'msg'=>'not-allowed'];
            exit;
        }
        $result = pg_query($db, "SELECT password_change_flag FROM loginuser_table where (use_name='$dhar_user' or nocuser='$noc_user') and dis_enb_option='E' and password_change_flag=0");    
        $row = pg_fetch_row($result);
        if(!$row){
            return ['result' => true, 'msg'=>'allowed'];
        }else{
            //update central auth
            $db=CENTRAL_AUTH;
            $host        = "host = ".VERIFY_USER_DB_HOST;
            $port        = "port = ".VERIFY_USER_DB_PORT;
            $dbname      = "dbname = $db" ;
            $credentials = "user=postgres password=postgres";
            $db = pg_connect("$host $port $dbname $credentials");
            if(!$db) {
                return ['result' => false, 'msg'=>'not-allowed'];
                exit;
            }
            $updateDhar=array(
                'password_change_flag' => 0,
            );

            $table = 'central_auth';
            if($dhar_user){
                $updateQueryString=array(
                    'dhar_user' => $dhar_user
                );
            }else if($noc_user){
                $updateQueryString=array(
                    'noc_user' => $noc_user
                );
            }else{
                return ['result' => false, 'msg'=>'not-allowed'];
            }
            $res = pg_update($db, $table, $updateDhar, $updateQueryString);
            if ($res) {
                return ['result' => true, 'msg'=>'not-allowed'];
            }else{
                return ['result' => false, 'msg'=>'not-allowed'];
            }

        }
    }
?>
