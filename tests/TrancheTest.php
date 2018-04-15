<?php

require_once "../vendor/autoload.php";

use LendInvest\Utils\Money;
use LendInvest\Entity\Investor;
use LendInvest\Entity\Loan;
use LendInvest\Entity\Tranche;
use LendInvest\Entity\Investment;

/**
 * Class TrancheTest
 *
 * I will not mock Money, it's an extremely simple class.
 */
class TrancheTest extends \PHPUnit\Framework\TestCase
{
    /** @var Investor | \PHPUnit\Framework\MockObject\MockObject */
    private $mockInvestor;

    /** @var Loan | \PHPUnit\Framework\MockObject\MockObject */
    private $mockLoan;


    /** @var Investment | \PHPUnit\Framework\MockObject\MockObject */
    private $mockInvestment1;

    /** @var Investment | \PHPUnit\Framework\MockObject\MockObject */
    private $mockInvestment2;

    /** @var Tranche */
    private $tranche;

    /** @throws Exception */
    public function setup()
    {
        $this->mockInvestor = $this->getMockBuilder(Investor::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->mockLoan = $this->getMockBuilder(Loan::class)
            ->disableOriginalConstructor()
            ->getMock();

        $maxAvailable = new Money(1000, "GBP");

        $this->tranche = new Tranche(1, "A", $this->mockLoan, $maxAvailable, 3);
    }

    public function testGetAlreadyInvested()
    {
        $this->mockInvestment1 = $this->getMockBuilder(Investment::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->mockInvestment2 = $this->getMockBuilder(Investment::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->mockInvestment1->expects($this->any())->method('getAmount')
            ->willReturn(new Money(200, 'GBP'));

        $this->mockInvestment2->expects($this->any())->method('getAmount')
            ->willReturn(new Money(110, 'GBP'));

        $this->tranche->addInvestment($this->mockInvestment1);
        $this->tranche->addInvestment($this->mockInvestment2);

        $this->assertEquals(new Money(310, 'GBP'), $this->tranche->getAlreadyInvestedInCurrency('GBP'));
    }
}
