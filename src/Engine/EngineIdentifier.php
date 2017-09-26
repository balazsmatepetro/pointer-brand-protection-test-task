<?php
namespace SearchEngineAggregator\Engine;

use Assert\Assertion;

/**
 * Description of EngineIdentifier
 * 
 * @package SearchEngineAggregator\Engine
 * @author Jeroen Mol <j.mol@pointerbp.nl>
 * @author Balázs Máté Petró <petrobalazsmate@gmail.com>
 */
final class EngineIdentifier implements EngineIdentifierInterface
{
    /**
     * The string representation of engine identifier.
     * 
     * @var string
     */
    private $identifier;

    /**
     * EngineIdentifier constructor.
     *
     * @param string $identifier
     */
    public function __construct($identifier)
    {
        Assertion::notEmpty($identifier, 'Identifier cannot be empty.');
        Assertion::string($identifier, 'Identifier is not a string.');

        $this->identifier = $identifier;
    }

    /**
     * @inheritDoc
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * @inheritDoc
     */
    public static function fromString($identifier)
    {
        return new self($identifier);
    }

    /**
     * Returns the string representation of engine identifier.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->identifier;
    }
}
