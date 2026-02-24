<?php

// namespace Odan\Util;

// *
//  * System infos for Linux (Ubuntu) and Windows.
//  *
//  * - Total RAM
//  * - Free RAM
//  * - Disk size
//  * - CPU load in %

$st = microtime(true);
$mb1= testUrl('https://basundhara.assam.gov.in/rtpsmb/LocalAPI/getAppDetails?id=278589');
$mb1_time= microtime(true) - $st;
$mb1['time']=$mb1_time;

$st = microtime(true);
$mb2= testUrl('https://basundhara.assam.gov.in/rtpsmb/LocalAPI/getAppDetails?id=430337');
$mb2_time= microtime(true) - $st;
$mb2['time']=$mb2_time;


$res1 = connectDharDb('10.177.7.130',5432, 'central_auth', 'location');
$res2 = connectDharDb('10.177.7.153',5432, 'central_auth', 'location');


$final_array=array(
                  'server' => '35',
                  'cpu'=> getCpuLoadPercentage(),
                  'ram'=> array('total'=> round(getRamTotal() /(1024*1024*1024)), 
                                'free' => round(getRamFree() /(1024*1024*1024)),
                                ),
                  'disk'=>array(
                               'c'=> getDiskSize('C:'),
                               'e'=> getDiskSize('D:')
                          ),
                  'api_mb1' => $mb1,
                  'api_mb2' => $mb2,
				  'dhar_live'=>$res1,
				  'dhar_repl'=>$res2,
             );
header('content-type:application/json');
echo json_encode($final_array);

	function connectDharDb($ip, $port, $db, $table)
	{
	   $before_conn1 = microtime(true);
	   $d1=pg_connect("host=$ip port=$port user=postgres password=postgres dbname=$db connect_timeout=5") ;
	   if (!$d1)
		  return array('conn'=>'failed', 'conn_time'=>(microtime(true) - $before_conn1), 'fetch'=>'failed', 'fetch_time'=>0);
	   $t1=microtime(true) - $before_conn1;
	   $before_conn1 = microtime(true);
	   $result = pg_query($d1, "select count(*) from $table");
	   $t2=microtime(true) - $before_conn1;
	   return array('conn'=>'success', 'conn_time'=>$t1, 'fetch'=>$result ? 'success':'failed', 'fetch_time'=>$t2);
	}

    /**
     * Return RAM Total in Bytes.
     *
     * @return int Bytes
     */
     function getRamTotal()
    {
        $result = 0;        
        $lines = null;
        $matches = null;
        exec('wmic ComputerSystem get TotalPhysicalMemory /Value', $lines);
        if (preg_match('/^TotalPhysicalMemory\=(\d+)$/', $lines[2], $matches)) {
            $result = $matches[1];
        }
        
        // KB RAM Total
        return (int) $result;
    }

    /**
     * Return free RAM in Bytes.
     *
     * @return int Bytes
     */
     function getRamFree()
    {
        $result = 0;        
        $lines = null;
        $matches = null;
        exec('wmic OS get FreePhysicalMemory /Value', $lines);
        if (preg_match('/^FreePhysicalMemory\=(\d+)$/', $lines[2], $matches)) {
            $result = $matches[1] * 1024;
        }       
        // KB RAM Total
        return (int) $result;
    }

    /**
     * Return harddisk infos.
     *
     * @param sring $path Drive or path
     * @return array Disk info
     */
     function getDiskSize($path = '/')
    {
        $result = array();
        $result['size'] = 0;
        $result['free'] = 0;
        $result['used'] = 0;        
        $lines = null;
        exec('wmic logicaldisk get FreeSpace^,Name^,Size /Value', $lines);
        foreach ($lines as $index => $line) {
            if ($line != "Name=$path") {
                continue;
            }
            $result['free'] = explode('=', $lines[$index - 1])[1];
            $result['size'] = explode('=', $lines[$index + 1])[1];
            $result['used'] = $result['size'] - $result['free'];
            $result['free'] = round($result['free']/(1024*1024*1024));
            $result['size'] = round($result['size']/(1024*1024*1024));
            $result['used'] = round($result['used']/(1024*1024*1024));
            break;
        }        
        return $result;
    }

    /**
     * Get CPU Load Percentage.
     *
     * @return float load percentage
     */
    function getCpuLoadPercentage()
    {
        $result = -1;
        $lines = null;        
        $matches = null;
        exec('wmic.exe CPU get loadpercentage /Value', $lines);
        if (preg_match('/^LoadPercentage\=(\d+)$/', $lines[2], $matches)) {
            $result = $matches[1];
        }      
        return (float) $result;
    }
    function testUrl($url)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_NOSIGNAL, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,  2);
        curl_setopt($ch, CURLOPT_TIMEOUT_MS, 10000);
        $data = curl_exec($ch);
        $curl_errno = curl_errno($ch);
        $curl_error = curl_error($ch);
        curl_close($ch);

        if ($curl_errno > 0) {
            return array('responseType'=>1,'msg'=>"cURL Error ($curl_errno): $curl_error\n", 'data'=>'');
        } else {
            return array('responseType'=>2,'msg'=>"Data received", 'data'=>$data);            
        }
    }


