<?php
namespace SearchEngineAggregator\Tests\Result;

use League\Uri\Schemes\Http as HttpUri;
use Mockery;
use PHPUnit\Framework\TestCase;
use SearchEngineAggregator\Engine\EngineIdentifier;
use SearchEngineAggregator\Engine\EngineIdentifierList;
use SearchEngineAggregator\Result\Result;
use SearchEngineAggregator\Result\ResultList;
use SearchEngineAggregator\Result\ResultsInterface;
use SplObjectStorage;
use Traversable;

/**
 * Description of ResultListTest
 * 
 * @author Balázs Máté Petró <petrobalazsmate@gmail.com>
 */
class ResultListTest extends TestCase
{
    private $resultList;

    public function setUp()
    {
        $this->resultList = new ResultList(new SplObjectStorage);
    }

    public function testConstructCreatesAnInstance()
    {
        $this->assertInstance($this->resultList);
    }

    public function testAddAddsResultItemToTheList()
    {
        $this->assertCount(0, $this->resultList);

        $result = $this->createResultItem();

        $this->resultList->add($result);

        $this->assertTrue($this->resultList->has($result));
        $this->assertCount(1, $this->resultList);
    }

    public function testAddDoesNotAddMultipleTimesTheSameResultToTheList()
    {
        $this->assertCount(0, $this->resultList);

        $result = $this->createResultItem();

        $this->resultList->add($result);
        $this->resultList->add($result);

        $this->assertTrue($this->resultList->has($result));
        $this->assertCount(1, $this->resultList);
    }

    public function testAddReturnsResultListInstance()
    {
        $this->assertCount(0, $this->resultList);
        
        $result = $this->resultList->add($this->createResultItem());

        $this->assertInstance($result);
    }

    public function testHasReturnsFalseWhenTheResultListDoesNotContainTheGivenResultItem()
    {
        $result = $this->createResultItem();

        $this->assertFalse($this->resultList->has($result));
    }

    public function testHasReturnsTrueWhenTheResultListContainsTheGivenResultItem()
    {
        $result = $this->createResultItem();

        $this->resultList->add($result);

        $this->assertTrue($this->resultList->has($result));
    }

    public function testRemoveRemovesResultItemFromTheList()
    {
        $this->assertCount(0, $this->resultList);

        $result = $this->createResultItem();

        $this->resultList->add($result);

        $this->assertTrue($this->resultList->has($result));
        $this->assertCount(1, $this->resultList);

        $this->resultList->remove($result);

        $this->assertFalse($this->resultList->has($result));
        $this->assertCount(0, $this->resultList);
    }

    public function testRemoveDoesNothingWhenTryingToRemoveResultItemThatDoesNotBelongToTheList()
    {
        $result = $this->createResultItem();

        $this->assertFalse($this->resultList->has($result));
        $this->assertCount(0, $this->resultList);

        $this->resultList->remove($result);

        $this->assertFalse($this->resultList->has($result));
        $this->assertCount(0, $this->resultList);
    }

    public function testRemoveReturnsResultListInstance()
    {
        $result = $this->resultList->remove($this->createResultItem());

        $this->assertInstance($result);
    }

    public function testAllReturnsAnArrayOfResultItems()
    {
        $this->resultList->add($this->createResultItem('Item - 1'));
        $this->resultList->add($this->createResultItem('Item - 2', 'http://example.com/'));

        $result = $this->resultList->all();

        $this->assertTrue(is_array($result));
        $this->assertInstanceOf(Result::class, $result[0]);
        $this->assertInstanceOf(Result::class, $result[1]);
        $this->assertEquals('Item - 1', $result[0]->getTitle());
        $this->assertEquals('Item - 2', $result[1]->getTitle());
    }

    public function testMergeReturnsMergedResult()
    {
        $target = new ResultList(new SplObjectStorage);
        $from = new ResultList(new SplObjectStorage);

        $merged = $target->merge($from);

        $this->assertInstanceOf(ResultList::class, $merged);
        $this->assertInstanceOf(ResultsInterface::class, $merged);
        $this->assertEquals(spl_object_hash($target), spl_object_hash($merged));
    }

