<?php


namespace Writ3it\CodingStandards\Tests\PhpCs;


use PHP_CodeSniffer\Files\File;

class TestFile extends File
{
    /**
     * @param int $line
     * @param string $source
     * @return bool
     */
    public function hasError($line, $source)
    {
        if (!array_key_exists($line, $this->errors)) {
            return false;
        }
        $lineErrors = $this->errors[$line];
        foreach ($lineErrors as $col => $errors) {
            foreach ($errors as $error) {
                if ($error['source'] === $source) {
                    return true;
                }
            }
        }
        return false;
    }
}