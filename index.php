<?php
require "vendor/autoload.php";

use DTCM\DTCM;
use DTCM\DTCMRequest;
use DTCM\Authentication\AccessToken;
use DTCM\HttpClients\DTCMCurl;
use DTCM\HttpClients\DTCMCurlHttpClient;
use DTCM\HttpClients\HttpClientsFactory;

// $client = HttpClientsFactory::createHttpClient('curl');

// $header = array('Accept' => 'application/json');

// $extra_options = array(
//     CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
//     CURLOPT_USERPWD => "27403b7cee244a6e9e24423d087530df:dd3e7736694640c9b5d561c398efc871"
// );

// $output = $client->send('https://api.etixdubai.com/oauth2/accesstoken', 'POST', 'grant_type=client_credentials', $header, 30,  $extra_options);

// $date = new DateTime();
// $date->add(new DateInterval('PT24H30S'));

// $AccessToken = $output->getBody();
// $AccessToken->expiresAt = $date->getTimestamp();

// $AccessToken = new stdClass();
$AccessToken = "65cd173f593449f694a8133539493151";
// $AccessToken->type = "Bearer";
// $AccessToken->scope = "https://api.etixdubai.com/performances.* https://api.etixdubai.com/baskets.* https://api.etixdubai.com/orders.* https://api.etixdubai.com/inventory.* https://api.etixdubai.com/customers.* https://api.etixdubai.com/tixscan.*";
// $AccessToken->expiresAt = 1480938863;
//$performances = $DTCM->sendRequest('GET', 'https://api.etixdubai.com/performances/ETES3EL/prices', ['channel' => 'W', 'sellerCode' => 'ATAPE1']);

$accesstoken = new AccessToken($AccessToken);

$DTCM = new DTCM(array('AccessToken' => $accesstoken));


$output = $DTCM->sendRequest('POST', 'https://api.etixdubai.com/customers', ['sellerCode' => 'ATAPE1'], [], ["salutation" => "Mr","firstname" => "ajilan","lastname" => "MA ","nationality" => "US","email" => "unknown@1unknown.com","dateofbirth" => "4-23-2015","internationalcode" => "001","areacode" => "001","phonenumber" => "507156120","addressline1" => "-","addressline2" => "-","addressline3" => "-","city" => "dubai","state" => "dubai","countrycode" => "AE"], true);

print_r($output);