    public function testMergeAddsNonExistingResultItemToTheTarget()
    {
        $target = new ResultList(new SplObjectStorage);
        $from = new ResultList(new SplObjectStorage);

        $uri = HttpUri::createFromString('http://example.com');
        $googleEngine = EngineIdentifier::fromString('google');
        $engineIdentifierList = EngineIdentifierList::fromIdentifier($googleEngine);

        $results = [
            new Result('Item - 1', $uri, $engineIdentifierList),
            new Result('Item - 2', $uri, $engineIdentifierList),
            new Result('Item - 3', $uri, $engineIdentifierList)
        ];

        $from->add($results[0]);
        $from->add($results[1]);
        $from->add($results[2]);

        $this->assertCount(0, $target);
        $this->assertCount(3, $from);

        $target->merge($from);

        $this->assertCount(3, $target);
        $this->assertCount(3, $from);
        $this->assertTrue($target->has($results[0]));
        $this->assertTrue($target->has($results[1]));
        $this->assertTrue($target->has($results[2]));
    }

    public function testMergeAddsEngineIdentifierToTheSameResult()
    {
        $target = new ResultList(new SplObjectStorage);
        $from = new ResultList(new SplObjectStorage);

        $uri = HttpUri::createFromString('http://example.com');

        $googleEngine = EngineIdentifier::fromString('google');
        $yahooEngine = EngineIdentifier::fromString('yahoo');

        $googleEngineList = EngineIdentifierList::fromIdentifier($googleEngine);
        $yahooEngineList = EngineIdentifierList::fromIdentifier($yahooEngine);

        $googleResults = [
            new Result('Result - 1', $uri, $googleEngineList),
            new Result('Result - 2', $uri, $googleEngineList)
        ];
        $yahooResults = [
            new Result('Result - 1', $uri, $yahooEngineList),
            new Result('Result - 2', $uri, $yahooEngineList)
        ];

        $target->add($googleResults[0]);
        $target->add($googleResults[1]);

        $from->add($yahooResults[0]);
        $from->add($yahooResults[1]);

        $this->assertCount(2, $target);
        $this->assertCount(2, $from);

        $this->assertCount(1, $googleResults[0]->getIdentifiers());
        $this->assertCount(1, $googleResults[1]->getIdentifiers());

        $this->assertCount(1, $yahooResults[0]->getIdentifiers());
        $this->assertCount(1, $yahooResults[1]->getIdentifiers());

        $target->merge($from);

        $this->assertCount(2, $target);
        $this->assertCount(2, $from);

        $this->assertTrue($target->has($googleResults[0]));
        $this->assertTrue($target->has($googleResults[1]));
        $this->assertTrue($target->has($yahooResults[0]));

        $mergedResults = $target->all();

        $this->assertCount(2, $mergedResults[0]->getIdentifiers());
        $this->assertTrue($mergedResults[0]->hasIdentifier($googleEngine));
        $this->assertTrue($mergedResults[0]->hasIdentifier($yahooEngine));

        $this->assertCount(2, $mergedResults[1]->getIdentifiers());
        $this->assertTrue($mergedResults[1]->hasIdentifier($googleEngine));
        $this->assertTrue($mergedResults[1]->hasIdentifier($yahooEngine));
    }

