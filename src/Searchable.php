<?php
namespace SearchEngineAggregator;

use SearchEngineAggregator\Result\ResultsInterface;

/**
 * Interface Searchable
 * @package SearchEngineAggregator
 * @author Jeroen Mol <j.mol@pointerbp.nl>
 */
interface Searchable
{
    /**
     * Perform search on keyword
     *
     * @param string $keyword
     *
     * @return ResultsInterface
     */
    public function search($keyword);
}