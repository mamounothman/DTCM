<?php
require "vendor/autoload.php";

use DTCM\DTCM;
use DTCM\DTCMRequest;
use DTCM\Authentication\AccessToken;
use DTCM\HttpClients\DTCMCurl;
use DTCM\HttpClients\DTCMCurlHttpClient;
use DTCM\HttpClients\HttpClientsFactory;

$client = HttpClientsFactory::createHttpClient('curl');

$header = array('Accept' => 'application/json');

$extra_options = array(
    CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
    CURLOPT_USERPWD => "27403b7cee244a6e9e24423d087530df:dd3e7736694640c9b5d561c398efc871"
);

$output = $client->send('https://api.etixdubai.com/oauth2/accesstoken', 'POST', 'grant_type=client_credentials', $header, 30,  $extra_options);

$date = new DateTime();
$date->add(new DateInterval('PT24H30S'));



$AccessToken = $output->getBody();
$AccessToken->expiresAt = $date->getTimestamp();

$accesstoken = new AccessToken($AccessToken);

$DTCM = new DTCM(array('AccessToken' => $accesstoken));
$DTCM->sendRequest('GET', 'https://api.etixdubai.com/performances/ETES3EL/prices', ['channel' => 'W', 'sellerCode' => 'ATAPE1']);
