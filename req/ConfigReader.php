<?php
class ConfigReader{

	public function readConfigFile($filePath){
		$config = file_get_contents($filePath);
		return json_decode($config);
	}
}
?>