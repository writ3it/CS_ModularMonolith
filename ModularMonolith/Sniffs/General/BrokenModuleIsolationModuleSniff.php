<?php


namespace Writ3it\CodingStandards\ModularMonolith\Sniffs\General;

use Writ3it\CodingStandards\ModularMonolith\AbstractGroup;
use Writ3it\CodingStandards\ModularMonolith\AbstractModuleSniff;
use Writ3it\CodingStandards\ModularMonolith\Groups\NamespaceGroup;
use Writ3it\CodingStandards\ModularMonolith\Groups\UseGroup;
use Writ3it\CodingStandards\ModularMonolith\Module\ModuleDefinition;

class BrokenModuleIsolationModuleSniff extends AbstractModuleSniff
{
    public $supportedTokenizers = array('PHP');
    /**
     * @var AbstractGroup[]
     */
    public $groups;

    /**
     * @var ModuleDefinition|null
     */
    private $clientModule = null;


    public function __construct()
    {
        $this->groups = array(
            new NamespaceGroup([$this, 'processNamespace']),
            new UseGroup([$this, 'processUse'])
        );
    }

    /**
     * @param AbstractGroup $group
     */
    public function processNamespace($group)
    {
        $namespace = $group->getContentAsString();
        $this->clientModule = $this->moduleRecognizer->getModuleNameByNamespace($namespace);
    }

    /**
     * @param AbstractGroup $group
     */
    public function processUse($group)
    {
        $class = $group->getContentAsString();
        $dependencyModule = $this->moduleRecognizer->getModuleNameByNamespace($class);
        if ($this->clientModule && $dependencyModule && !$this->clientModule->equals($dependencyModule)) {
            $clientModuleName = $this->clientModule->getName();
            $dependencyModuleName = $dependencyModule->getName();
            $this->addError("BrokenBoundary","Module $clientModuleName breaks the boundary by referencing the module $dependencyModuleName.", $class);
        }
    }


}