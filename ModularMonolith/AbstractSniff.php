<?php


namespace Writ3it\CodingStandards\ModularMonolith;


use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

abstract class AbstractSniff implements Sniff
{
    /**
     * @var AbstractGroup[]
     */
    public $groups = [];
    /**
     * @var File
     */
    private $phpcsFile;
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
        $this->phpcsFile = $phpcsFile;
        $this->stackPtr = $stackPtr;
        $tokens = $phpcsFile->getTokens();
        foreach ($this->groups as $group) {
            $group->processToken($tokens[$stackPtr]);
        }
    }

    /**
     * @return \PHP_CodeSniffer\Config|null
     */
    protected function config()
    {
        return $this->phpcsFile->config;
    }
}