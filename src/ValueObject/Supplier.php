<?php

namespace VerisureLab\Library\AlisApiClient\ValueObject;

class Supplier
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
     * @var string
     */
    private $client;

    /**
     * Factory from name and code
     *
     * @param string $name
     * @param string $code
     * @param string $client
     *
     * @return Supplier
     */
    public static function fromNameAndCode(string $name, string $code, string $client): self
    {
        $blacklist = new static();
        $blacklist->name = $name;
        $blacklist->code = $code;
        $blacklist->client = $client;

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

    /**
     * Get client
     *
     * @return string
     */
    public function getClient(): string
    {
        return $this->client;
    }
}