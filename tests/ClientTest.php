<?php
namespace ExchangeGroup\Tests;

use ExchangeGroup\Client;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Subscriber\History;
use GuzzleHttp\Subscriber\Mock;
use GuzzleHttp\Message\Response;


/**
 * @covers ExchangeGroup\Client
 */
class ClientTest extends \PHPUnit_Framework_TestCase
{
    function testGet() {
        $gClient = new GuzzleClient();
        $history = new History();
        $gClient->getEmitter()->attach($history);
        $mock = new Mock([new Response(200)]);
        $gClient->getEmitter()->attach($mock);

        $client = new Client('foo', 'bar', $gClient);
        $client->get('/api/v1/client/adverts');

        $request = $history->getLastRequest();
        $this->assertEquals($request->getMethod(), 'GET');
        $this->assertEquals($request->getPath(), '/api/v1/client/adverts');
    }

    function testPut() {
        $gClient = new GuzzleClient();
        $history = new History();
        $gClient->getEmitter()->attach($history);
        $mock = new Mock([new Response(200)]);
        $gClient->getEmitter()->attach($mock);

        $client = new Client('foo', 'bar', $gClient);
        $client->put('/api/v1/client/adverts/1', ['barcode' => '1234']);

        $request = $history->getLastRequest();
        $this->assertEquals($request->getMethod(), 'PUT');
        $this->assertEquals($request->getPath(), '/api/v1/client/adverts/1');
        $this->assertEquals($request->getHeader('Content-Type'), 'application/json');

        $requestData = json_decode($request->getBody());
        $this->assertEquals($requestData->barcode, '1234');
    }

    public function testCollectionToArray() {
        $input = [
            123 => ['title' => 'My Advert'],
            124 => ['title' => 'My Second Advert']
        ];

        $client = new Client(null, null);
        $this->assertEquals(
            $client->collectionToArray($input),
            [
                ['id' => 123, 'title' => 'My Advert'],
                ['id' => 124, 'title' => 'My Second Advert']
            ]
        );
    }
}
