<?php

require_once "../vendor/autoload.php";

class AcceptanceTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test
     */
    public function everythingWorksTest()
    {
        $startDate = new \DateTime();
        $startDate->add(new \DateInterval('P1D'));

        $loan = new \LendInvest\Entity\Loan(
            1,
            new DateTime('2015-10-01'),
            new DateTime('2015-11-15'),
            'GBP'
        );

        $trancheA = new \LendInvest\Entity\Tranche(
            1,
            'A',
            $loan,
            new \LendInvest\Utils\Money(1000, "GBP"),
            3
        );

        $loan->setTranches([$trancheA]);

        $investor = new \LendInvest\Entity\Investor(2);

        $wallet = new \LendInvest\Entity\Wallet(1, $investor, "GBP");
        $wallet->deposit(new \LendInvest\Utils\Money(1000, "GBP"));
        $investor->setWallets([$wallet]);

        $investment = new \LendInvest\Entity\Investment(
            1,
            $investor,
            $trancheA,
            new \LendInvest\Utils\Money(1000, "GBP"),
            new DateTime('2015-10-03')
        );

        $earnings = $investment->getEarningsInRunningMonth(new DateTime('2015-10-31'));

        $this->assertEquals(28.06, $earnings);
    }
}
