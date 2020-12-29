<?php


namespace Writ3it\CodingStandards\ModularMonolith\Module;


class ModuleRecognizer
{
    /**
     * @var \SimpleXMLElement|string
     */
    private $config;
    /**
     * @var ModuleDefinition[]|array
     */
    private $modules;

    public function __construct(Configuration $config)
    {
        foreach ($config->getModules() as $name => $module) {
            $this->modules[$name] = new ModuleDefinition($module);
        }
        foreach($config->getModules() as $name=>$module){
            $parent = $module['parent'];
            if ($parent){
                $this->modules[$parent]->addChild($this->modules[$module['name']]);
            }
        }
    }

    /**
     * @param string $namespaceOrFullClassName
     * @return ModuleDefinition|null
     */
    public function getModuleNameByNamespace($namespaceOrFullClassName)
    {
        $namespaceOrFullClassName = ModuleDefinition::cleanNamespace($namespaceOrFullClassName);
        foreach ($this->modules as $module) {
            /** @var ModuleDefinition $module */
            if ($module->containsClass($namespaceOrFullClassName) && !$module->childrenContainsClass($namespaceOrFullClassName)) {
                return $module;
            }
        }
        return null;
    }


}