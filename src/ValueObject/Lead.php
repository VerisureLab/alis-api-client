<?php

namespace VerisureLab\Library\AlisApiClient\ValueObject;

class Lead
{
    /**
     * @var string
     */
    private $phoneNumber;

    /**
     * @var null|string
     */
    private $zipCode;

    /**
     * @var array
     */
    private $data;

    /**
     * Factory from phone number and data
     *
     * @param string $phoneNumber
     * @param array $data
     *
     * @return Lead
     */
    public static function fromPhoneNumberAndData(string $phoneNumber, array $data): self
    {
        $lead = new static();
        $lead->phoneNumber = $phoneNumber;
        $lead->data = $data;

        return $lead;
    }

    /**
     * Factory from all data
     *
     * @param string $phoneNumber
     * @param string $zipCode
     * @param array $data
     *
     * @return Lead
     */
    public static function fromAll(string $phoneNumber, string $zipCode, array $data): self
    {
        $lead = new static();
        $lead->phoneNumber = $phoneNumber;
        $lead->zipCode = $zipCode;
        $lead->data = $data;

        return $lead;
    }

    /**
     * Get phoneNumber
     *
     * @return string
     */
    public function getPhoneNumber(): string
    {
        return $this->phoneNumber;
    }

    /**
     * Get zipCode
     *
     * @return string|null
     */
    public function getZipCode(): ?string
    {
        return $this->zipCode;
    }

    /**
     * Get data
     *
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }
}