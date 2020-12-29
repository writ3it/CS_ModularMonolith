<?php


namespace Writ3it\CodingStandards\ModularMonolith;


abstract class AbstractGroup
{
    const STATE_CLOSED = 0;
    const STATE_OPEN = 1;
    public $openingToken;
    public $closeToken;
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
        if (self::STATE_CLOSED === $this->state && $token['code'] === $this->openingToken) {
            $this->reset();
            $this->state = self::STATE_OPEN;
            return;
        }

        if ($this->state === self::STATE_OPEN && in_array($token['code'], $this->contentTokens, true)) {
            $this->matchedContentTokens[] = $token;
            return;
        }

        if (self::STATE_OPEN === $this->state && $token['code'] === $this->closeToken) {
            $this->state = self::STATE_CLOSED;
            $callback = $this->processGroupCallback;
            $callback && $callback($this);
            return;
        }
    }

    private function reset()
    {
        $this->matchedContentTokens = [];
    }

    public function getContentAsString()
    {
        $contents = [];
        foreach ($this->matchedContentTokens as $token) {
            $contents[] = $token['content'];
        }
        return implode('', $contents);
    }

    public function getSupportedTokens()
    {
        return array_merge($this->contentTokens, [$this->openingToken, $this->closeToken]);
    }
}