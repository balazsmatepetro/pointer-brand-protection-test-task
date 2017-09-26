<?php
namespace SearchEngineAggregator\Engine;

use Assert\Assertion;

/**
 * Class EngineIdentifier
 * @package SearchEngineAggregator\Engine
 * @author Jeroen Mol <j.mol@pointerbp.nl>
 */
final class EngineIdentifier
{
    /**
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
     * Creates an engine identifier from string
     *
     * @static
     *
     * @param string $identifier
     *
     * @return EngineIdentifier
     */
    public static function fromString($identifier)
    {
        return new self($identifier);
    }

    /**
     * Returns the string representation
     *
     * @return string
     */
    public function __toString()
    {
        return $this->identifier;
    }

}