    public function testMergeMergesResultsProperly()
    {
        $target = new ResultList(new SplObjectStorage);
        $from = new ResultList(new SplObjectStorage);

        $uri = HttpUri::createFromString('http://example.com');
        
        $googleEngine = EngineIdentifier::fromString('google');
        $yahooEngine = EngineIdentifier::fromString('yahoo');

        $googleEngineList = EngineIdentifierList::fromIdentifier($googleEngine);
        $yahooEngineList = EngineIdentifierList::fromIdentifier($yahooEngine);

        $googleResults = [
            new Result('Result - 1', $uri, $googleEngineList),
            new Result('Result - 2', $uri, $googleEngineList)
        ];
        $yahooResults = [
            new Result('Result - 1', $uri, $yahooEngineList),
            new Result('Result - 2', $uri, $yahooEngineList),
            new Result('Result - 3', $uri, $yahooEngineList)
        ];

        $target->add($googleResults[0]);
        $target->add($googleResults[1]);

        $from->add($yahooResults[0]);
        $from->add($yahooResults[1]);
        $from->add($yahooResults[2]);

        $this->assertCount(2, $target);
        $this->assertCount(3, $from);

        $this->assertCount(1, $googleResults[0]->getIdentifiers());
        $this->assertCount(1, $googleResults[1]->getIdentifiers());

        $this->assertCount(1, $yahooResults[0]->getIdentifiers());
        $this->assertCount(1, $yahooResults[1]->getIdentifiers());
        $this->assertCount(1, $yahooResults[2]->getIdentifiers());

        $target->merge($from);

        $this->assertCount(3, $target);
        $this->assertCount(3, $from);

        $this->assertTrue($target->has($googleResults[0]));
        $this->assertTrue($target->has($googleResults[1]));
        $this->assertTrue($target->has($yahooResults[0]));

        $mergedResults = $target->all();

        $this->assertCount(2, $mergedResults[0]->getIdentifiers());
        $this->assertTrue($mergedResults[0]->hasIdentifier($googleEngine));
        $this->assertTrue($mergedResults[0]->hasIdentifier($yahooEngine));

        $this->assertCount(2, $mergedResults[1]->getIdentifiers());
        $this->assertTrue($mergedResults[1]->hasIdentifier($googleEngine));
        $this->assertTrue($mergedResults[1]->hasIdentifier($yahooEngine));

        $this->assertCount(1, $mergedResults[2]->getIdentifiers());
        $this->assertTrue($mergedResults[2]->hasIdentifier($yahooEngine));
    }

    public function testMergeDoesNotMutateFromList()
    {
        $target = new ResultList(new SplObjectStorage);
        $from = new ResultList(new SplObjectStorage);

        $uri = HttpUri::createFromString('http://example.com');
        
        $googleEngine = EngineIdentifier::fromString('google');
        $yahooEngine = EngineIdentifier::fromString('yahoo');

        $googleEngineList = EngineIdentifierList::fromIdentifier($googleEngine);
        $yahooEngineList = EngineIdentifierList::fromIdentifier($yahooEngine);

        $googleResults = [
            new Result('Result - 1', $uri, $googleEngineList),
            new Result('Result - 2', $uri, $googleEngineList)
        ];
        $yahooResults = [
            new Result('Result - 1', $uri, $yahooEngineList),
            new Result('Result - 2', $uri, $yahooEngineList)
        ];

        $target->add($googleResults[0]);
        $target->add($googleResults[1]);

        $from->add($yahooResults[0]);
        $from->add($yahooResults[1]);

        $target->merge($from);
        
        $this->assertCount(2, $target);
        $this->assertCount(2, $from);

        $targetResults = $target->all();
        $fromResults = $from->all();

        $this->assertTrue($targetResults[0]->hasIdentifier($googleEngine));
        $this->assertTrue($targetResults[0]->hasIdentifier($yahooEngine));
        $this->assertTrue($targetResults[1]->hasIdentifier($googleEngine));
        $this->assertTrue($targetResults[1]->hasIdentifier($yahooEngine));

        $this->assertFalse($fromResults[0]->hasIdentifier($googleEngine));
        $this->assertFalse($fromResults[1]->hasIdentifier($googleEngine));
    }

    public function testGetIteratorReturnsTraversableObject()
    {
        $this->assertInstanceOf(Traversable::class, $this->resultList->getIterator());
    }

    public function testCountReturnsCorrespondingValue()
    {
        $this->assertCount(0, $this->resultList);

        $this->resultList->add($this->createResultItem());

        $this->assertCount(1, $this->resultList);
    }

    private function assertInstance($instance)
    {
        $this->assertInstanceOf(ResultList::class, $instance);
        $this->assertInstanceOf(ResultsInterface::class, $instance);
    }

    private function createResultItem($title = 'Item - 1', $httpUrl = 'https://foo.example.com/')
    {
        $uri = HttpUri::createFromString($httpUrl);
        $engineIdentifierList = new EngineIdentifierList([
            new EngineIdentifier('engine-1')
        ]);

        return new Result($title, $uri, $engineIdentifierList);
    }
}
