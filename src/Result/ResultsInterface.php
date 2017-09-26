<?php
namespace SearchEngineAggregator\Result;

use Countable;
use IteratorAggregate;

/**
 * Description of ResultsInterface
 * 
 * @package SearchEngineAggregator\Result
 * @author Jeroen Mol <j.mol@pointerbp.nl>
 * @author Balázs Máté Petró <petrobalazsmate@gmail.com>
 */
interface ResultsInterface extends Countable, IteratorAggregate
{
    /**
     * Adds the given search result item to the list.
     *
     * @param ResultInterface $result The result item to add.
     * @return ResultsInterface
     */
    public function add(ResultInterface $result);

    /**
     * Returns true if the given result item belongs to the list, else false.
     *
     * @param ResultInterface $result The result item to check.
     * @return bool
     */
    public function has(ResultInterface $result);

    /**
     * Remove the given search item from the list.
     *
     * @param ResultInterface $result The result item to remove.
     * @return ResultsInterface
     */
    // NOTE: To be honest, this part of the interface was a little bit unclear for me.
    // In the original code, the type hint was ResultsInterface. Was it just a typo?
    // Another thing that I didn't understood, the return type was marked as mixed.
    // Should the method to be able to remove a whole list? Please give me a feedback 
    // about this after you've reviewed my code! :)
    public function remove(ResultInterface $result);

    /**
     * Returns all of the search result items.
     *
     * @return ResultInterface[]
     */
    public function all();

    /**
     * Merges search results.
     *
     * Example:
     *
     * Search result 1:
     * [title: u, url: v, identifiers: [w]]
     * [title: x, url: y, identifiers: [a]]
     *
     * Search result 2:
     * [title: x, url: y, identifier: [z]]
     *
     * Merged result:
     * [title: u, url: v, identifiers: [w]]
     * [title: x, url: y, identifiers: [a,z]]
     *
     * @param ResultsInterface $results The results to merge.
     * @return ResultsInterface
     */
    public function merge(ResultsInterface $results);
}
