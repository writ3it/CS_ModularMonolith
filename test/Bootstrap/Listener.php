<?php


namespace Writ3it\CodingStandards\Tests\Bootstrap;


use PHPUnit\Framework\BaseTestListener;
use PHPUnit_Framework_TestSuite;

class Listener extends BaseTestListener
{
    public function startTestSuite(PHPUnit_Framework_TestSuite $suite)
    {
        if (strpos($suite->getName(), " PHPCS ") !== false) {
            require_once 'bootstrap_phpcs.php';
        } else {
            require_once 'bootstrap_native.php';
        }
    }
}