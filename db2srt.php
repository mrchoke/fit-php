<?php
/*
 *  v 0.1.0 by mrchoke@gmail.com
 * $php db2srt.php -f goprofile.mp4 >goprofile.srt
 * 
 */
date_default_timezone_set('Asia/Bangkok');
require_once  __DIR__."/db.php";

function time2sec($timestr)
{
	$seconds = 0;
	$parts   = explode(':', $timestr);
	
	
	if (count($parts) > 2) {
	   $seconds += $parts[0] * 3600;
	}
	
	$seconds += $parts[1] * 60;
	
	$seconds += $parts[2];
	return $seconds;
}

$options = getopt("f:");

// Set this var for your condition

$tz="07:00:00";
$timeoffset = 8;

$mp4file=$options['f'];
$mp4duration=shell_exec("exiftool  -s3 -Duration ".$mp4file);
$mp4start=shell_exec("exiftool  -s3 -CreateDate ".$mp4file);

$mp4start = strtotime($mp4start) - time2sec($tz);

$mp4durationinseconds = time2sec($mp4duration);

for ($i=0; $i <=$mp4durationinseconds ; $i++) { 
	
	$srtline=$i+1;
	echo $srtline.PHP_EOL;
	echo  gmdate('H:i:s', $srtline).",000 --> ". gmdate('H:i:s', $srtline).",999".PHP_EOL;
    $mp4currenttime = $mp4start + $i + $timeoffset;

	$querystr ="SELECT * FROM records  WHERE timestamp <= ".$mp4currenttime." ORDER BY timestamp DESC LIMIT 1";

	$datafromdb=\Fit\Db::querydb($querystr);
	$row = $datafromdb->fetchArray();
//	echo date("d/m/y H:i:s",$mp4currenttime+time2sec("07:00:00")) ." " .  $row[speed] ." kmh " ." " . $row[cadence] ." rpm " ." " . $row[heart_rate] . " bpm ".  $row[temperature] ."°C  ".$row[altitude] . " m ".PHP_EOL;
	echo sprintf("%s %2.2f kmh %3d rpm %3d bpm %2d °C %3.2f m \n",date("d/m/y H:i:s",$mp4currenttime+time2sec($tz)),  $row[speed],$row[cadence],$row[heart_rate],$row[temperature],$row[altitude]);
	echo PHP_EOL;
}

?>