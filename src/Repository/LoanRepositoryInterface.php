<?php

namespace LendInvest\Repository;


use LendInvest\Entity\Loan as Loan;

interface LoanRepositoryInterface
{
    public function findById(int $id) : Loan;
}
