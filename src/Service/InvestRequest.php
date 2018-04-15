<?php

namespace LendInvest\Service;


use LendInvest\Utils\Money;

class InvestRequest
{
    /** @var int */
    private $id;

    /** @var int */
    private $investorId;

    /** @var string */
    private $trancheName;

    /** @var Money */
    private $amount;

    /** @var \DateTime */
    private $date;

    public function __construct(int $id, int $investorId, string $trancheName, Money $amount, \DateTime $date)
    {
        $this->id = $id;
        $this->investorId = $investorId;
        $this->trancheName = $trancheName;
        $this->amount = $amount;
        $this->date = $date;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getInvestorId(): int
    {
        return $this->investorId;
    }

    public function getTrancheName(): string
    {
        return $this->trancheName;
    }

    public function getAmount(): Money
    {
        return $this->amount;
    }

    public function getDate(): \DateTime
    {
        return $this->date;
    }
}
