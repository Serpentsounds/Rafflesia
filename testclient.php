<?php
/**
 * Rafflesia - testclient.php
 * Date: 9/23/2016
 */

namespace Rafflesia;

require_once("src/RafflesiaMessage.php");
require_once("src/RafflesiaClient.php");
require_once("src/RafflesiaException.php");

$client = new RafflesiaClient();
$response = $client->send("enemy quadra gay");
echo $response->getResponse(). "\n";