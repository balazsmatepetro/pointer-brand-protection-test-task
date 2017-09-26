<?php
namespace SearchEngineAggregator\Result;

use SplObjectStorage;

/**
 * Description of ResultList
 * 
 * @package SearchEngineAggregator\Result
 * @author Balázs Máté Petró <petrobalazsmate@gmail.com>
 */
class ResultList implements ResultsInterface
{
    /**
     * The result list object.
     *
     * @var SplObjectStorage
     */
    private $resultList;

    /**
     * Creates a new ResultList instance.
     *
     * @param SplObjectStorage $resultList The result list object.
     */
    public function __construct(SplObjectStorage $resultList)
    {
        $this->resultList = $resultList;
    }

    /**
     * @inheritDoc
     */
    public function add(ResultInterface $result)
    {
        // If the list doesn't contain the result item...
        if (!$this->has($result)) {
            // ...we will add it to the list.
            $this->resultList->attach($result);
        }
        // Because this is a fluent method, we have to return the object itself.
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function has(ResultInterface $result)
    {
        // We have to rewind the iterator to the first element.
        $this->resultList->rewind();
        // Looping through the result list.
        foreach ($this->resultList as $resultItem) {
            // If the given result item equals to the current result item, we have to break
            // the iteration and return true.
            if ($result->equals($resultItem)) {
                return true;
            }
        }
        // We haven't found any matching items, time to return false.
        return false;
    }

    /**
     * @inheritDoc
     */
    public function remove(ResultInterface $result)
    {
        // If the list contains the result item...
        if ($this->has($result)) {
            // ...we will remove it from the list.
            $this->resultList->detach($result);
        }
        // Because this is a fluent method, we have to return the object itself.
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function all()
    {
        // We have to rewind the iterator to the first element.
        $this->resultList->rewind();
        // This array will store the result items.
        $results = [];
        // Looping through result items.
        foreach ($this->resultList as $resultItem) {
            $results[] = $resultItem;
        }
        // Returning result items.
        return $results;
    }

    /**
     * @inheritDoc
     */
    public function merge(ResultsInterface $results)
    {
        // We have to loop through the result items.
        foreach ($results as $result) {
            if ($this->has($result)) {
                // If the target result has the same result, we have find it in the list.
                $targetResult = $this->findTargetResult($result);
                // If the result is located.
                if (false !== $targetResult) {
                    // We will merge the engine identifiers to the result.
                    $this->mergeEngineIdentifiers($targetResult, $result->getIdentifiers());
                }
            } else {
                // If the target result list doesn't contain the given result item, we
                // have to add it to the list.
                $this->add($result);
            }
        }
        // Returns the merged result list.
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getIterator()
    {
        return $this->resultList;
    }

    /**
     * @inheritDoc
     */
    public function count()
    {
        return $this->resultList->count();
    }

    /**
     * Returns the result item if that can be located in the list, else false.
     *
     * @param ResultInterface $target The result item to search for.
     * @return ResultInterface|false
     */
    private function findTargetResult(ResultInterface $target)
    {
        // Looping through result items.
        foreach ($this as $result) {
            // If the result item equals to the target, we have to break the iteration
            // and return the item from the list.
            if ($target->equals($result)) {
                return $result;
            }
        }
        // If the target result cannot be located, we have to return false.
        return false;
    }

    /**
     * Merges the engine identifiers to the result.
     *
     * @param ResultInterface $result The result to merge.
     * @param EngineIdentifierInterface[] $engineIdentifiers The engine Identifiers to merge.
     */
    private function mergeEngineIdentifiers(ResultInterface $result, array $engineIdentifiers)
    {
        // Looping through engine identifiers.
        foreach ($engineIdentifiers as $engineIdentifier) {
            // If the engine identifier doesn't belong to the result.
            if (!$result->hasIdentifier($engineIdentifier)) {
                // We have to add the engine identifier to the result.
                $result->addIdentifier($engineIdentifier);
            }
        }
    }
}
