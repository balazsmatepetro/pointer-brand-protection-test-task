<?php
namespace SearchEngineAggregator\Result;

use Exception;
use Psr\Http\Message\UriInterface;
use SearchEngineAggregator\Engine\EngineIdentifierInterface;
use SearchEngineAggregator\Engine\EngineIdentifierListInterface;

/**
 * Description of Result
 * 
 * @package SearchEngineAggregator\Result
 * @author Balázs Máté Petró <petrobalazsmate@gmail.com>
 */
class Result implements ResultInterface
{
    /**
     * The title of search result.
     *
     * @var string
     */
    private $title = '';

    /**
     * The URI of search result.
     *
     * @var UriInterface
     */
    private $uri;

    /**
     * The engine identifier list object.
     *
     * @var EngineIdentifierListInterface
     */
    private $engineIdentifierList;

    /**
     * Creates a new Result instance.
     *
     * @param string $title The title of result.
     * @param UriInterface $uri The URI of result.
     * @param EngineIdentifierListInterface $engineIdentifierList The engine identifier list of result.
     */
    public function __construct($title, UriInterface $uri, EngineIdentifierListInterface $engineIdentifierList)
    {
        $this->title = $title;
        $this->uri = $uri;
        $this->engineIdentifierList = $engineIdentifierList;
    }

    /**
     * @inheritDoc
     */
    public function getTitle()
    {
        return $this->title;
    }
    
    /**
     * @inheritDoc
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * @inheritDoc
     */
    public function equals(ResultInterface $result)
    {
        return $result->getTitle() === $this->title && $result->getUri()->__toString() === $this->uri->__toString();
    }
    
    /**
     * @inheritDoc
     */
    public function addIdentifier(EngineIdentifierInterface $identifier)
    {
        // Adding engine identifier to the list.
        $this->engineIdentifierList->add($identifier);
        // We have to return the object itself, because it's a fluent method (defined be the interface).
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function hasIdentifier(EngineIdentifierInterface $identifier)
    {
        return $this->engineIdentifierList->has($identifier);
    }

    /**
     * @inheritDoc
     */
    public function removeIdentifier(EngineIdentifierInterface $identifier)
    {
        // If only one engine identifier remained, we have to throw exception.
        if ($this->hasIdentifier($identifier) && 1 === count($this->engineIdentifierList)) {
            throw new Exception('Cannot remove all of the engine identifiers!');
        }
        // Otherwise we can remove the engine identifier from the list.
        $this->engineIdentifierList->remove($identifier);
        // We have to return the object itself, because it's a fluent method (defined be the interface).
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getIdentifiers()
    {
        return $this->engineIdentifierList->all();
    }
}
