<?php

namespace VerisureLab\Library\AlisApiClient\ValueObject;

class Blacklist
{
    /**
     * @var string
     */
    private $scope;

    /**
     * @var string
     */
    private $value;

    /**
     * Factory from scope and value
     *
     * @param string $scope
     * @param string $value
     *
     * @return Blacklist
     */
    public static function fromScopeAndValue(string $scope, string $value): self
    {
        $blacklist = new static();
        $blacklist->scope = $scope;
        $blacklist->value = $value;

        return $blacklist;
    }

    /**
     * Get scope
     *
     * @return string
     */
    public function getScope(): string
    {
        return $this->scope;
    }

    /**
     * Get value
     *
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }
}