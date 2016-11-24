<?php
require "vendor/autoload.php";

use DTCM\DTCM;
use DTCM\DTCMRequest;
use DTCM\Authentication\AccessToken;
use DTCM\HttpClients\DTCMCurl;
use DTCM\HttpClients\DTCMCurlHttpClient;
use DTCM\HttpClients\HttpClientsFactory;


$date = new DateTime();
$date->add(new DateInterval('PT24H30S'));

$accesstoken = new AccessToken('977fa834378942128730c09ec8a3a711', $date->getTimestamp(), 'Bearer', 'https://api.etixdubai.com/performances.* https://api.etixdubai.com/baskets.* https://api.etixdubai.com/orders.* https://api.etixdubai.com/inventory.* https://api.etixdubai.com/customers.* https://api.etixdubai.com/tixscan.*');


//print_r($accesstoken);die;

$DTCM = new DTCM(array('AccessToken' => $accesstoken));
$DTCM->sendRequest('GET', 'https://api.etixdubai.com/performances/ETES3EL/prices', ['channel' => 'W', 'sellerCode' => 'ATAPE1']);
// print_r($DTCM);
//$DTCMRequest = new DTCMRequest($accesstoken, 'GET', 'https://api.etixdubai.com/performances/ETES3EL/prices');
// print_r($DTCMRequest);die;





// curl –X GET https://api.etixdubai.com/performances/ETES3EL/prices?channel=W&sellerCode=ATAPE1 -H "Content-Type: application/json" -H "Authorization: Bearer 977fa834378942128730c09ec8a3a711"




// $client = HttpClientsFactory::createHttpClient('curl');

// $header = array('Accept' => 'application/json');

// $extra_options = array(
//     CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
//     CURLOPT_USERPWD => "27403b7cee244a6e9e24423d087530df:dd3e7736694640c9b5d561c398efc871"
// );

// $output = $client->send('https://api.etixdubai.com/oauth2/accesstoken', 'POST', 'grant_type=client_credentials', $header, 30,  $extra_options);
// print_r($output->getBody());die;



// var_dump($DTCM);

//$accesstoken


/* ------------------------------------------- */

// $header += array('Accept' => 'application/json');

// $extra_options = array(
//     CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
//     CURLOPT_USERPWD => "27403b7cee244a6e9e24423d087530df:dd3e7736694640c9b5d561c398efc871"
// );

// $output = $client->send('https://api.etixdubai.com/oauth2/accesstoken', 'POST', 'grant_type=client_credentials', $header, 30,  $extra_options);





// stdClass Object
// (
//     [access_token] => 977fa834378942128730c09ec8a3a711
//     [token_type] => Bearer
//     [expires_in] => 86399
//     [scope] => https://api.etixdubai.com/performances.* https://api.etixdubai.com/baskets.* https://api.etixdubai.com/orders.* https://api.etixdubai.com/inventory.* https://api.etixdubai.com/customers.* https://api.etixdubai.com/tixscan.*
// )