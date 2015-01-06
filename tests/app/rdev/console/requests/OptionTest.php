<?php
/**
 * Copyright (C) 2015 David Young
 *
 * Tests the console option
 */
namespace RDev\Console\Requests;

class OptionTest extends \PHPUnit_Framework_TestCase
{
    /** @var Option The option to use in tests */
    private $option = null;

    /**
     * Sets up the tests
     */
    public function setUp()
    {
        $this->option = new Option("foo", OptionTypes::OPTIONAL_VALUE, "Foo option", "bar");
    }

    /**
     * Tests checking whether or not the option value is optional
     */
    public function testCheckingIsValueOptional()
    {
        $requiredOption = new Option("foo", OptionTypes::REQUIRED_VALUE, "Foo option", "bar");
        $optionalArgument = new Option("foo", OptionTypes::OPTIONAL_VALUE, "Foo option", "bar");
        $this->assertFalse($requiredOption->valueIsOptional());
        $this->assertTrue($optionalArgument->valueIsOptional());
    }

    /**
     * Tests checking whether or not the option value is permitted
     */
    public function testCheckingIsValuePermitted()
    {
        $requiredOption = new Option("foo", OptionTypes::REQUIRED_VALUE, "Foo option", "bar");
        $notPermittedOption = new Option("foo", OptionTypes::NO_VALUE, "Foo option", "bar");
        $this->assertTrue($requiredOption->valueIsPermitted());
        $this->assertFalse($notPermittedOption->valueIsPermitted());
    }

    /**
     * Tests checking whether or not the option value is required
     */
    public function testCheckingIsValueRequired()
    {
        $requiredOption = new Option("foo", OptionTypes::REQUIRED_VALUE, "Foo option", "bar");
        $optionalArgument = new Option("foo", OptionTypes::OPTIONAL_VALUE, "Foo option", "bar");
        $this->assertTrue($requiredOption->valueIsRequired());
        $this->assertFalse($optionalArgument->valueIsRequired());
    }

    /**
     * Tests getting the default value
     */
    public function testGettingDefaultValue()
    {
        $this->assertEquals("bar", $this->option->getDefaultValue());
    }

    /**
     * Tests getting the description
     */
    public function testGettingDescription()
    {
        $this->assertEquals("Foo option", $this->option->getDescription());
    }

    /**
     * Tests getting the name
     */
    public function testGettingName()
    {
        $this->assertEquals("foo", $this->option->getName());
    }

    /**
     * Tests getting the type
     */
    public function testGettingType()
    {
        $this->assertEquals(OptionTypes::OPTIONAL_VALUE, $this->option->getType());
    }
}