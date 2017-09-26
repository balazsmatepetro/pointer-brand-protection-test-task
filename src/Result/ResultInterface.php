<?php
namespace SearchEngineAggregator\Result;

use Exception;
use Psr\Http\Message\UriInterface;
use SearchEngineAggregator\Engine\EngineIdentifierInterface;

/**
 * Description of ResultInterface
 * 
 * @package SearchEngineAggregator\Result
 * @author Jeroen Mol <j.mol@pointerbp.nl>
 * @author Balázs Máté Petró <petrobalazsmate@gmail.com>
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
     * Returns true when the result is the same as the given, else false.
     *
     * @param ResultInterface $result The result to check.
     * @return bool
     */
    public function equals(ResultInterface $result);

    /**
     * Adds the given engine identifier to the result.
     *
     * @param EngineIdentifierInterface $identifier The engine identifier to add.
     * @return ResultInterface
     */
    public function addIdentifier(EngineIdentifierInterface $identifier);

    /**
     * Returns true when the engine identifier exists in the result, else false.
     *
     * @param EngineIdentifierInterface $identifier The engine identifier to check.
     * @return bool
     */
    public function hasIdentifier(EngineIdentifierInterface $identifier);

    /**
     * Removes the given engine identifier from the result.
     *
     * @param EngineIdentifierInterface $identifier The engine identifier to remove.
     * @throws Exception Thrown when the engine identifier is the only one engine identifier of the result.
     * @return ResultInterface
     */
    public function removeIdentifier(EngineIdentifierInterface $identifier);

    /**
     * Returns all engine identifiers.
     *
     * @return EngineIdentifierInterface[]
     */
    public function getIdentifiers();
}
