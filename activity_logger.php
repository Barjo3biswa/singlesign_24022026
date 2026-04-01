<?php


/* ---------- DISTRICT DB SWITCH ---------- */

function databaseSwitch_new($val){
    switch ($val) {

        case '01':
            return $database = 'kokrajhar';
            break;

        case '02':
            return $database = 'dhubri';
            break;

        case '03':
            return $database = 'goalpara';
            break;

        case '05':
            return $database = 'barpeta';
            break;

        case '06':
            return $database = 'nalbari';
            break;

        case '07':
            return $database = 'kamrup_uat';
            break;

        case '08':
            return $database = 'darrang';
            break;

        case '10':
            return $database = 'chirang';
            break;

        case '11':
            return $database = 'sonitpur';
            break;

        case '12':
            return $database = 'lakhimpur';
            break;

        case '13':
            return $database = 'bongaigaon';
            break;

        case '14':
            return $database = 'golaghat';
            break;

        case '15':
            return $database = 'jorhat';
            break;

        case '16':
            return $database = 'sibsagar';
            break;

        case '17':
            return $database = 'dibrugarh';
            break;

        case '18':
            return $database = 'tinsukia';
            break;

        case '21':
            return $database = 'karimganj';
            break;

        case '22':
            return $database = 'hailakandi';
            break;

        case '23':
            return $database = 'cachar';
            break;

        case '24':
            return $database = 'kamrupM';
            break;

        case '25':
            return $database = 'dhemaji';
            break;

        case '26':
            return $database = 'udalguri';
            break;

        case '32':
            return $database = 'morigaon';
            break;

        case '33':
            return $database = 'nagaon';
            break;

        case '34':
            return $database = 'majuli';
            break;

        case '35':
            return $database = 'biswanath';
            break;

        case '36':
            return $database = 'hojai';
            break;

        case '37':
            return $database = 'charaideo';
            break;

        case '38':
            return $database = 'ssalmara';
            break;

        case '39':
            return $database = 'bajali';
            break;

        case UAT_DIST_CODE:
            return $database = UAT_DB_NAME;
            break;

        default:
            exit;
    }
}


function getDistrictConnection($dist_code)
{
    $dbName = databaseSwitch_new($dist_code);
    
    if (!$dbName) return null;
    if(in_array($dbName, DIST_ARRAY_1))
  	{
      $host = "host = ".VERIFY_USER_DB_HOST_1;
  	}
  	elseif(in_array($dbName, DIST_ARRAY_2))
  	{
      $host = "host = ".VERIFY_USER_DB_HOST_2;
  	}
  	else{
      $host = "host = ".VERIFY_USER_DB_HOST;
  	}
    $port = "port=" . VERIFY_USER_DB_PORT;
    $dbname = "dbname=" . $dbName;

    $cred = "user=postgres password=postgres";

    return pg_connect("$host $port $dbname $cred");
}


function getCentralConnection()
{
    $db = CENTRAL_AUTH;

    $host = "host=" . VERIFY_USER_DB_HOST;
    $port = "port=" . VERIFY_USER_DB_PORT;
    $dbname = "dbname=" . $db;

    $cred = "user=postgres password=postgres";

    return pg_connect("$host $port $dbname $cred");
}


function log_request_activity(
    $dist_code,
    $table_name = null,
    $where = null,
    $user_code = null,
    $type = null
)
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $districtConn = getDistrictConnection($dist_code);
    $centralConn  = getCentralConnection();
    if (!$districtConn || !$centralConn) return false;

    $postData = $_POST;
    $url = $_SERVER['REQUEST_URI'] ?? null;
    $ip  = $_SERVER['REMOTE_ADDR'] ?? null;

    $existing_data = null;


    /* ===== FETCH EXISTING DATA FROM CENTRAL AUTH ===== */

    if ($table_name && $where) {

        $columns = array_keys($where);
        $values  = array_values($where);

        $conditions = [];
        foreach ($columns as $index => $col) {
            $param = $index + 1;
            $conditions[] = "$col = $$param";
        }

        $whereClause = implode(' OR ', $conditions);

        $query = "SELECT * FROM $table_name WHERE ($whereClause) LIMIT 1";

        $res = pg_query_params($centralConn, $query, $values);

        $existing_data = pg_fetch_assoc($res);

    } else {
        $existing_data = $_SESSION;
    }
    $action_by = $_SESSION['credentials']['username'] ?? 'SYSTEM';
    $action_at = date('Y-m-d H:i:s');
    $sql = "INSERT INTO user_activity_logs
        (user_code, controller, method, table_name,
         existing_data, postdata, url, ip, action_by, action_at)
        VALUES ($1,$2,$3,$4,$5,$6,$7,$8,$9,$10)";
    pg_query_params($districtConn, $sql, [
        $user_code,
        'Singlesign',
        $type,
        $table_name,
        $existing_data ? json_encode($existing_data) : null,
        json_encode($postData),
        $url,
        $ip,
        $action_by,
        $action_at
    ]);
    pg_close($districtConn);
    pg_close($centralConn);

    return true;
}
