<?php

require_once "../vendor/autoload.php";

use LendInvest\Entity\Tranche;
use LendInvest\Entity\Loan;
use LendInvest\Entity\Investor;
use LendInvest\Entity\Investment;
use LendInvest\Entity\Wallet;
use LendInvest\Utils\Money;

/**
 * Class Investor
 *
 * I will not mock Money, it's an extremely simple class. I'll test it here in the context of a wallet.
 */
class InvestorTest extends \PHPUnit\Framework\TestCase
{
    /** @var Investor */
    private $investor;

    /** @var Tranche | \PHPUnit\Framework\MockObject\MockObject */
    private $mockTranche;

    /** @var Loan | \PHPUnit\Framework\MockObject\MockObject */
    private $mockLoan;

    /** @var Investment | \PHPUnit\Framework\MockObject\MockObject */
    private $mockInvestment;

    /** @var Wallet | \PHPUnit\Framework\MockObject\MockObject */
    private $mockWallet;

    /** @throws Exception */
    public function setup()
    {
        $this->mockTranche = $this->getMockBuilder(Tranche::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->mockLoan = $this->getMockBuilder(Loan::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->mockWallet = $this->getMockBuilder(Wallet::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->mockInvestment = $this->getMockBuilder(Investment::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    /** @throws Exception */
    public function testCanInvest()
    {
        $this->investor = new Investor(1);
        $this->mockWallet->expects($this->any())->method('getCurrency')
            ->willReturn('GBP');

        $this->mockTranche->expects($this->once())->method('addInvestment')
            ->with($this->mockInvestment);

        $this->mockInvestment->expects($this->any())->method('getTranche')
            ->willReturn($this->mockTranche);

        $this->mockInvestment->expects($this->any())->method('getAmount')
            ->willReturn(new Money(200, 'GBP'));

        $this->mockWallet->expects($this->once())->method('withdraw');

        $this->investor->setWallets([$this->mockWallet]);
        $this->investor->setInvestments([$this->mockInvestment]);

        $this->investor->invest($this->mockInvestment);
    }
}
