<?php


namespace Writ3it\CodingStandards\ModularMonolith\Groups;


use Writ3it\CodingStandards\ModularMonolith\AbstractGroup;

class ClassConstAndStaticGroup extends AbstractGroup
{
    public $openingTokens = [T_CLOSE_PARENTHESIS, T_EQUAL];
    public $closeTokens = [T_DOUBLE_COLON];
    public $contentTokens = [
        T_NS_SEPARATOR,
        T_STRING
    ];
}