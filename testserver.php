<?php
/**
 * Rafflesia - test.php
 * Date: 9/23/2016
 */

namespace Rafflesia;

require_once("src/RafflesiaServer.php");
require_once("src/RafflesiaClient.php");
require_once("src/RafflesiaException.php");

$server = new RafflesiaServer();
$server->listen();