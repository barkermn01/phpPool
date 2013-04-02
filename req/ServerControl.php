<?php 
class ServerControl{
	private $db, $config;
	
	public function __construct($config){
		$this->config = $config;
	}
	
	public function setDatabaseConnection($db){
		$this->db = $db;
	}
	
	public function checkLogin(){
		if (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])) {
			$user = $_SERVER['PHP_AUTH_USER'];
			$pass = $_SERVER['PHP_AUTH_PW'];
			$username = explode(".", $_SERVER['PHP_AUTH_USER']);
			$check = $this->db->select("`user_id`")
						->from("users")
						->where("`username`='".$username[0]."'")
						->execute("getRow");
			if(!$check){
				header('WWW-Authenticate: Basic realm="phppool worker login"');
				header('HTTP/1.0 401 Unauthorized');
				die('Unauthorized');
			}
			
			$check = $this->db->select("`worker_id`")
						->from("`workers`")
						->where("`user_id` = '".$check['user_id']."' AND `username` = '".$username[1]."' AND `password` = SHA1('".$pass."')")
						->execute("getRow");
						
			if(!$check){
				header('WWW-Authenticate: Basic realm="phppool login"');
				header('HTTP/1.0 401 Unauthorized');
				die('Unauthorized');
			}
		}else{
			header('WWW-Authenticate: Basic realm="phppool login"');
			header('HTTP/1.0 401 Unauthorized');
			die('Unauthorized');
		}
	}
	
	public function handleProxy(){
		$server = new ServerComs($this->config->server->rpc_host, $this->config->server->rpc_port, $this->config->server->rpc_user, $this->config->server->rpc_password);
		$input = file_get_contents("php://input");

		$data = json_decode($input);
		$response = $server->sendData($data);

		$output = explode("\r\n\r\n", $response);

		$output[0] = explode("\r\n", $output[0]);
		foreach($output[0] as $key => $line){
			header($line);
		}

		echo $output[1];
	}
}
?>