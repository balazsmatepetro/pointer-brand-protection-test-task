<?php
namespace SearchEngineAggregator\Tests\Engine;

use Mockery;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\UriInterface;
use SearchEngineAggregator\Engine\GoogleEngine;
use Serps\Core\Browser\Browser;
use Serps\Core\Serp\BaseResult;
use Serps\Core\Serp\IndexedResultSet;
use Serps\HttpClient\CurlClient;
use Serps\SearchEngine\Google\GoogleClient;
use Serps\SearchEngine\Google\GoogleUrl;
use Serps\SearchEngine\Google\Page\GoogleSerp;

/**
 * Description of GoogleEngineTest
 * 
 * @author Balázs Máté Petró <petrobalazsmate@gmail.com>
 */
class GoogleEngineTest extends TestCase
{
    private $client;

    private $url;

    public function setUp()
    {
        $browser = new Browser(
            new CurlClient,
            'Mozilla/5.0 (Windows NT 10.0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/40.0.2214.93 Safari/537.36',
            'en-GB'
        );

        $resultMock1 = new BaseResult('type', [
            'title' => 'Title',
            'url' => 'http://example.com/'
        ]);

        $resultSet = new IndexedResultSet();
        $resultSet->addItem($resultMock1);

        $queryResultMock = Mockery::mock(GoogleSerp::class);
        $queryResultMock->shouldReceive('getNaturalResults')->once()->andReturn($resultSet);

        $clientMock = Mockery::mock(GoogleClient::class);
        $clientMock->shouldReceive('query')->once()->andReturn($queryResultMock);

        $this->client = $clientMock;
    }

    public function testSearchReturnsResultList()
    {
        $results = (new GoogleEngine($this->client, new GoogleUrl))->search('keyword')->all();

        $this->assertEquals('Title', $results[0]->getTitle());
        $this->assertInstanceOf(UriInterface::class, $results[0]->getUri());
        $this->assertCount(1, $results[0]->getIdentifiers());
        $this->assertEquals('google', $results[0]->getIdentifiers()[0]->getIdentifier());
    }
}
