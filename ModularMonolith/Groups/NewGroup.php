<?php


namespace Writ3it\CodingStandards\ModularMonolith\Groups;


use Writ3it\CodingStandards\ModularMonolith\AbstractGroup;

class NewGroup extends AbstractGroup
{
    public $openingTokens = [T_NEW];
    public $closeTokens = [T_SEMICOLON, T_OPEN_PARENTHESIS];
    public $contentTokens = [
        T_NS_SEPARATOR,
        T_STRING
    ];
}