<?php
require_once("req/ServerComs.php");
require_once("req/ServerControl.php");
require_once("req/ConfigReader.php");
require_once("req/Database.php");

$configReader = new ConfigReader();
$conf = $configReader->readConfigFile("config.json");

$db = new Database($conf->database->host, $conf->database->user, $conf->database->pass, $conf->database->db);

$serverControl = new ServerControl($conf);
$serverControl->setDatabaseConnection($db);

$serverControl->checkLogin();
if($_SERVER['HTTP_HOST'] == $conf->pool->mine_host){
	$serverControl->handleProxy();
}elseif($_SERVER['HTTP_HOST'] == $conf->pool->site_host){
	?>
		<h1>Under Construction</h1>
		<p>
			Currently phpPool does not have a working Web Interface <br />
			Check out our repo on GitHub <a href="https://github.com/barkermn01/phpPool">https://github.com/barkermn01/phpPool</a><br />
			or you can donate to help support this project: 19rzPNQ9SScBxRZgu5rttmNEtBnpt9iEKT
		</p>
	<?php
}
?>