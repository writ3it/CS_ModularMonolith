<?php


namespace Writ3it\CodingStandards\ModularMonolith\Groups;


use Writ3it\CodingStandards\ModularMonolith\AbstractGroup;

class NamespaceGroup extends AbstractGroup
{
    public $openingTokens = [T_NAMESPACE];
    public $closeTokens = [T_SEMICOLON];
    public $contentTokens = [
        T_NS_SEPARATOR,
        T_STRING
    ];
}