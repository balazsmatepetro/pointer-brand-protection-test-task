<?php
namespace SearchEngineAggregator\Engine;

use SearchEngineAggregator\Searchable;

/**
 * Interface Engine
 * @package SearchEngineAggregator\Engine
 * @author Jeroen Mol <j.mol@pointerbp.nl>
 */
interface Engine extends Searchable
{
    /**
     * Returns the engine's identifier
     *
     * @return EngineIdentifier
     */
    public function getIdentifier();
}