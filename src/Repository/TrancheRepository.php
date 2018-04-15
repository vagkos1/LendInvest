<?php

namespace LendInvest\Repository;


use LendInvest\Entity\Tranche;
use LendInvest\Entity\Loan;
use LendInvest\Utils\Money;

// simulates an Tranche Repository
class TrancheRepository implements TrancheRepositoryInterface
{
    private static $init = false;
    public static $trancheA;
    public static $trancheB;
    public static $loanRepo;

    public function __construct(LoanRepositoryInterface $loanRepo)
    {
        // normally instead of a Tranche
    }

    public static function init()
    {
        if (!self::$init) {

            self::$loanRepo = new Loan(
                1,
                new \DateTime('2015-10-01'),
                new \DateTime('2015-11-15'),
                "GBP"
            );

            self::$trancheA = new Tranche(
                1,
                'A',
                self::$loanRepo,
                new Money(1000, 'GBP'),
                3
            );

            self::$trancheB = new Tranche(
                1,
                'B',
                self::$loanRepo,
                new Money(1000, 'GBP'),
                6
            );
        }

        self::$init = true;
    }

    public function findById(int $id) : Tranche
    {
        // @todo Implement
    }

    /**
     * I'll hardcode the 2 tranches the test asks for and use static so that the same Tranches get returned.
     *
     * In a real application, this method would retrieve the tranche from the persistence layer (assuming name is unique).
     * Otherwise we can findByLoan and let the investor choose a Tranche
     *
     * @return Tranche|null
     */
    public function findByName(string $name) : Tranche
    {
        if ($name == 'A') {
            return self::$trancheA;
        }

        if ($name == 'B') {
            return self::$trancheB;
        }

        return null;
    }

}