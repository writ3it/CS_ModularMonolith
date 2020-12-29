<?php


namespace ModularMonolith\Unit;


use PHPUnit\Framework\TestCase;
use Writ3it\CodingStandards\ModularMonolith\Module\ModuleDefinition;

class ModuleDefinitionTest extends TestCase
{


    public function test_containsClass_simpleModule()
    {
        $module = $this->getSimpleModule();

        self::assertTrue($module->containsClass('App\\TestModule\\Foo\\Bar'));
        self::assertTrue($module->containsClass('App\\TestModule\\FooBar'));

        self::assertFalse($module->containsClass('App\\Outside\\TestModule\\Foo\\Bar'));
        self::assertFalse($module->containsClass('App\\TestModule1\\Foo\\Bar'));
        self::assertFalse($module->containsClass('App1\\TestModule\\Foo\\Bar'));
    }


    public function test_containsClass_complexModule()
    {
        list($module, $child) = $this->getComplexModule();

        self::assertTrue($module->containsClass('App\\TestModule\\Class'));
        self::assertFalse($child->containsClass('App\\TestModule\\Class'));

        self::assertTrue($module->containsClass('App\\Test\\TestModule\\Class'));
        self::assertFalse($child->containsClass('App\\Test\\TestModule\\Class'));

        self::assertTrue($module->containsClass('App\\TestModule\\Public\\Class'));
        self::assertFalse($child->containsClass('App\\TestModule\\Public\\Class'));

        self::assertFalse($module->containsClass('App\\TestModule\\Nested1\\Class'));
        self::assertTrue($child->containsClass('App\\TestModule\\Nested1\\Class'));

        self::assertFalse($module->containsClass('App\\TestModule\\Nested2\\Class'));
        self::assertTrue($child->containsClass('App\\TestModule\\Nested2\\Class'));

        self::assertFalse($module->containsClass('App\\TestModule\\Nested1\\Public\\Class'));
        self::assertTrue($child->containsClass('App\\TestModule\\Nested1\\Public\\Class'));
        self::assertFalse($module->containsClass('App\\Outside\\Class'));
        self::assertFalse($child->containsClass('App\\Outside\\Class'));
    }

    private function getComplexModule()
    {
        $childModule = new ModuleDefinition([
            'name' => 'ChildModule',
            'namespaces' => [
                'App\\TestModule\\Nested1\\',
                'App\\TestModule\\Nested2\\'
            ],
            'publicNamespaces' => [
                'App\\TestModule\\Nested1\\Public\\'
            ]
        ]);
        $module = new ModuleDefinition([
            'name' => 'TestModule',
            'namespaces' => [
                'App\\TestModule\\',
                'App\\Test\\TestModule\\'
            ],
            'publicNamespaces' => [
                'App\\TestModule\\Public\\'
            ],
        ]);
        $module->addChild($childModule);
        return [$module, $childModule];
    }

    private function getSimpleModule()
    {
        return new ModuleDefinition([
            'name' => 'TestModule',
            'namespaces' => [
                'App\\TestModule\\'
            ],
            'publicNamespaces' => [

            ]
        ]);
    }
}