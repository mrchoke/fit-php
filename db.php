<?php
namespace Fit;

class Db  {
	
	function setvar()
	{
		global $db_file;
		$db_file = "db1.db";
	}

	public function createdb()
	{
		
		global $db_file;
		\Fit\Db::setvar();
		if(is_file($db_file)) unlink($db_file);
		
		$db = new \SQLite3($db_file);
		
		$db->createCollation('NATURAL_CMP', 'strnatcmp');
		
		$db->exec('
		  CREATE TABLE records (
		  	timestamp INTEGER PRIMARY KEY,
		  	position_lat REAL,
		  	position_long REAL,
		  	distance REAL,
		  	altitude REAL,
		  	speed REAL,
		  	heart_rate INTEGER,
		  	cadence INTEGER,
		  	temperature INTEGER
		  );
		
		');
	}
	public function insertdb($timestamp,$position_lat,$position_long,$distance,$altitude,$speed,$heart_rate,$cadence,$temperature)
	{
		global $db_file;
		\Fit\Db::setvar();
		$db = new \SQLite3($db_file);
		$db->exec("INSERT INTO records VALUES($timestamp,$position_lat,$position_long,$distance,$altitude,$speed,$heart_rate,$cadence,$temperature)");
		
	}

	public function querydb($str)
	{
	
		global $db_file;
		\Fit\Db::setvar();
		
		$db = new \SQLite3($db_file);
		return $db->query($str);
		
	}


}

?>
