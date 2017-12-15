<?php

date_default_timezone_set('Asia/Shanghai');

/*========================================================
Log class to write log in standard and easy way.

Author:			Jason Tsui
Last Modified:	2014-09-23

==========================================================*/

error_reporting(E_ALL);

class Log {
	private $m_filepath, $m_fp;
	private $isNewFile = false;

	function __construct() {
		$this->m_fp = false;
	}
	
	function Open($filepathname, $filepath="") {
		if(empty($filepath)) $filepath = dirname(($filepathname));
	    if( !empty($filepath) ) {
			$filepath=rtrim($filepath, '/');
			if(!file_exists($filepath))	mkdir($filepath, 0755, true);
		}

		$this->m_filepath = $filepathname;
		$this->m_fp=fopen($this->m_filepath, "at");
		flock($this->m_fp, LOCK_EX);

		// if(filesize($this->m_filepath) == 0) {
		// 	$this->isNewFile = true;
		// 	$this->WriteLine('file empty');
		// } else {
		// 	$this->isNewFile = false;
		// 	$this->WriteLine('file not empty');
		// }

		// if(feof($this->m_fp)) {
		// 	$this->isNewFile = true;
		// 	$this->WriteLine('file empty');
		// } else {
		// 	$this->isNewFile = false;
		// 	$this->WriteLine('file not empty');
		// }

		// $char = fgetc($this->m_fp);
		// var_dump($char);
		// $this->WriteLine($char);

		$stat = fstat($this->m_fp);
		if($stat['size'] <= 0) {
			$this->isNewFile = true;
		} else {
			$this->isNewFile = false;
		}
		// var_dump($stat);
		
		return $this->m_fp;
	}
	
	function OpenDateFile($logpath, $fname_prefix="", $fname_suffix="") {
		$logpath=rtrim($logpath, '/');
		if(!file_exists($logpath))	mkdir($logpath, 0755, true);
		$this->m_filepath=$logpath.'/'.$fname_prefix.date('Ymd').$fname_suffix.'.log';
		$this->m_fp=fopen($this->m_filepath, "at");
		return $this->m_fp;
	}
	
	function OpenDateTimeFile($logpath, $fname_prefix="", $fname_suffix="", $logpath_subfolder="") {
		$logpath=rtrim($logpath, '/');
		$logpath=$logpath.'/'.date('Ymd');
                if(!empty($logpath_subfolder))     $logpath.='/'.rtrim($logpath_subfolder,'/');
		if(!file_exists($logpath))	mkdir($logpath, 0755, true);
		$this->m_filepath=$logpath.'/'.$fname_prefix.date('His').$fname_suffix.'.log';
		$this->m_fp=fopen($this->m_filepath, "at");
		return $this->m_fp;
	}
	
	function Close() {
		if($this->m_fp==false) return false;
		// $this->WriteLine('flush');
		fflush($this->m_fp);
		flock($this->m_fp, LOCK_UN);
		fclose($this->m_fp);
		$this->m_fp=false;
	}

	function WriteMsg($str, $linechar="") {
		if($this->m_fp==false) return false;
		if($linechar!="") {
			$line="";
			for($i=0;$i<90;++$i) $line=$line.$linechar;
			fwrite($this->m_fp, "\n$line\n");
		}
		fwrite($this->m_fp, "[".date('h:i:s')."] ".$str."\n");
	}

	function WriteArray($str, $ary) {
		if($this->m_fp==false) return false;
		fwrite($this->m_fp, "[".date('h:i:s')."] ".$str.print_r($ary,true)."\n");
	}

	/* write pretty json requires php 5.4 or later */
	function WriteJson($str, $json) {
		if($this->m_fp==false) return false;
		$ary=json_decode($json);
		$json=json_encode($ary, JSON_PRETTY_PRINT);
		fwrite($this->m_fp, "[".date('h:i:s')."] ".$str.$json."\n");
	}

	function WriteSessionStart() {
		if($this->m_fp==false) return false;
		$this->WriteMsg("Session Started.", "-");
		if(isset($_SERVER['REMOTE_ADDR'])) $this->WriteArray('RemoteAddr=', $_SERVER['REMOTE_ADDR']);
		$this->WriteArray('$_REQUEST=', $_REQUEST);
	}

	function WriteLine($str) {
		if($this->m_fp==false) return false;
		fwrite($this->m_fp, $str."\n");
	}

	function WriteCsv($fields) {
		if($this->m_fp==false) return false;
		fputcsv($this->m_fp, $fields);
	}

	public function IsNewFile() {
		return $this->isNewFile;
	}
}

/*
// test main
$log = new Log();
$log->OpenDateTimeFile("/var/goqolog/logtest", "", "-ip-saveitem");
$log->WriteSessionStart();
$log->WriteMsg("Parameter received.");
$itemList[] = array("123", "456");
$itemList[] = array("abc", "def", "ghi");
$log->WriteArray("itemList=", $itemList);
$log->WriteJson("itemList=", json_encode($itemList));
$log->WriteMsg("Exited.");
$log->Close();
*/

?>
