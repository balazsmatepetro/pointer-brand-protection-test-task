<?php
namespace SearchEngineAggregator\Engine;

use Countable;

/**
 * Description of EngineIdentifierListInterface
 * 
 * @package SearchEngineAggregator\Engine
 * @author Balázs Máté Petró <petrobalazsmate@gmail.com>
 */
interface EngineIdentifierListInterface extends Countable
{
    /**
     * Returns all engine identifiers
     *
     * @return EngineIdentifierInterface[]
     */
    public function all();

    /**
     * Adds an engine identifier to the list.
     *
     * @param EngineIdentifierInterface $identifier
     * @return EngineIdentifierListInterface
     */
    public function add(EngineIdentifierInterface $identifier);

    /**
     * Returns whether an engine identifier exists.
     *
     * @param EngineIdentifierInterface $identifier
     * @return bool
     */
    public function has(EngineIdentifierInterface $identifier);

    /**
     * Removes an identifier from the list.
     *
     * @param EngineIdentifierInterface $identifier
     * @return EngineIdentifierListInterface
     */
    public function remove(EngineIdentifierInterface $identifier);

    /**
     * Creates an engine identifier list from an engine identifier.
     *
     * @static
     * @param EngineIdentifierInterface $identifier
     * @return EngineIdentifierListInterface
     */
    public static function fromIdentifier(EngineIdentifierInterface $identifier);
}
