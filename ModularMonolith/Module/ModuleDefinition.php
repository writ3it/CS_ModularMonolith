<?php


namespace Writ3it\CodingStandards\ModularMonolith\Module;


use Writ3it\CodingStandards\ModularMonolith\Exception\InvalidModuleDefinitionException;

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
    /**
     * @var array
     */
    private $publicNamespaces = [];

    public function __construct($config)
    {
        $this->name = $config['name'];
        foreach($config['namespaces'] as $namespace){
            $this->validateNamespace($namespace);
            $this->namespaces[] = $namespace;
        }
        foreach($config['publicNamespaces'] as $namespace){
            $this->validateNamespace($namespace);
            if (!$this->containsClass($namespace)){
                $name = $this->getName();
                throw new InvalidModuleDefinitionException("Public source ns: $namespace is outside of module $name.");
            }
            $this->publicNamespaces[] = $namespace;
        }
    }

    /**
     * @param string $namespace
     * @throws InvalidModuleDefinitionException
     */
    public function validateNamespace($namespace){
        if (!preg_match('/^([A-Za-z0-9_]+\\\\)+$/', $namespace)){
            $name = $this->getName();
            throw new InvalidModuleDefinitionException("Invalid namespace $namespace in $name module definition.");
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
       return $this->collectionContainsClass($this->namespaces, $className);
    }

    private function collectionContainsClass(&$collection, $className){
        foreach ($collection as $namespace) {
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
     * @param $className
     * @return bool
     */
    public function isPrivateClass($className)
    {
        return $this->containsClass($className) && !$this->collectionContainsClass($this->publicNamespaces, $className);
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
            if ($child === $toAdd){
                return;
            }
        }
        $this->children[$toAdd->getName()] = $toAdd;
        $toAdd->setParent($this);
    }
}