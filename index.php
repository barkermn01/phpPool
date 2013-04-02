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
$serverControl->handleProxy();
?>