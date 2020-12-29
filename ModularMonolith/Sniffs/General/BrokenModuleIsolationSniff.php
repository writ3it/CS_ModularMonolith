<?php


namespace Writ3it\CodingStandards\ModularMonolith\Sniffs\General;

use Writ3it\CodingStandards\ModularMonolith\AbstractGroup;
use Writ3it\CodingStandards\ModularMonolith\AbstractSniff;
use Writ3it\CodingStandards\ModularMonolith\Groups\NamespaceGroup;
use Writ3it\CodingStandards\ModularMonolith\Groups\UseGroup;

class BrokenModuleIsolationSniff extends AbstractSniff
{
    public $supportedTokenizers = array('PHP');
    /**
     * @var AbstractGroup[]
     */
    public $groups;
    /**
     * @var string
     */
    private $clientNamespace = 'undefined';

    /**
     * Path to the modules definitions config.
     * @var string
     */
    public $modules_definitions_path = '';

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
        var_dump($this->modules_definitions_path); die();
        $this->clientNamespace = $group->getContentAsString();
    }

    /**
     * @param AbstractGroup $group
     */
    public function processUse($group)
    {
    //    var_dump($this->config()->getSettings());
      //  var_dump('USE=' . $group->getContentAsString());
    }


}