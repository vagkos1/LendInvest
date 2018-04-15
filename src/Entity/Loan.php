<?php

namespace LendInvest\Entity;

use LendInvest\Entity\Tranche as Tranche;

class Loan
{
    /** @var int - PK to Loan*/
    private $id;

    /** @var \DateTime */
    private $startDate;

    /** @var \DateTime */
    private $endDate;

    /** @var string */
    private $currency;

    /** @var Tranche[] $tranches - inverse side M-1 to Tranche */
    private $tranches;

    /**
     * Creates an empty load with no tranches.
     * In a real-world application we would not be setting $id, we would use an autoincrement strategy upon DB insertion
     */
    public function __construct(int $id, \DateTime $startDate, \DateTime $endDate, string $currency)
    {
        $this->id = $id;
        $this->currency = $currency;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->tranches = [];
    }

    public function getId() : int
    {
        return $this->id;
    }

    public function getCurrency() : string
    {
        return $this->currency;
    }

    /** @param Tranche[] $tranches */
    public function setTranches(array $tranches) : void
    {
        foreach ($tranches as $tranche) {
            $this->addTranche($tranche);
        }
    }

    public function addTranche(Tranche $tranche)
    {
        $this->tranches[] = $tranche;
    }

    /** @return Tranche[] */
    public function getTranches() : array
    {
        return $this->tranches;
    }

    public function getStartDate() : \DateTime
    {
        return $this->startDate;
    }

    public function getEndDate() : \DateTime
    {
        return $this->endDate;
    }

    public function hasTranches() : bool
    {
        return !empty($this->tranches);
    }
}
