<?php


namespace Writ3it\CodingStandards\ModularMonolith;


abstract class AbstractGroup
{
    const STATE_CLOSED = 0;
    const STATE_OPEN = 1;
    public $openingTokens;
    public $closeTokens;
    public $contentTokens = [];

    public $matchedContentTokens = [];

    private $state = self::STATE_CLOSED;
    /**
     * @var callable
     */
    private $processGroupCallback = null;

    public function __construct(callable $processGroupCallback)
    {
        $this->setProcessGroup($processGroupCallback);
    }

    public function setProcessGroup(callable $callback)
    {
        $this->processGroupCallback = $callback;
    }

    public function processToken($token)
    {
        if (self::STATE_CLOSED === $this->state && in_array($token['code'], $this->openingTokens, true)) {
            $this->reset();
            $this->state = self::STATE_OPEN;
            return;
        }

        if ($this->state === self::STATE_OPEN && in_array($token['code'], $this->contentTokens, true)) {
            $this->matchedContentTokens[] = $token;
            return;
        }

        if (self::STATE_OPEN === $this->state && in_array($token['code'], $this->closeTokens, true)) {
            $this->state = self::STATE_CLOSED;
            $callback = $this->processGroupCallback;
            $callback && $callback($this);
            return;
        }
    }

    public function reset()
    {
        $this->state = self::STATE_CLOSED;
        $this->matchedContentTokens = [];
    }

    /**
     * @param string $glue
     * @return string
     */
    public function getContentAsString($glue='')
    {
        $contents = $this->getContent();
        return implode($glue, $contents);
    }

    public function getSupportedTokens()
    {
        return array_merge($this->contentTokens, $this->openingTokens, $this->closeTokens);
    }


    /**
     * @return array
     */
    public function getContent()
    {
        $contents = [];
        foreach ($this->matchedContentTokens as $token) {
            $contents[] = $token['content'];
        }
        return $contents;
    }

    public function overrideContentByType($tokenCode, $newContent)
    {
        foreach($this->matchedContentTokens as &$token){
            if ($token['code'] === $tokenCode){
                $token['content'] = $newContent;
            }
        }
    }
}