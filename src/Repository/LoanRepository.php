<?php

namespace LendInvest\Repository;


use LendInvest\Entity\Loan;

// simulates an Loan Repository
class LoanRepository implements LoanRepositoryInterface
{
    /**
     * we'll hardcode the loan the test asks for
     * In a real application, this method would retrieve the loans from the persistence layer
     *
     * @param int $id
     * @return Loan|null
     */
    public function findById(int $id): Loan
    {
        if ($id === 1) {
            return new Loan(
                $id,
                new \DateTime('2015-10-01'),
                new \DateTime('2015-11-15'),
                "GBP"
            );
        }

        return null;
    }

}