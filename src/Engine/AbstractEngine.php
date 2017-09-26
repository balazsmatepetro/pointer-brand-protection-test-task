<?php
namespace SearchEngineAggregator\Engine;

/**
 * Class AbstractEngine
 *
 * Note: feel free to modify this class, if needed.
 *
 * @package SearchEngineAggregator\Engine
 * @author Jeroen Mol <j.mol@pointerbp.nl>
 * @author Balázs Máté Petró <petrobalazsmate@gmail.com>
 */
abstract class AbstractEngine implements Engine
{
    /**
     * @var EngineIdentifierInterface
     */
    protected $identifier;

    /**
     * AbstractEngine constructor.
     *
     * @param EngineIdentifierInterface $identifier The engine identifier.
     */
    public function __construct(EngineIdentifierInterface $identifier)
    {
        $this->identifier = $identifier;
    }

    /**
     * @inheritDoc
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }
}
