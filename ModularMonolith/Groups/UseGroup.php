<?php


namespace Writ3it\ModularMonolithCs\Groups;

use Writ3it\ModularMonolithCs\AbstractGroup;

class UseGroup extends AbstractGroup
{
    public $openingToken = T_USE;
    public $closeToken = T_SEMICOLON;
    public $contentTokens = [
        T_NS_SEPARATOR,
        T_STRING
    ];
}