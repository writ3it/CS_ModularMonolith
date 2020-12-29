<?php


namespace Writ3it\CodingStandards\ModularMonolith\Module;

use Writ3it\CodingStandards\ModularMonolith\Exception\ConfigurationException;

class Configuration
{

    private $modules = [];
    private $parentModule = null;

    public function __construct($configPath)
    {
        $this->loadConfig($configPath);
    }

    private function loadConfig($configPath)
    {
        if (!file_exists($configPath)) {
            throw new ConfigurationException("Modules definitions file {$configPath} not found.");
        }
        $config = simplexml_load_string(file_get_contents($configPath));
        $this->processConfig($config);
    }

    private function processConfig(\SimpleXMLElement $config)
    {
        foreach ($config->children() as $child) {
            switch ($child->getName())
            {
                case 'module':
                    $attributes = $child->attributes();
                    $name = static::getFullName($attributes['name']->__toString(), $this->parentModule);
                    $this->addModule($name);
                    $this->processModule($name, $child);
                break;
            }
        }
    }

    /**
     * @param string $name
     */
    private function addModule($name)
    {
        $this->modules[$name] = ['name' => $name, 'namespaces' => [], 'parent'=>null, 'publicNamespaces'=>[]];
    }

    private static function getFullName($name, $parent){
        return $parent?$parent.'.'.$name:$name;
    }

    /**
     * @param string $name
     * @param \SimpleXMLElement $module
     * @throws ConfigurationException
     */
    private function processModule($name, $module)
    {
        $this->setModuleParent($name, $this->parentModule);
        foreach ($module->children() as $child) {
            switch ($child->getName()) {
                case 'source':
                    $attributes = $child->attributes();
                    $this->addModuleSource($name, $attributes['namespace']->__toString());
                    break;
                case 'public':
                    foreach($child->children() as $source){
                        $attributes = $source->attributes();
                        $this->addModulePublicSource($name, $attributes['namespace']->__toString());
                    }
                    break;
                case 'submodules':
                    $oldParent = $this->parentModule;
                    $this->parentModule = $name;
                    $this->processConfig($child);
                    $this->parentModule = $oldParent;
                   break;
            }
        }

    }

    private function addModuleSource($name, $namespace)
    {
        if (!array_key_exists($name, $this->modules)) {
            throw new ConfigurationException("You have to use <source/> at <module/> element.");
        }
        $this->modules[$name]['namespaces'][] = $namespace;
    }

    /**
     * @return array
     */
    public function getModules()
    {
        return $this->modules;
    }

    private function setModuleParent($name, $parentModule)
    {
        if (!array_key_exists($name, $this->modules)) {
            throw new ConfigurationException("Module {$name} doesn't known.");
        }
        $this->modules[$name]['parent']= $parentModule;
    }

    private function addModulePublicSource($name, $namespace)
    {
        if (!array_key_exists($name, $this->modules)) {
            throw new ConfigurationException("You have to use <source/> at <module/> element.");
        }
        $this->modules[$name]['publicNamespaces'][] = $namespace;
    }
}