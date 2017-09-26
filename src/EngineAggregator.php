<?php
namespace SearchEngineAggregator;

use InvalidArgumentException;
use SearchEngineAggregator\Engine\Engine;
use SearchEngineAggregator\Result\ResultList;
use SplObjectStorage;

/**
 * Description of EngineAggregator
 * 
 * @package SearchEngineAggregator
 * @author Jeroen Mol <j.mol@pointerbp.nl>
 * @author Balázs Máté Petró <petrobalazsmate@gmail.com>
 */
class EngineAggregator implements Searchable
{
    /**
     * The collection of engines.
     *
     * @var Engine[]
     */
    private $engines = [];

    /**
     * Creates a new EngineAggregator instance.
     *
     * @param Engine[] $engines The collection of engines.
     * @throws InvalidArgumentException Thrown when engines argument is empty or one of the items is not an Engine instance.
     */
    public function __construct(array $engines)
    {
        // We have to throw an exception if the engines argument is an empty array.
        if (empty($engines)) {
            throw new InvalidArgumentException('The engines argument cannot be empty!');
        }
        // Looping through engines.
        foreach ($engines as $engine) {
            // If the engine is not instance of Engine...
            if (!($engine instanceof Engine)) {
                // ...we have to throw an exception.
                throw new InvalidArgumentException('All items must be an instance of Engine!');
            }
        }
        // Setting engines.
        $this->engines = $engines;
    }

    /**
     * @inheritDoc
     */
    public function search($keyword)
    {
        // Creating a ResultList instance.
        $results = new ResultList(new SplObjectStorage);
        // Looping through engines.
        foreach ($this->engines as $engine) {
            // Merging search result to the result list.
            $results->merge($engine->search($keyword));
        }
        // Returns the result list.
        return $results;
    }
}
