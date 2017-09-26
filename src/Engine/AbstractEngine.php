<?php
namespace SearchEngineAggregator\Engine;

/**
 * Class AbstractEngine
 *
 * Note: feel free to modify this class, if needed.
 *
 * @package SearchEngineAggregator\Engine
 * @author Jeroen Mol <j.mol@pointerbp.nl>
 */
abstract class AbstractEngine implements Engine
{
    /**
     * @var EngineIdentifier
     */
    private $identifier;

    /**
     * AbstractEngine constructor.
     *
     * @param EngineIdentifier $identifier
     */
    public function __construct(EngineIdentifier $identifier)
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