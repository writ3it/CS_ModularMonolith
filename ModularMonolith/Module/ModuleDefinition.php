<?php


namespace Writ3it\CodingStandards\ModularMonolith\Module;


class ModuleDefinition
{
    /**
     * @var string
     */
    private $name;
    /**
     * @var string[]|array
     */
    private $namespaces;

    public function __construct($config)
    {
        $this->name = $config['name'];
        $this->namespaces = $config['namespaces'];
    }

    /**
     * @param string $className
     * @return bool
     */
    public function containsClass($className)
    {
        foreach ($this->namespaces as $namespace) {
            $prefix = substr($className, 0, strlen($namespace));
            if ($prefix === $namespace) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param $obj
     * @return bool
     */
    public function equals($obj)
    {
        if (!$obj instanceof self) {
            return false;
        }
        return $obj->getName() === $this->getName();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}