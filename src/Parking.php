<?php

namespace Inteleon\SmsPark;

use DateTime;

class Parking
{
    /** @var DateTime */
    private $startDateTime;

    /** @var DateTime */
    private $endDateTime;

    /** @var DateTime */
    private $currentDateTime;

    /** @var array */
    private $tariffParts;

    public function __construct(DateTime $startDateTime, DateTime $endDateTime)
    {
        $this->startDateTime = $startDateTime;
        $this->currentDateTime = $startDateTime;
        $this->endDateTime = $endDateTime;
        $this->tariffParts = [];
    }

    /**
     * @return \DateTime
     */
    public function getCurrent()
    {
        return $this->currentDateTime;
    }

    /**
     * @param DateTime $currentDateTime
     */
    public function setCurrent(DateTime $currentDateTime)
    {
        $this->currentDateTime = $currentDateTime;
    }

    /**
     * @return \DateTime
     */
    public function getStartDate()
    {
        return $this->startDateTime;
    }

    /**
     * @return \DateTime
     */
    public function getEndDate()
    {
        return $this->endDateTime;
    }

    /**
     * @param int $tariffPart
     */
    public function addTariffPart($tariffPart)
    {
        $this->tariffParts[] = $tariffPart;
    }

    /**
     * @return array
     */
    public function getTariffParts()
    {
        return $this->tariffParts;
    }
}
