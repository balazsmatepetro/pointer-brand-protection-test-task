<?php
namespace SearchEngineAggregator\Result;

use SearchEngineAggregator\Engine\EngineIdentifier;

/**
 * Class EngineIdentifierList
 * @package SearchEngineAggregator\Result
 * @author Jeroen Mol <j.mol@pointerbp.nl>
 */
final class EngineIdentifierList implements \Countable
{
    /**
     * @var EngineIdentifier[]
     */
    private $identifiers;

    /**
     * EngineIdentifierList constructor.
     */
    private function __construct()
    {
        $this->identifiers = [];
    }

    /**
     * Creates an engine identifier list from an engine identifier
     *
     * @static
     *
     * @param EngineIdentifier $identifier
     *
     * @return $this
     */
    public static function fromIdentifier(EngineIdentifier $identifier)
    {
        $list = new self();
        $list->add($identifier);

        return $list;
    }

    /**
     * Adds an engine identifier the list
     *
     * @param EngineIdentifier $identifier
     *
     * @return $this
     */
    public function add(EngineIdentifier $identifier)
    {
        if (!$this->has($identifier)) {
            array_push($this->identifiers, $identifier);
        }

        return $this;
    }

    /**
     * Returns whether an engine identifier exists
     *
     * @param EngineIdentifier $identifier
     *
     * @return bool
     */
    public function has(EngineIdentifier $identifier)
    {
        return in_array($identifier, $this->identifiers, true);
    }

    /**
     * Removes an identifier from the list
     *
     * @param EngineIdentifier $identifier
     *
     * @return $this
     */
    public function remove(EngineIdentifier $identifier)
    {
        $key = array_search($identifier, $this->identifiers);

        if (false !== $key) {
            unset($this->identifiers[$key]);
        }

        return $this;
    }

    /**
     * Returns all engine identifiers
     *
     * @return \SearchEngineAggregator\Engine\EngineIdentifier[]
     */
    public function all()
    {
        return $this->identifiers;
    }

    /**
     * @inheritDoc
     */
    public function count()
    {
        return count($this->identifiers);
    }

}