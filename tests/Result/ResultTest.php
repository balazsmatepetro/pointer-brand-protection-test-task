<?php
namespace SearchEngineAggregator\Tests\Result;

use Exception;
use League\Uri\Schemes\Http as HttpUri;
use PHPUnit\Framework\TestCase;
use SearchEngineAggregator\Engine\EngineIdentifier;
use SearchEngineAggregator\Engine\EngineIdentifierList;
use SearchEngineAggregator\Result\Result;
use SearchEngineAggregator\Result\ResultInterface;

/**
 * Description of ResultTest
 * 
 * @author Balázs Máté Petró <petrobalazsmate@gmail.com>
 */
class ResultTest extends TestCase
{
    private $result;

    public function setUp()
    {
        $title = $this->getDefaultTitle();
        $uri = $this->getDefaultUri();
        $engineIdentifierList = $this->getDefaultEngineIdentifierList();
        
        $this->result = new Result($title, $uri, $engineIdentifierList);
    }

    public function testConstructCreatesAnInstance()
    {
        $this->assertInstance($this->result);
    }

    public function testGetTitleReturnsTitle()
    {
        $this->assertEquals($this->getDefaultTitle(), $this->result->getTitle());
    }

    public function testGetUriReturnsUri()
    {
        $this->assertEquals($this->getDefaultUri(), $this->result->getUri());
    }

    public function testEqualsReturnsTrueWhenTheResultsAreTotallyTheSame()
    {
        $result = new Result(
            $this->getDefaultTitle(),
            $this->getDefaultUri(),
            $this->getDefaultEngineIdentifierList()
        );

        $this->assertTrue($this->result->equals($result));
    }

    public function testEqualsReturnsFalseWhenTheTitlesAreNotEqual()
    {
        $result = new Result('A new result', $this->getDefaultUri(), $this->getDefaultEngineIdentifierList());

        $this->assertFalse($this->result->equals($result));
    }

    public function testEqualsReturnsFalseWhenTheUrisAreNotEqual()
    {
        $result = new Result(
            $this->getDefaultTitle(),
            HttpUri::createFromString('https://foo.example.com/'),
            $this->getDefaultEngineIdentifierList()
        );

        $this->assertFalse($this->result->equals($result));
    }

    public function testAddIdentifierAddsIdentifierToTheResultIfTheIdentifierDoesNotBelongToTheResult()
    {
        $this->assertEquals(1, count($this->result->getIdentifiers()));

        $engineIdentifier = new EngineIdentifier('engine-1');

        $this->result->addIdentifier($engineIdentifier);

        $this->assertTrue($this->result->hasIdentifier($engineIdentifier));
        $this->assertEquals(2, count($this->result->getIdentifiers()));
    }

    public function testAddIdentifierDoesNotAddIdentifierToTheResultIfTheIdentifierBelongsToTheResult()
    {
        $engineIdentifier = $this->result->getIdentifiers()[0];

        $this->assertEquals(1, count($this->result->getIdentifiers()));
        $this->assertTrue($this->result->hasIdentifier($engineIdentifier));

        $this->result->addIdentifier($engineIdentifier);

        $this->assertEquals(1, count($this->result->getIdentifiers()));
    }

    public function testAddIdentifierReturnsResultInstance()
    {
        $engineIdentifier = new EngineIdentifier('engine-2');

        $result = $this->result->addIdentifier($engineIdentifier);

        $this->assertInstance($result);
    }

    public function testHasIdentifierReturnsFalseWhenTheGivenIdentifierDoesNotBelongToTheResult()
    {
        $engineIdentifier = new EngineIdentifier('engine-1');

        $this->assertFalse($this->result->hasIdentifier($engineIdentifier));
    }

    public function testHasIdentifierReturnsTrueWhenTheGivenIdentifierBelongsToTheResult()
    {
        $engineIdentifier = $this->result->getIdentifiers()[0];

        $this->assertTrue($this->result->hasIdentifier($engineIdentifier));
    }

    public function testRemoveIdentifierRemovesTheIdentifierIfTheGivenIdentifierBelongsToTheResult()
    {
        $engineIdentifier = new EngineIdentifier('engine-2');

        $this->result->addIdentifier($engineIdentifier);

        $this->assertTrue($this->result->hasIdentifier($engineIdentifier));

        $this->result->removeIdentifier($engineIdentifier);

        $this->assertFalse($this->result->hasIdentifier($engineIdentifier));
    }

    public function testRemoveIdentifierDoesNothingIfTheGivenIdentifierDoesNotBelongToTheResult()
    {
        $engineIdentifier = new EngineIdentifier('engine-2');

        $this->assertFalse($this->result->hasIdentifier($engineIdentifier));

        $this->result->removeIdentifier($engineIdentifier);

        $this->assertFalse($this->result->hasIdentifier($engineIdentifier));
    }

    public function testRemoveIdentifierReturnsResultInstance()
    {
        $engineIdentifier = new EngineIdentifier('engine-2');

        $result = $this->result->removeIdentifier($engineIdentifier);

        $this->assertInstance($result);
    }

    /**
     * @expectedException Exception
     * @expectedExceptionMessage Cannot remove all of the engine identifiers!
     */
    public function testRemoveIdentifierThrowsExceptionIfTheUserTriesToRemoveTheLastEngineIdentifier()
    {
        $engineIdentifier = $this->result->getIdentifiers()[0];

        $this->result->removeIdentifier($engineIdentifier);
    }

    public function testGetIdentifiersReturnAllEngineIdentifiers()
    {
        $defaultIdentifiers = $this->getDefaultEngineIdentifierList()->all();
        $engineIdentifiers = $this->result->getIdentifiers();

        $this->assertCount(1, $engineIdentifiers);
        $this->assertEquals($defaultIdentifiers[0]->getIdentifier(), $engineIdentifiers[0]->getIdentifier());
    }

    private function assertInstance($instance)
    {
        $this->assertInstanceOf(Result::class, $instance);
        $this->assertInstanceOf(ResultInterface::class, $instance);
    }

    private function getDefaultEngineIdentifierList()
    {
        $engineIdentifier = new EngineIdentifier('engine-1');

        return new EngineIdentifierList([$engineIdentifier]);
    }

    private function getDefaultTitle()
    {
        return 'Title';
    }

    private function getDefaultUri()
    {
        return HttpUri::createFromString('https://foo.example.com/path/to/index.php?param=value');
    }
}
