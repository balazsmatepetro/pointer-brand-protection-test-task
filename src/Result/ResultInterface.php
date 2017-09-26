<?php
namespace SearchEngineAggregator\Result;

use Psr\Http\Message\UriInterface;
use SearchEngineAggregator\Engine\EngineIdentifier;

/**
 * Interface ResultInterface
 * @package SearchEngineAggregator\Result
 * @author Jeroen Mol <j.mol@pointerbp.nl>
 */
interface ResultInterface
{
    /**
     * Returns the result title
     *
     * @return string
     */
    public function getTitle();

    /**
     * Returns the results URI
     *
     * @return UriInterface
     */
    public function getUri();

    /**
     * Returns whether the result equals another result
     *
     * @param ResultInterface $result
     *
     * @return bool
     */
    public function equals(ResultInterface $result);

    /**
     * Adds an engine identifier to the result
     *
     * @param EngineIdentifier $identifier
     *
     * @return $this
     */
    public function addIdentifier(EngineIdentifier $identifier);

    /**
     * Returns whether an engine identifier exists in the result
     *
     * @param EngineIdentifier $identifier
     *
     * @return bool
     */
    public function hasIdentifier(EngineIdentifier $identifier);

    /**
     * Removes an engine identifier from the result
     *
     * @param EngineIdentifier $identifier
     *
     * @throws \Exception When engine identifier is the only engine identifier of the result
     *
     * @return $this
     */
    public function removeIdentifier(EngineIdentifier $identifier);

    /**
     * Returns all engine identifiers
     *
     * @return EngineIdentifier[]
     */
    public function getIdentifiers();

}