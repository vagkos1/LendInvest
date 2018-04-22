<?php

namespace LendInvest\Entity;

use LendInvest\Entity\Loan as Loan;
use LendInvest\Entity\Investment as Investment;

use LendInvest\Utils\Money;

class Tranche
{
    /** @var int $id - behaves like a Primary Key to Tranche */
    private $id;

    /** @var string $name */
    private $name;

    /** @var Loan $loan - behaves like a Foreign Key to Loan (owning side M-1)*/
    private $loan;

    /** @var int $interest - monthly interest - divide by 100 to get the interest rate */
    private $interest;

    /**@var Money - the maximum an investor can invest in this tranche */
    private $maxAvailable = 1000;

    /** @var Investment[] $investments - the inverse side of the M-1 to Tranche*/
    private $investments = [];

    /**
     * In a real-world application we would not be setting $id, we would use an autoincrement strategy upon DB insertion
     */
    public function __construct(int $id, string $name, Loan $loan, Money $maxAvailable, int $interest)
    {
        $this->id = $id;
        $this->name = $name;
        $this->loan = $loan;
        $this->maxAvailable = $maxAvailable;
        $this->interest = $interest;
    }

    public function getId() : int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getLoan() : Loan
    {
        return $this->loan;
    }

    public function getInterest() : int
    {
        return $this->interest;
    }

    public function getMaxAvailable(): Money
    {
        return $this->maxAvailable;
    }

    public function getDailySimpleInterestRate(\DateTime $dateTime) : float
    {
        // calculate how many days a particular month in a specific year had.
        $days = cal_days_in_month(CAL_GREGORIAN, $dateTime->format('m'), $dateTime->format('Y'));

        return round($this->interest / $days, 6);
    }

    // lets assume all investments in GBP here
    public function getAlreadyInvestedInCurrency(string $currency) : Money
    {
        $amount = 0;

        foreach ($this->investments as $investment) {
            if ($investment->getAmount()->getCurrency() === $currency) {
                $amount += $investment->getAmount()->getAmount();
            }
        }

        return new Money($amount, $currency);
    }

    /**
     * @return Investment[]
     */
    public function getInvestments() : array
    {
        return $this->investments;
    }

    public function addInvestment(Investment $investment)
    {
        $this->investments[] = $investment;
    }
}
