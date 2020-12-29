<?php


namespace Writ3it\ModularMonolithCs\Groups;


use Writ3it\ModularMonolithCs\AbstractGroup;

class NamespaceGroup extends AbstractGroup
{
    public $openingToken = T_NAMESPACE;
    public $closeToken = T_SEMICOLON;
    public $contentTokens = [
        T_NS_SEPARATOR,
        T_STRING
    ];
}