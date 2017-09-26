<?php
namespace SearchEngineAggregator\Tests;

use InvalidArgumentException;
use League\Uri\Schemes\Http as HttpUri;
use Mockery;
use PHPUnit\Framework\TestCase;
use SearchEngineAggregator\EngineAggregator;
use SearchEngineAggregator\Engine\EngineIdentifier;
use SearchEngineAggregator\Engine\EngineIdentifierList;
use SearchEngineAggregator\Engine\GeneratedStaticResultEngine;
use SearchEngineAggregator\Result\Result;
use SearchEngineAggregator\Result\ResultList;
use SplObjectStorage;

/**
 * Description of EngineAggregatorTest
 * 
 * @author Balázs Máté Petró <petrobalazsmate@gmail.com>
 */
class EngineAggregatorTest extends TestCase
{
    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage The engines argument cannot be empty!
     */
    public function testConstructThrowsExceptionWhenTheEnginesArgumentIsAnEmptyArray()
    {
        new EngineAggregator([]);
    }

    /**
     * @expectedException InvalidArgumentException
     * @excpectedExceptionMessage All items must be an instance of Engine!
     */
    public function testConstructThrowsExceptionWhenOneOfTheEnginesIsNotAnEngineIdentifierInstance()
    {
        new EngineAggregator([
            EngineIdentifier::fromString('engine-1'),
            'engine',
            EngineIdentifier::fromString('engine-2')
        ]);
    }

    public function testSearchExecutesSearchMethodOfEngines()
    {
        $results1 = new ResultList(new SplObjectStorage);
        $results2 = new ResultList(new SplObjectStorage);

        $engine1 = Mockery::mock(GeneratedStaticResultEngine::class);
        $engine1->shouldReceive('search')->once()->andReturn($results1);
        $engine2 = Mockery::mock(GeneratedStaticResultEngine::class);
        $engine2->shouldReceive('search')->once()->andReturn($results2);

        $results = (new EngineAggregator([$engine1, $engine2]))->search('keyword');

        Mockery::close();
    }

    public function testSearchMergesResultsFromEngines()
    {
        $engineIdentifier1 = EngineIdentifier::fromString('engine-1');
        $engineIdentifier2 = EngineIdentifier::fromString('engine-2');

        $engineIdentifierList1 = EngineIdentifierList::fromIdentifier($engineIdentifier1);
        $engineIdentifierList2 = EngineIdentifierList::fromIdentifier($engineIdentifier2);

        $results1 = new ResultList(new SplObjectStorage);
        $results2 = new ResultList(new SplObjectStorage);

        $uri = HttpUri::createFromString('http://google.com/');

        $results1->add(new Result('Result - 1', $uri, $engineIdentifierList1));
        $results1->add(new Result('Result - 2', $uri, $engineIdentifierList1));

        $results2->add(new Result('Result - 1', $uri, $engineIdentifierList2));
        $results2->add(new Result('Result - 3', $uri, $engineIdentifierList2));

        $engine1 = Mockery::mock(GeneratedStaticResultEngine::class);
        $engine1->shouldReceive('search')->once()->andReturn($results1);
        $engine2 = Mockery::mock(GeneratedStaticResultEngine::class);
        $engine2->shouldReceive('search')->once()->andReturn($results2);

        $results = (new EngineAggregator([$engine1, $engine2]))->search('keyword');

        $resultItems = $results->all();

        $uriHash = spl_object_hash($uri);

        $this->assertCount(3, $resultItems);

        $this->assertEquals($resultItems[0]->getTitle(), 'Result - 1');
        $this->assertEquals($uriHash, spl_object_hash($resultItems[0]->getUri()));
        $this->assertCount(2, $resultItems[0]->getIdentifiers());
        $this->assertTrue($resultItems[0]->hasIdentifier($engineIdentifier1));
        $this->assertTrue($resultItems[0]->hasIdentifier($engineIdentifier2));

        $this->assertEquals($resultItems[1]->getTitle(), 'Result - 2');
        $this->assertEquals($uriHash, spl_object_hash($resultItems[1]->getUri()));
        $this->assertCount(2, $resultItems[1]->getIdentifiers());
        $this->assertTrue($resultItems[1]->hasIdentifier($engineIdentifier1));
        $this->assertTrue($resultItems[1]->hasIdentifier($engineIdentifier2));

        $this->assertEquals($resultItems[2]->getTitle(), 'Result - 3');
        $this->assertEquals($uriHash, spl_object_hash($resultItems[2]->getUri()));
        $this->assertCount(1, $resultItems[2]->getIdentifiers());
        $this->assertTrue($resultItems[2]->hasIdentifier($engineIdentifier2));

        Mockery::close();
    }
}
