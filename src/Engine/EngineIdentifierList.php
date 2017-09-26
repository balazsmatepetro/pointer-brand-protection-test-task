<?php
namespace SearchEngineAggregator\Engine;

use InvalidArgumentException;

/**
 * Description of EngineIdentifierList
 * 
 * @package SearchEngineAggregator\Engine
 * @author Jeroen Mol <j.mol@pointerbp.nl>
 * @author Balázs Máté Petró <petrobalazsmate@gmail.com>
 */
final class EngineIdentifierList implements EngineIdentifierListInterface
{
    /**
     * The collection of engine identifiers.
     *
     * @var EngineIdentifierInterface[]
     */
    private $identifiers;

    public function __construct(array $identifiers = [])
    {
        foreach ($identifiers as $identifier) {
            if (!is_object($identifier) && !($identifier instanceof EngineIdentifierInterface)) {
                throw new InvalidArgumentException('All items must be an instance of EngineIdentifierInterface!');
            }
        }
        
        $this->identifiers = $identifiers;
    }

    /**
     * @inheritDoc
     */
    public static function fromIdentifier(EngineIdentifierInterface $identifier)
    {
        return (new self)->add($identifier);
    }

    /**
     * @inheritDoc
     */
    public function add(EngineIdentifierInterface $identifier)
    {
        if (!$this->has($identifier)) {
            array_push($this->identifiers, $identifier);
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function has(EngineIdentifierInterface $identifier)
    {
        return in_array($identifier, $this->identifiers, true);
    }

    /**
     * @inheritDoc
     */
    public function remove(EngineIdentifierInterface $identifier)
    {
        $key = array_search($identifier, $this->identifiers);

        if (false !== $key) {
            unset($this->identifiers[$key]);
        }

        return $this;
    }

    /**
     * @inheritDoc
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
