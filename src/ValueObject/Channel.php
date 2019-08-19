<?php

namespace VerisureLab\Library\AlisApiClient\ValueObject;

class Channel
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $code;

    /**
     * Factory from name and code
     *
     * @param string $name
     * @param string $code
     *
     * @return Channel
     */
    public static function fromNameAndCode(string $name, string $code): self
    {
        $blacklist = new static();
        $blacklist->name = $name;
        $blacklist->code = $code;

        return $blacklist;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }
}