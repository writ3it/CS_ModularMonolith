<?php


namespace Writ3it\ModularMonolithCs;


use PHP_CodeSniffer\Sniffs\Sniff;

abstract class AbstractSniff implements Sniff
{
    /**
     * @var AbstractGroup[]
     */
    public $groups = [];

    public function register()
    {
        $tokens = [];
        foreach ($this->groups as $group) {
            $tokens[] = $group->getSupportedTokens();
        }
        return array_unique(array_merge(...$tokens));
    }
}