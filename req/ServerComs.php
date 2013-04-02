<?php
class ServerComs{
	private $con, $auth, $host;
	
	public function __construct($address, $port, $user, $pass){
		$this->con = @fsockopen($address, $port, $errno, $errstr, 30);
		if(!$this->con){
			die("Failed to connect to server");
		}
		$this->host = $address.":".$port;
		$this->auth = base64_encode($user.":".$pass);
	}
	
	public function sendData($data){
		$out = "GET / HTTP/1.0\r\n";
		$out .= "Accept:*/*\r\n";
		$out .= "Accept-Charset:ISO-8859-1,utf-8;q=0.7,*;q=0.3\r\n";
		$out .= "Accept-Encoding:gzip,deflate,sdch\r\n";
		$out .= "Accept-Language:en-US,en;q=0.8\r\n";
		$out .= "Authorization:Basic ".$this->auth."\r\n";
		$out .= "Connection:keep-alive\r\n";
		$out .= "Content-Length: ".strlen(json_encode($data))."\r\n";
		$out .= "Content-Type:application/json\r\n";
		$out .= "Host:".$this->host."\r\n";
		$out .= "User-Agent: phpPoold/0.1\r\n";
		$out .= "\r\n";
		$out .= json_encode($data)."\r\n";
		
		fwrite($this->con, $out);
		$buffer = "";
		while(!feof($this->con)){
			$buffer .= fgets($this->con, 128);
		}
		return $buffer;
	}
	
	public function __destruct(){
		fclose($this->con);
	}
	
}
?>