<?php


namespace Writ3it\CodingStandards\Tests\ModularMonolith\SampleModules\ModuleOne;

use Writ3it\CodingStandards\Tests\ModularMonolith\SampleModules\ModuleTwo\SampleClass;

class BrokenBoundaryInUse
{
    public function example()
    {
        $foobar = SampleClass::class;
    }

    public function example2()
    {
        $foobar = \Writ3it\CodingStandards\Tests\ModularMonolith\SampleModules\ModuleTwo\SampleClass2::class; //full class name
    }

    public function example3()
    {
        $foobar = new \Writ3it\CodingStandards\Tests\ModularMonolith\SampleModules\ModuleTwo\SampleClass2; //full class name
    }

    public function example4()
    {
        $foobar = new \Writ3it\CodingStandards\Tests\ModularMonolith\SampleModules\ModuleTwo\SampleClass2(); //full class name
    }
}