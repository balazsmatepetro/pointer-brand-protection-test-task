<?php
namespace SearchEngineAggregator\Result;

/**
 * Interface ResultsInterface
 * @package SearchEngineAggregator\Result
 * @author Jeroen Mol <j.mol@pointerbp.nl>
 */
interface ResultsInterface extends \Countable
{
    /**
     * Adds a search result to the list
     *
     * @param \SearchEngineAggregator\Result\ResultInterface $result
     *
     * @return ResultsInterface
     */
    public function add(ResultInterface $result);

    /**
     * Returns whether a search result exists
     *
     * @param ResultInterface $result
     *
     * @return bool
     */
    public function has(ResultInterface $result);

    /**
     * Remove a search result from the list
     *
     * @param ResultsInterface $result
     *
     * @return mixed
     */
    public function remove(ResultsInterface $result);

    /**
     * Returns all search results
     *
     * @return \SearchEngineAggregator\Result\ResultInterface[]
     */
    public function all();

    /**
     * Merges search results
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
     * @param \SearchEngineAggregator\Result\ResultsInterface $results
     *
     * @return $this
     */
    public function merge(ResultsInterface $results);
}