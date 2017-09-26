<?php
namespace SearchEngineAggregator\Tests\Engine;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use SearchEngineAggregator\Engine\EngineIdentifier;
use SearchEngineAggregator\Engine\EngineIdentifierList;
use SearchEngineAggregator\Engine\EngineIdentifierListInterface;

/**
 * Description of EngineIdentifierListTest
 * 
 * @author Balázs Máté Petró <petrobalazsmate@gmail.com>
 */
class EngineIdentifierListTest extends TestCase
{
    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage All items must be an instance of EngineIdentifierInterface!
     */
    public function testConstructThrowsExceptionWhenOneOfTheItemIsNotAnEngineIdentifier()
    {
        new EngineIdentifierList([
            new EngineIdentifier('engine-1'),
            null,
            new EngineIdentifier('engine-2')
        ]);
    }

    public function testConstructCreatesEmptyListWhenNoArgumentGiven()
    {
        $this->assertInstance(new EngineIdentifierList);
    }

    public function testConstructCreatesEmptyListWhenTheGivenArgumentIsAnEmptyArray()
    {
        $this->assertInstance(new EngineIdentifierList([]));
    }

    public function testConstructCreatesListWithTheGivenEngineIdentifiers()
    {
        $engineIdentifiers = [
            new EngineIdentifier('engine-1'),
            new EngineIdentifier('engine-2')
        ];

        $engineIdentifierList = new EngineIdentifierList($engineIdentifiers);

        $this->assertInstance($engineIdentifierList);
        $this->assertCount(2, $engineIdentifierList);
        $this->assertTrue($engineIdentifierList->has($engineIdentifiers[0]));
        $this->assertTrue($engineIdentifierList->has($engineIdentifiers[1]));
    }

    public function testFromIdentifierMustCallAddMethod()
    {
        $engineIdentifier = new EngineIdentifier('engine-1');

        $engineIdentifierList = EngineIdentifierList::fromIdentifier($engineIdentifier);

        $this->assertInstance($engineIdentifierList);
        $this->assertCount(1, $engineIdentifierList);
        $this->assertTrue($engineIdentifierList->has($engineIdentifier));
    }

    public function testAddAddsTheGivenIdentifierToTheList()
    {
        $engineIdentifier = new EngineIdentifier('engine-1');

        $engineIdentifierList = new EngineIdentifierList;

        $this->assertFalse($engineIdentifierList->has($engineIdentifier));

        $engineIdentifierList->add($engineIdentifier);

        $this->assertTrue($engineIdentifierList->has($engineIdentifier));
    }

    public function testAddDoesNotAddsMultipleTimesTheGivenListItemToTheList()
    {
        $engineIdentifier = new EngineIdentifier('engine-1');

        $engineIdentifierList = EngineIdentifierList::fromIdentifier($engineIdentifier);

        $this->assertTrue($engineIdentifierList->has($engineIdentifier));
        $this->assertCount(1, $engineIdentifierList);

        $engineIdentifierList->add($engineIdentifier);

        $this->assertCount(1, $engineIdentifierList);
    }

    public function testAddReturnsTheListItself()
    {
        $engineIdentifierList = new EngineIdentifierList;

        $engineIdentifier = new EngineIdentifier('engine-1');

        $this->assertInstance($engineIdentifierList->add($engineIdentifier));
        $this->assertTrue($engineIdentifierList->has($engineIdentifier));
        $this->assertCount(1, $engineIdentifierList);
    }

    public function testHasReturnsFalseWhenTheGivenListDoesNotContainTheGivenItem()
    {
        $engineIdentifier = new EngineIdentifier('engine-1');

        $this->assertFalse((new EngineIdentifierList)->has($engineIdentifier));
    }

    public function testHasReturnsTrueWhenTheGivenListContainsTheGivenItem()
    {
        $engineIdentifier = new EngineIdentifier('engine-1');

        $engineIdentifierList = EngineIdentifierList::fromIdentifier($engineIdentifier);

        $this->assertTrue($engineIdentifierList->has($engineIdentifier));
    }

    public function testRemoveRemovesTheGivenItemFromTheList()
    {
        $engineIdentifier = new EngineIdentifier('engine-1');

        $engineIdentifierList = EngineIdentifierList::fromIdentifier($engineIdentifier);

        $engineIdentifierList->remove($engineIdentifier);

        $this->assertFalse($engineIdentifierList->has($engineIdentifier));
        $this->assertCount(0, $engineIdentifierList);
    }

    public function testRemoveDoesNotRemoveItemThatDoesNotExistInTheList()
    {
        $engineIdentifier = new EngineIdentifier('engine-1');

        $engineIdentifierList = new EngineIdentifierList;

        $this->assertFalse($engineIdentifierList->has($engineIdentifier));

        $engineIdentifierList->remove($engineIdentifier);

        $this->assertFalse($engineIdentifierList->has($engineIdentifier));
        $this->assertCount(0, $engineIdentifierList);
    }

    public function testRemoveReturnsTheListItself()
    {
        $engineIdentifier = new EngineIdentifier('engine-1');

        $engineIdentifierList = EngineIdentifierList::fromIdentifier($engineIdentifier);

        $this->assertInstance($engineIdentifierList->remove($engineIdentifier));
        $this->assertFalse($engineIdentifierList->has($engineIdentifier));
        $this->assertCount(0, $engineIdentifierList);
    }

    public function testAllReturnsAllOfTheIdentifiers()
    {
        $engineIdentifiers = [
            new EngineIdentifier('engine-1'),
            new EngineIdentifier('engine-2')
        ];

        $engineIdentifierList = new EngineIdentifierList($engineIdentifiers);

        $this->assertEquals($engineIdentifiers, $engineIdentifierList->all());
    }

    public function testCountReturnsTheProperValue()
    {
        $engineIdentifiers = [
            new EngineIdentifier('engine-1'),
            new EngineIdentifier('engine-2')
        ];

        $engineIdentifierList = new EngineIdentifierList($engineIdentifiers);

        $this->assertCount(count($engineIdentifiers), $engineIdentifierList);
    }

    private function assertInstance($instance)
    {
        $this->assertInstanceOf(EngineIdentifierList::class, $instance);
        $this->assertInstanceOf(EngineIdentifierListInterface::class, $instance);
    }
}
