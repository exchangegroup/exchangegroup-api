Exchange Group API V1 Client
=======================

PHP API client for http://www.bikeexchange.com.au/, http://www.tinitrader.com.au/, http://www.renoexchange.com.au/, http://www.furnitureexchange.com.au/, http://www.bikeexchange.co.nz, http://www.bikeexchange.de and http://www.bikeexchange.com

All response data is provided as associative arrays for ease of handling

```php
<?php

require('vendor/autoload.php');

$client = new ExchangeGroup\Client('username', 'password');

// retrieving more information about one random advert from that list
$adverts = $client->getAdverts();

// updating the title and price of one random advert from that list
$randomAdvert = array_rand($adverts, 1);

$client->updateAdvert(
    $advertToUpdate['id'],
    array('title' => 'New Product Title', 'price' => '12.34')
);

// retrieving a list of variants from the server
$variants = $client->getVariants();

// retrieving more information about one variant advert from that list
$randomVariant =  array_rand($variants, 1);
$variantDetails = $client->getVariant($randomVariant['id']);

// and from the list attached to the advert
foreach($randomAdvert['variant_ids'] as $variantId) {
    var_dump($client->getVariant($variantId));
}

// OR
$advertVariants = array_map(
    function($id) use ($client) { return $client->getVariant($id); },
    $randomAdvert['variant_ids']
);

// updating the count_on_hand and sku of one of those variants
$client->updateVariant(
    $advertVariants[0]['id'],
    array('count_on_hand' => 1, 'sku' => 'A3334C')
);
```

Handling API Errors
--------------

If the API returns an errors object, the client will throw `ExchangeGroup\ClientException`. This exception has all request errors as a message, but also offers a public `errors` property for iterating over the errors.

```php
try {
    $client->updateVariant(
        1234,
        array('count_on_hand' => -3, 'sku' => 'A3334C')
    );
} catch(ExchangeGroup\ClientException $e) {
    // "Errors in API request - count_on_hand: must be greater than zero"
    echo($e->getMessage());
}
```
