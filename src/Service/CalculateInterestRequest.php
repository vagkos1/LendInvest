<?php

namespace LendInvest\Service;


class CalculateInterestRequest
{
    /** @var int */
    private $id;

    /** @var \DateTime */
    private $startDate;

    /** @var \DateTime */
    private $endDate;

    public function __construct(int $id, \DateTime $startDate, \DateTime $endDate)
    {
        $this->id = $id;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getStartDate(): \DateTime
    {
        return $this->startDate;
    }

    public function getEndDate(): \DateTime
    {
        return $this->endDate;
    }
}
