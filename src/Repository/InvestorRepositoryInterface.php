<?php

namespace LendInvest\Repository;


use LendInvest\Entity\Investor as Investor;

interface InvestorRepositoryInterface
{
    public function findById(int $id) : Investor;
}
