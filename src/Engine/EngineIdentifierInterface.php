<?php
namespace SearchEngineAggregator\Engine;

/**
 * Description of EngineIdentifierInterface
 * 
 * @package SearchEngineAggregator\Engine
 * @author Balázs Máté Petró <petrobalazsmate@gmail.com>
 */
interface EngineIdentifierInterface
{
    /**
     * Returns the identifier.
     *
     * @return string
     */
    public function getIdentifier();

    /**
     * Creates an engine identifier from a string.
     *
     * @static
     * @param string $identifier
     * @return EngineIdentifierInterface
     */
    public static function fromString($identifier);
}
