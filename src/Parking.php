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
    }

    public function getCurrent()
    {
        return $this->currentDateTime;
    }

    public function setCurrent(DateTime $currentDateTime)
    {
        $this->currentDateTime = $currentDateTime;
    }

    public function getStartDate()
    {
        return $this->startDateTime;
    }

    public function getEndDate()
    {
        return $this->endDateTime;
    }

    public function addTariffPart($tariffPart)
    {
        $this->tariffParts[] = $tariffPart;
    }

    public function getTariffParts()
    {
        return $this->tariffParts;
    }
}
