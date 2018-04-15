<?php

namespace LendInvest\Repository;


use LendInvest\Entity\Wallet;
use LendInvest\Entity\Investor;
use LendInvest\Utils\Money;

// simulates an Investor Repository
class InvestorRepository implements InvestorRepositoryInterface
{
    /**
     * In a real application, this method would retrieve the investor from the persistence layer
     *
     * @throws \Exception
     */
    public function findById(int $id): Investor
    {
        $investor = new Investor($id);

        // let's assume all investors have one GBP wallet with 1000 pounds for now
        $wallet = new Wallet($id, $investor, 'GBP');
        $wallet->deposit(new Money(1000, 'GBP'));

        $investor->setWallets([$wallet]);

        return $investor;
    }
}
