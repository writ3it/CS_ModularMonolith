<?php


namespace Writ3it\CodingStandards\ModularMonolith\Groups;

use Writ3it\CodingStandards\ModularMonolith\AbstractGroup;

class UseGroup extends AbstractGroup
{
    public $openingToken = T_USE;
    public $closeToken = T_SEMICOLON;
    public $contentTokens = [
        T_NS_SEPARATOR,
        T_STRING
    ];
}