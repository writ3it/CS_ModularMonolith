<?php


namespace Writ3it\CodingStandards\Tests\PhpCs;


use PHP_CodeSniffer\Config;
use PHP_CodeSniffer\Ruleset;
use PHP_CodeSniffer\Sniffs\Sniff;

class TestRuleset extends Ruleset
{
    public function __construct(Config $config)
    {
        parent::__construct($config);
        $this->sniffs = [];
        $this->tokenListeners = [];
    }

    public function registerSniff(Sniff $sniff)
    {
        $this->sniffs = [get_class($sniff) => $sniff];
        $this->populateTokenListeners();
        $this->sniffs = [get_class($sniff) => $sniff];
    }
}