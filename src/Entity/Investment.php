<?php

namespace LendInvest\Entity;

use LendInvest\Entity\Tranche as Tranche;
use LendInvest\Entity\Investor as Investor;

use LendInvest\Utils\Money;

class Investment
{
    /** @var int - PK to Investment */
    private $id;

    /** @var Investor $investor - behaves like a Foreign Key to Investor (owning side M-1) */
    private $investor;

    /** @var Tranche $tranche - behaves like a Foreign Key to Tranche (owning side M-1) */
    private $tranche;

    /** @var int */
    private $amount;

    /** @var \DateTime */
    private $madeAt;

    /**
     * In a real-world application we would not be setting $id, we would use an autoincrement strategy upon DB insertion
     * @throws \Exception
     */
    public function __construct($id, Investor $investor, Tranche $tranche, Money $amount, \DateTime $madeAt)
    {
        $this->assertCanMakeInvestment($investor, $tranche, $amount, $madeAt);

        $this->id = $id;
        $this->investor = $investor;
        $this->tranche = $tranche;
        $this->amount = $amount;
        $this->madeAt = $madeAt;
    }

    /**
     * Constraints around making an Investment
     *
     * @throws \Exception
     */
    private function assertCanMakeInvestment(Investor $investor, Tranche $tranche, Money $amount, \DateTime $madeAt)
    {
        $amToInvest = $amount->getAmount();
        $amInvestedInTranche = $tranche->getAlreadyInvestedInCurrency($amount->getCurrency())->getAmount();
        $amMaxInTranche = $tranche->getMaxAvailable()->getAmount();

        if ($amToInvest > $investor->getWalletByCurrency($amount->getCurrency())->getBalance()->getAmount()) {
            throw new \InvalidArgumentException("Not enough money to make this investment.");
        }

        if ($amInvestedInTranche === $amMaxInTranche) {
            throw new \Exception("Tranche is full, cannot invest anymore");
        }

        if (($amToInvest + $amInvestedInTranche) > $amMaxInTranche) {
            throw new \InvalidArgumentException(
                "The max that can still be invested in this tranche is " .
                (string) ($amMaxInTranche - $amInvestedInTranche)
            );
        }

        if ($madeAt < $tranche->getLoan()->getStartDate()) {
            throw new \InvalidArgumentException("Cannot invest in a tranche for a Loan that has not started yet!");
        }
    }

    public function getId() : int
    {
        return $this->id;
    }

    public function getInvestor() : Investor
    {
        return $this->investor;
    }

    public function getTranche() : Tranche
    {
        return $this->tranche;
    }

    public function getAmount() : Money
    {
        return $this->amount;
    }

    public function getMadeAt()
    {
        return $this->madeAt;
    }

    /**
     * @return int $days - Days that this investment is (or has been) active for
     */
    public function getDaysInTheRunningMonth(\DateTime $endDate) : int
    {
        if ($this->madeAt->format('Y-M') === $endDate->format('Y-M')) {
            // compensate for the missing first day
            return $this->madeAt->diff($endDate)->days + 1;
        } else {
            return $endDate->modify('first day of this month')->diff($endDate)->days + 1;
        }

    }

    /**
     * @param \DateTime $endDate
     * @return float
     * @throws \Exception
     */
    public function getEarningsInRunningMonth(\DateTime $endDate)
    {
        $this->assertCanGetEarningsInRunningMonth($endDate);

        return round(($this->amount->getAmount() * $this->getTranche()->getDailySimpleInterestRate()/100 * $this->getDaysInTheRunningMonth($endDate)), 2);
    }

    /**
     * Constraints around getting the Earnings
     *
     * @throws \Exception
     */
    public function assertCanGetEarningsInRunningMonth(\DateTime $endDate): void
    {
        if ($endDate < $this->madeAt) {
            throw new \InvalidArgumentException("The investment endDate must come after the startDate.");
        }

        if ($endDate > $this->getTranche()->getLoan()->getEndDate()) {
            throw new \InvalidArgumentException("The investment endDate must come before the Loan this Tranche belongs to has ended.");
        }
    }
}
