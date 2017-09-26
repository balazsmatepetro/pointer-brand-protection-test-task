<?php
namespace SearchEngineAggregator\Engine;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use SearchEngineAggregator\Engine\EngineIdentifier;
use SearchEngineAggregator\Engine\GeneratedStaticResultEngine;
use SearchEngineAggregator\Result\ResultInterface;
use SearchEngineAggregator\Result\ResultsInterface;
use stdClass;

/**
 * Description of GeneratedStaticResultEngineTest
 * 
 * @author Balázs Máté Petró <petrobalazsmate@gmail.com>
 */
class GeneratedStaticResultEngineTest extends TestCase
{
    private $engineIdentifier;

    public function setUp()
    {
        $this->engineIdentifier = EngineIdentifier::fromString('engine');
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage The title must be a string!
     * @dataProvider dataProviderInvalidTitles
     */
    public function testConstructThrowsExceptionWhenTheGivenTitleIsInvalid($title)
    {
        new GeneratedStaticResultEngine($this->engineIdentifier, $title);
    }

    public function testConstructGeneratesResults()
    {
        $title = 'Title';

        $engine = new GeneratedStaticResultEngine($this->engineIdentifier, $title);

        $results = $engine->getResults();

        $this->assertCount(GeneratedStaticResultEngine::NUMBER_OF_RESULTS, $results);

        foreach ($results as $index => $result) {
            $this->assertEquals($title . ' - ' . $index, $result);
        }
    }

    public function testSearchReturnsResultsInterfaceInstance()
    {
        $engine = new GeneratedStaticResultEngine($this->engineIdentifier, 'Title');

        $this->assertInstanceOf(ResultsInterface::class, $engine->search('title'));
    }

    public function testSearchReturnsListWithResultWhenHasMatchForTheGivenKeyword()
    {
        $engine = new GeneratedStaticResultEngine($this->engineIdentifier, 'Title');

        $results = $engine->search('title');

        $this->assertCount(GeneratedStaticResultEngine::NUMBER_OF_RESULTS, $results);

        foreach ($results as $index => $result) {
            $this->assertInstanceOf(ResultInterface::class, $result);
            $this->assertEquals('Title - '. $index, $result->getTitle());
        }
    }

    public function testSearchReturnsEmptyListWhenNoMatchForTheGivenKeyword()
    {
        $engine = new GeneratedStaticResultEngine($this->engineIdentifier, 'Title');

        $this->assertCount(0, $engine->search('result'));
    }

    public function dataProviderInvalidTitles()
    {
        return [
            [''],
            [0],
            [false],
            [[]],
            [new stdClass],
            [function () {
                return 'title';
            }]
        ];
    }
}
