<?php    
//    session_start(); 
//    echo $_SESSION['otp'];
session_start();
include "constants.php";
if(ENABLE_EXPIRY_PASSWORD_MODULE == 1)
{
    $data['otp'] = $_SESSION['otp'];
    echo json_encode($data);
    return;
} 
else
{
    $otp = $_SESSION['otp'];
    $user_name = $_SESSION['user_name'];
    $dist_code = $_SESSION['dist_code'];
    $db          = CENTRAL_AUTH;
    $host        = "host = " . VERIFY_USER_DB_HOST;
    $port        = "port = ". VERIFY_USER_DB_PORT;
    $dbname      = "dbname = $db";
    $credentials = "user=postgres password=postgres";

    //new auth process
    $data = central_auth_data($host, $port, $credentials, $dbname,$user_name,$dist_code,$otp);
    if ($data['responseType'] == 1) {
        //central auth row not found
        $response = array('responseType' => 1,'msg' => "#ERROR01 : Access Denied");
        return $response;
    }
    else
    {
     echo json_encode($data);
      return;
    }
    function central_auth_data($host, $port, $credentials, $dbname,$user_name,$dist_code,$otp)
    {
        $data = array();
        $dbname      = "dbname = ".CENTRAL_AUTH;
        $database = $dist_code;
        $db = pg_connect("$host $port $dbname $credentials");
        if (!$db) {
            $response = array('responseType' => 1,'msg' => "#ERROR02 : Access Denied");
            return $response;
        }
        $result = pg_query($db, "SELECT dhar_user as use_name,noc_user as code ,dist_code as dist_code,password_change
             FROM central_auth 
            where  (dhar_user='$user_name' or noc_user='$user_name') and dist_code='$database' "); //
        while ($row = pg_fetch_row($result)) 
        {
            if (!$row) {
                $response = array('responseType' => 1,'msg' => "#ERROR03 : Access Denied");
                return $response;
                break;
            }
            $currdate = date('Y-m-d');
            $password_change_date = $row[3];
            $date1_ts = strtotime($currdate);
            $date2_ts = strtotime($password_change_date);
            $diff = $date1_ts - $date2_ts;
            $remainingDays = round($diff / 86400);

            if(PASSWORD_EXPIRY_DAYS < $remainingDays)
            {
                $data['password_expired'] = true;
            }
            else
            {
                $data['password_expired'] = false;
        }
        $data['password_change_date']=$password_change_date;
        $data['otp'] = $otp;
        $data['responseType'] = 2;

        }
        return $data;
    }

}
?>
