<?php
namespace SearchEngineAggregator\Engine;

use InvalidArgumentException;
use League\Uri\Schemes\Http as HttpUri;
use SearchEngineAggregator\Result\Result;
use SearchEngineAggregator\Result\ResultList;
use SplObjectStorage;

/**
 * Description of GeneratedStaticResultEngine
 * 
 * @package SearchEngineAggregator\Engine
 * @author Balázs Máté Petró <petrobalazsmate@gmail.com>
 */
class GeneratedStaticResultEngine extends AbstractEngine
{
    /**
     * The number of generated results.
     * 
     * @var int
     */
    const NUMBER_OF_RESULTS = 10;

    /**
     * The generated results.
     *
     * @var string[]
     */
    private $results = [];

    /**
     * Creates a new GeneratedStaticResultEngine instance.
     *
     * @param EngineIdentifierInterface $identifier The engine identifier.
     * @param string $title The result title.
     */
    public function __construct(EngineIdentifierInterface $identifier, $title)
    {
        // If the given title is invalid, we have to throw an exception.
        if (!is_string($title) || empty($title)) {
            throw new InvalidArgumentException('The title must be a string!');
        }
        // Calling parent constructor.
        parent::__construct($identifier);
        // Generating results.
        for ($i = 0; self::NUMBER_OF_RESULTS > $i; $i++) {
            $this->results[] = $title . ' - ' . $i;
        }
    }

    /**
     * @inheritDoc
     */
    public function search($keyword)
    {
        // Creating ResultList instance.
        $results = new ResultList(new SplObjectStorage);
        // Creating URI, this will be added to the result.
        $uri = HttpUri::createFromString('http://example.com/');
        // Creating engine identifier list, this will be added to the result.
        $engineIdentifierList = EngineIdentifierList::fromIdentifier($this->identifier);
        // Looping through results.
        foreach ($this->results as $result) {
            // If the result matches to the given keyword...
            if (false !== strpos(strtolower($result), strtolower($keyword))) {
                // ...we have to add the result to the list.
                $results->add(new Result($result, $uri, $engineIdentifierList));
            }
        }
        // Returning results.
        return $results;
    }

    /**
     * Returns the generated results.
     *
     * @return string[]
     */
    public function getResults()
    {
        return $this->results;
    }
}
