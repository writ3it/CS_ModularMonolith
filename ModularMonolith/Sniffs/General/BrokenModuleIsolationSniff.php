<?php


namespace Writ3it\CodingStandards\ModularMonolith\Sniffs\General;

use PHP_CodeSniffer\Files\File;
use Writ3it\CodingStandards\ModularMonolith\AbstractGroup;
use Writ3it\CodingStandards\ModularMonolith\AbstractModuleSniff;
use Writ3it\CodingStandards\ModularMonolith\Groups\ClassConstAndStaticGroup;
use Writ3it\CodingStandards\ModularMonolith\Groups\NamespaceGroup;
use Writ3it\CodingStandards\ModularMonolith\Groups\NewGroup;
use Writ3it\CodingStandards\ModularMonolith\Groups\UseGroup;
use Writ3it\CodingStandards\ModularMonolith\Module\ModuleDefinition;

class BrokenModuleIsolationSniff extends AbstractModuleSniff
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

    /**
     * @var string
     */
    private $currentNamespace = null;


    public function __construct()
    {
        $this->groups = array(
            new NamespaceGroup([$this, 'processNamespace']),
            new UseGroup([$this, 'processUse']),
            new NewGroup([$this, 'processNew']),
            new ClassConstAndStaticGroup([$this, 'processConstAndStatic'])
        );
    }

    /**
     * @param AbstractGroup $group
     */
    public function processConstAndStatic($group)
    {
        $this->processNew($group);
    }

    /**
     * @param AbstractGroup $group
     */
    public function processNamespace($group)
    {
        $group->overrideContentByType(ModuleDefinition::NS_SEPARATOR, T_NS_SEPARATOR);
        $namespace = $group->getContentAsString();
        $this->currentNamespace = $namespace;
        $this->clientModule = $this->moduleRecognizer->getModuleNameByNamespace($namespace);
    }

    public function initializeFile()
    {
        $this->resetContext();
        parent::initializeFile();
    }

    /**
     * @param AbstractGroup $group
     */
    public function processUse($group)
    {
        $group->overrideContentByType(ModuleDefinition::NS_SEPARATOR, T_NS_SEPARATOR);
        $class = $group->getContentAsString();
        $this->checkBoundaryWith($class);
    }

    public function processNew($group)
    {
        $group->overrideContentByType(ModuleDefinition::NS_SEPARATOR, T_NS_SEPARATOR);
        $class = $group->getContentAsString();
        $this->checkBoundaryWith($class);
        if ($class{0} !== ModuleDefinition::NS_SEPARATOR) {
            $possibleClass = $this->currentNamespace . ModuleDefinition::NS_SEPARATOR . $class;
            $this->checkBoundaryWith($possibleClass);
        }
    }

    private function checkBoundaryWith($className)
    {
        $dependencyModule = $this->moduleRecognizer->getModuleNameByNamespace($className);
        if ($this->clientModule
            && $dependencyModule
            && !$this->clientModule->equals($dependencyModule)
            && $dependencyModule->isPrivateClass($className)) {
            $clientModuleName = $this->clientModule->getName();
            $dependencyModuleName = $dependencyModule->getName();
            $message = "Module $clientModuleName breaks the boundary by referencing the module $dependencyModuleName. Redesign your code.";
            $this->addError("BrokenBoundary", $message, $className);
        }
    }

    private function resetContext()
    {
        $this->currentNamespace = null;
        $this->clientModule = null;
    }


}