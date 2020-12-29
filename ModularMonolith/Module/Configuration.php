<?php


namespace Writ3it\CodingStandards\ModularMonolith\Module;

use Writ3it\CodingStandards\ModularMonolith\Exception\ConfigurationException;

class Configuration
{

    private $modules = [];

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
        switch ($config->getName()) {
            case 'module':
                $attributes = $config->attributes();
                $name = $attributes['name']->__toString();
                $this->addModule($name);
                $this->processModule($name, $config);
                break;
        }
        foreach ($config->children() as $child) {
            $this->processConfig($child);
        }
    }

    /**
     * @param string $name
     */
    private function addModule($name)
    {
        $this->modules[$name] = ['name' => $name, 'namespaces' => []];
    }

    /**
     * @param string $name
     * @param \SimpleXMLElement $module
     */
    private function processModule($name, $module)
    {
        foreach ($module->children() as $child) {
            switch ($child->getName()) {
                case 'source':
                    $attributes = $child->attributes();
                    $this->addModuleSource($name, $attributes['namespace']->__toString());
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
}