<?php


namespace Writ3it\CodingStandards\ModularMonolith;


use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use Writ3it\CodingStandards\ModularMonolith\Module\Configuration;
use Writ3it\CodingStandards\ModularMonolith\Module\ModuleRecognizer;

abstract class AbstractModuleSniff implements Sniff
{
    /**
     * @var AbstractGroup[]
     */
    public $groups = [];
    /**
     * Path to the modules definitions config.
     * @var string
     */
    public $modules_definitions_path = '';
    /**
     * @var ModuleRecognizer
     */
    protected $moduleRecognizer;
    /**
     * @var File
     */
    private $phpcsFile = null;
    /**
     * @var int
     */
    private $stackPtr;

    public function register()
    {
        $tokens = [];
        foreach ($this->groups as $group) {
            $tokens[] = $group->getSupportedTokens();
        }
        return array_unique(array_merge(...$tokens));
    }

    public function process(File $phpcsFile, $stackPtr)
    {
        $oldFile = $this->phpcsFile;
        $this->phpcsFile = $phpcsFile;
        $this->stackPtr = $stackPtr;
        if (!$this->moduleRecognizer) {
            $this->initializeRecognizer();
        }
        if ($oldFile !== $this->phpcsFile) {
            $this->resetGroups();
            $this->initializeFile();
        }
        $tokens = $phpcsFile->getTokens();
        foreach ($this->groups as $group) {
            $group->processToken($tokens[$stackPtr]);
        }
    }

    /**
     * Override if you need
     */
    public function initializeRecognizer()
    {
        $path = rtrim($this->config()->basepath, '/') . '/' . $this->modules_definitions_path;
        $this->moduleRecognizer = new ModuleRecognizer(new Configuration($path));
    }

    /**
     * @return \PHP_CodeSniffer\Config|null
     */
    protected function config()
    {
        return $this->phpcsFile->config;
    }

    /**
     * Override if you need
     */
    public function initializeFile()
    {
    }

    /**
     * @param string $code
     * @param string $message
     * @param string $content
     */
    public function addError($code, $message, $content)
    {
        $this->phpcsFile->addError($message, $this->stackPtr, $code, $content);
    }

    private function resetGroups()
    {
        foreach ($this->groups as $group) {
            $group->reset();
        }
    }
}