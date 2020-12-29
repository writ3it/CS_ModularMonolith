<?php


namespace Writ3it\CodingStandards\ModularMonolith\Module;


class ModuleDefinition
{
    const NS_SEPARATOR = '\\';
    /**
     * @var string
     */
    private $name;
    /**
     * @var string[]|array
     */
    private $namespaces;
    /**
     * @var self
     */
    private $parentModule;
    /**
     * @var self[]
     */
    private $children = [];

    public function __construct($config)
    {
        $this->name = $config['name'];
        foreach($config['namespaces'] as $namespace){
            $this->namespaces[] = static::cleanNamespace($namespace);
        }
    }

    /**
     * @param string $namespace
     * @return string
     */
    public static function cleanNamespace($namespace)
    {
        return ltrim($namespace,self::NS_SEPARATOR).self::NS_SEPARATOR;
    }

    protected function setParent($module){
        $this->parentModule = $module;
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
     * @param $className
     * @return bool
     */
    public function childrenContainsClass($className){
        foreach($this->children as $child){
            if ($child->containsClass($className)){
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

    /**
     * @param self $toRemove
     */
    public function removeChild($toRemove)
    {
        foreach($this->children as $name=>$child){
            if ($child === $toRemove){
                unset($this->children[$name]);
                $child->setParent(null);
            }
        }
    }
    /**
     * @param self $toAdd
     */
    public function addChild($toAdd)
    {
        foreach($this->children as $name=>$child){
            if ($child == $toAdd){
                return;
            }
        }
        $this->children[$toAdd->getName()] = $toAdd;
        $toAdd->setParent($this);
    }
}