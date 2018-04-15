<?php

namespace LendInvest\Repository;


use LendInvest\Entity\Tranche as Tranche;

interface TrancheRepositoryInterface
{
    public function findById(int $id) : Tranche;

    public function findByName(string $name) : Tranche;
}
