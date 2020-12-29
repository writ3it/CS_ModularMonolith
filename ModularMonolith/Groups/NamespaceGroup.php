<?php


namespace Writ3it\CodingStandards\ModularMonolith\Groups;


use Writ3it\CodingStandards\ModularMonolith\AbstractGroup;

class NamespaceGroup extends AbstractGroup
{
    public $openingToken = [T_NAMESPACE];
    public $closeToken = [T_SEMICOLON];
    public $contentTokens = [
        T_NS_SEPARATOR,
        T_STRING
    ];
}