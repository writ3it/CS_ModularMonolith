<?php


namespace Writ3it\CodingStandards\Tests\PhpCs;


use PHP_CodeSniffer\Config;
use PHP_CodeSniffer\Ruleset;
use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Util\Tokens;
use PHPUnit\Framework\TestCase;

class SniffTestCase extends TestCase
{
    public function setUp()
    {
        parent::setUp();
        class_exists(Tokens::class); // force to load file with constants
        !defined('PHP_CODESNIFFER_CBF') && define('PHP_CODESNIFFER_CBF', false);
        !defined('PHP_CODESNIFFER_IN_TESTS') && define('PHP_CODESNIFFER_IN_TESTS', true);
        !defined('PHP_CODESNIFFER_VERBOSITY') && define('PHP_CODESNIFFER_VERBOSITY', 1);
    }

    /**
     * @param string $phpFile
     * @param Sniff $sniff
     * @return TestFile
     */
    protected function executeSniff($phpFile, $sniff)
    {
        $config = new Config();
        $config->standards = ['Generic']; //some standard is required to start a ruleset
        $config->basepath = TEST_DIR.DIRECTORY_SEPARATOR;
        $ruleset = new TestRuleset($config);
        $ruleset->registerSniff($sniff);

        $file = new TestFile($phpFile, $ruleset, $config);
        $file->setContent(file_get_contents($phpFile));
        $file->process();
        return $file;
    }
}