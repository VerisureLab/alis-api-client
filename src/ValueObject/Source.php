<?php

namespace VerisureLab\Library\AlisApiClient\ValueObject;

class Source
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $supplierId;

    /**
     * @var string
     */
    private $channelId;

    /**
     * Factory from name and code
     *
     * @param string $name
     * @param string $supplierId
     * @param string $channelId
     *
     * @return Source
     */
    public static function fromAllData(string $name, string $supplierId, string $channelId): self
    {
        $blacklist = new static();
        $blacklist->name = $name;
        $blacklist->supplierId = $supplierId;
        $blacklist->channelId = $channelId;

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
     * Get supplierId
     *
     * @return string
     */
    public function getSupplierId(): string
    {
        return $this->supplierId;
    }

    /**
     * Get channelId
     *
     * @return string
     */
    public function getChannelId(): string
    {
        return $this->channelId;
    }
}