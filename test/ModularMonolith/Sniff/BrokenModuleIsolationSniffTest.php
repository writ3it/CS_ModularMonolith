<?php


namespace Writ3it\CodingStandards\Tests\ModularMonolith\Sniff;


use Writ3it\CodingStandards\ModularMonolith\Sniffs\General\BrokenModuleIsolationSniff;
use Writ3it\CodingStandards\Tests\PhpCs\SniffTestCase;

class BrokenModuleIsolationSniffTest extends SniffTestCase
{
    const BROKEN_BOUNDARY_ERROR = 'ModularMonolith.General.BrokenModuleIsolation.BrokenBoundary';

    public function test_brokenBoundaryInUse()
    {
        $sniff = new BrokenModuleIsolationSniff();
        $sniff->modules_definitions_path = 'ModularMonolith/SampleModules/modules.xml';
        $file = $this->executeSniff(__DIR__ . '/../SampleModules/ModuleOne/BrokenBoundaryInUse.php', $sniff);
        self::assertTrue($file->hasError(6, self::BROKEN_BOUNDARY_ERROR),"use boundary is not guarded.");
        self::assertTrue($file->hasError(27, self::BROKEN_BOUNDARY_ERROR),"object creation boundary is not guarded.");
        self::assertTrue($file->hasError(22, self::BROKEN_BOUNDARY_ERROR),"object creation boundary (no parenthesis) is not guarded.");
        self::assertTrue($file->hasError(17, self::BROKEN_BOUNDARY_ERROR),"FQNS::class boundary is not guarded.");
    }
}