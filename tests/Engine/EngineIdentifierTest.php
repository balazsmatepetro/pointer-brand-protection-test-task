<?php
namespace SearchEngineAggregator\Tests\Engine;

use Assert\InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use SearchEngineAggregator\Engine\EngineIdentifier;
use SearchEngineAggregator\Engine\EngineIdentifierInterface;
use stdClass;

/**
 * Description of EngineIdentifierTest
 * 
 * @author Balázs Máté Petró <petrobalazsmate@gmail.com>
 */
class EngineIdentifierTest extends TestCase
{
    const IDENTIFIER = 'identifier';

    /**
     * @dataProvider dataProviderEmptyValues
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Identifier cannot be empty.
     */
    public function testConstructThrowsExceptionWhenTheGivenValueIsEmpty($emptyValue)
    {
        new EngineIdentifier($emptyValue);
    }

    /**
     * @dataProvider dataProviderNonStringValues
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Identifier is not a string.
     */
    public function testConstructThrowsExceptionWhenTheGivenValueIsNotString($nonStringValue)
    {
        new EngineIdentifier($nonStringValue);
    }

    public function testFromStringCreatesNewInstance()
    {
        $engineIdentifier = EngineIdentifier::fromString(self::IDENTIFIER);

        $this->assertInstanceOf(EngineIdentifierInterface::class, $engineIdentifier);
        $this->assertInstanceOf(EngineIdentifier::class, $engineIdentifier);
    }

    public function testFromStringCreatesNewInstanceWithTheGivenIdentifier()
    {
        $engineIdentifier = EngineIdentifier::fromString(self::IDENTIFIER);

        $this->assertEquals(self::IDENTIFIER, $engineIdentifier->getIdentifier());
    }

    public function testGetIdentifierReturnsTheIdentifier()
    {
        $engineIdentifier = new EngineIdentifier(self::IDENTIFIER);

        $this->assertEquals(self::IDENTIFIER, $engineIdentifier->getIdentifier());
    }

    public function dataProviderEmptyValues()
    {
        return [
            [''],
            [[]],
            [0],
            [0.0],
            ['0'],
            [null],
            [false]
        ];
    }

    public function dataProviderNonStringValues()
    {
        return [
            [1],
            [1.0],
            [true],
            [['string']],
            [new stdClass]
        ];
    }
}
