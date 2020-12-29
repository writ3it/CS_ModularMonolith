<?php


namespace ModularMonolith\Unit\Module;


use PHPUnit\Framework\TestCase;
use Writ3it\CodingStandards\ModularMonolith\Module\Configuration;
use Writ3it\CodingStandards\ModularMonolith\Module\ModuleRecognizer;

class ModuleRecognizerTest extends TestCase
{
    /**
     * @var ModuleRecognizer
     */
    private $recognizer;

    public function setUp()
    {
        parent::setUp();
        $this->recognizer = new ModuleRecognizer(new Configuration(__DIR__ . '/../../samples/two_modules_with_nesteds.xml'));
    }

    /**
     * @dataProvider provideExamples
     * @param $classToCheck
     * @param $expectedModule
     */
    public function test_findingByPrivateClass($classToCheck, $expectedModule)
    {
        $module = $this->recognizer->getModuleNameByNamespace($classToCheck);
        if ($expectedModule === null) {
            self::assertNull($module);
        } else {
            self::assertEquals($expectedModule, $module->getName());
        }
    }

    public function provideExamples()
    {
        return [
            ['App\\ModuleOne\\Part1\\Class', 'ModuleOne'],
            ['App\\ModuleOne\\Part2\\Class', 'ModuleOne'],
            ['App\\ModuleOne\\Part2\\Subnamespace\\Class', 'ModuleOne'],
            ['App\\ModuleOne\\Nested\\Class', 'ModuleOne.ModuleOneNested'],
            ['App\\ModuleOne\\Nested\\Shared\\Class', 'ModuleOne.ModuleOneNested'],
            ['App\\Outside\\Nested\\Shared\\Class', null],
        ];
    }
}