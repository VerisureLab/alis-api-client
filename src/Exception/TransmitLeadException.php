<?php

namespace VerisureLab\Library\AlisApiClient\Exception;

use Throwable;
use VerisureLab\Library\AlisApiClient\ValueObject\Lead;

class TransmitLeadException extends \RuntimeException
{
    /**
     * @var string
     */
    private $sourceId;

    /**
     * @var Lead
     */
    private $lead;

    public function __construct(string $sourceId, Lead $lead, Throwable $previous = null)
    {
        parent::__construct('Lead transmission error. The lead was backed up', 500, $previous);

        $this->sourceId = $sourceId;
        $this->lead = $lead;
    }

    /**
     * Get sourceId
     *
     * @return string
     */
    public function getSourceId(): string
    {
        return $this->sourceId;
    }

    /**
     * Get lead
     *
     * @return Lead
     */
    public function getLead(): Lead
    {
        return $this->lead;
    }
}