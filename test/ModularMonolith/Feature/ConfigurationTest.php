<?php


namespace ModularMonolith\Feature;


use PHPUnit\Framework\TestCase;
use Writ3it\CodingStandards\ModularMonolith\Module\Configuration;

class ConfigurationTest extends TestCase
{
    /**
     * @dataProvider examplesProvider
     * @param $exampleName
     */
    public function test_configurationNormalizing($exampleName)
    {
        $configuration = new Configuration(__DIR__ . '/samples/' . $exampleName . '.xml');
        $expected = json_decode(file_get_contents(__DIR__ . '/samples/' . $exampleName . '.json'), true);
        self::assertEquals($expected,
            $configuration->getModules()
        );
    }

    public function examplesProvider()
    {
        return [
            ['simple_config'],
            ['one_module_with_source'],
            ['one_module_with_source_and_public'],
            ['two_modules'],
            ['two_modules_with_nesteds']
        ];
    }
}