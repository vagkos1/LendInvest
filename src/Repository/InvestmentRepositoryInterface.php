<?php

namespace LendInvest\Repository;


use LendInvest\Entity\Investment as Investment;

interface InvestmentRepositoryInterface
{
    public function findById(int $id) : Investment;

    /** @return Investment[] */
    public function findByPeriod(\DateTime $startDate, \DateTime $endDate) : array;
}
