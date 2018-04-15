<?php

namespace LendInvest\Repository;


use LendInvest\Entity\Investment;

// simulates an Investor Repository
class InvestmentRepository implements InvestmentRepositoryInterface
{
    /** @var Investment[] */
    private $investments = array();

    // this would not happen in a real app.
    public function addInvestment(Investment $investment)
    {
        $this->investments[] = $investment;
    }

    /**
     * Let's return all the investments for now
     * @return Investment[]
     */
    public function findByPeriod(\DateTime $startDate, \DateTime $endDate): array
    {
        return $this->investments;
    }

    /**
     * In a real application, this method would retrieve the investment from the persistence layer
     */
    public function findById(int $id): Investment
    {
        // TODO: Implement findById() method.
    }
}
