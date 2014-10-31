<?php

/*
 *  v 0.1.0 by mrchoke@gmail.com
 * $php fit2db.php -f garminfil.fit
 * 
 */
date_default_timezone_set('Asia/Bangkok');

require_once  __DIR__."/fit"."/Core.php";
require_once  __DIR__."/fit"."/Data.php";
require_once  __DIR__."/fit"."/Reader.php";
require_once  __DIR__."/fit"."/ProductProfile.php";
require_once  __DIR__."/fit"."/Zend_Io_Reader.php";
require_once  __DIR__."/db.php";
$debug = FALSE;


$options = getopt("f:");

$filepath=$options['f'];

$fit = new \Fit\Reader(true);
$fit->parseFile($filepath, $debug);

\Fit\Db::createdb();


foreach ($fit as $key => $value) {

   if ($key =="records"){
   	$oldkey="";
		foreach ($value as $key2 => $value2) {
			if((sizeof($value2) == 8) or (sizeof($value2) == 9)){
				foreach ($value2 as $key3 => $value3) {
					
					$vout = $value3['value'];
					switch ($key3) 
					{
					    case 'timestamp':
					        $timestamp=\Fit\Data::timeToUnix($vout);
					        break;
					    case 'position_lat':
					        $position_lat=\Fit\Data::positionToDegrees($vout);
					        break;
					    case 'position_long':
					        $position_long=\Fit\Data::positionToDegrees($vout);
					        break;
						case 'distance':
					        $distance=round($vout,2,PHP_ROUND_HALF_UP);
					        break;
						case 'altitude':
					        $altitude=$vout;
					        break;
					    case 'speed':
					        $speed=round($vout,2,PHP_ROUND_HALF_UP);
					        break;					        
						case 'heart_rate':
					        $heart_rate=$vout;
					        break;
						case 'cadence':
					        $cadence=$vout;
					        break;
						case 'temperature':
					        $temperature=$vout;
					        break;
	  				}
					
					
					if(($key3 == 'temperature') && ($oldkey == 'heart_rate')) $cadence=0;
					echo  $key3 .":".$vout." ";
					
					
				    $oldkey=$key3;
				}
				\Fit\Db::insertdb($timestamp,$position_lat,$position_long,$distance,$altitude,$speed,$heart_rate,$cadence,$temperature);
				echo PHP_EOL;
			}
					
		}
	
 
   }
}