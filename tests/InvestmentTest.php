<?php

require_once "../vendor/autoload.php";

use LendInvest\Utils\Money;
use LendInvest\Entity\Tranche;
use LendInvest\Entity\Loan;
use LendInvest\Entity\Investor;
use LendInvest\Entity\Wallet;
use LendInvest\Entity\Investment;

/**
 * Class InvestmentTest
 *
 * I will not mock Money, it's an extremely simple class. I'll test it here in the context of a wallet.
 */
class InvestmentTest extends \PHPUnit\Framework\TestCase
{
    /** @var Investor | \PHPUnit\Framework\MockObject\MockObject */
    private $mockInvestor;

    /** @var Tranche | \PHPUnit\Framework\MockObject\MockObject */
    private $mockTranche;

    /** @var Loan | \PHPUnit\Framework\MockObject\MockObject */
    private $mockLoan;

    /** @var Wallet | \PHPUnit\Framework\MockObject\MockObject */
    private $mockWallet;

    /** @var Investment | \PHPUnit\Framework\MockObject\MockObject */
    private $investment;

    /** @throws Exception */
    public function setup()
    {
        $this->mockInvestor = $this->getMockBuilder(Investor::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->mockTranche = $this->getMockBuilder(Tranche::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->mockLoan = $this->getMockBuilder(Loan::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->mockWallet = $this->getMockBuilder(Wallet::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    /** @throws Exception */
    public function testCannotConstructInvestmentForTrancheBelongingToLoanThatHasNotYetStarted()
    {
        // Loan starts one day after the investment!
        $this->mockLoan->expects($this->any())->method('getStartDate')
            ->willReturn(new DateTime('2015-10-04'));

        $this->mockTranche->expects($this->any())->method('getLoan')
            ->willReturn($this->mockLoan);

        $this->mockTranche->expects($this->any())->method('getMaxAvailable')
            ->willReturn(new Money(1000, "GBP"));

        $this->mockTranche->expects($this->any())->method('getAlreadyInvestedInCurrency')
            ->with($this->equalTo('GBP'))
            ->willReturn(new Money(0, "GBP"));

        $this->mockWallet->expects($this->any())->method('getBalance')
            ->willReturn(new Money(5000, "GBP"));

        $this->mockInvestor->expects($this->any())->method('getWalletByCurrency')
            ->willReturn($this->mockWallet);

        $this->expectException(InvalidArgumentException::class);

        $this->investment = new Investment(
            1,
            $this->mockInvestor,
            $this->mockTranche,
            new Money(1000, "GBP"),
            new DateTime('2015-10-03')
        );
    }

    /** @throws Exception */
    public function testCannotConstructInvestmentForTrancheThatIsFull()
    {
        $this->mockLoan->expects($this->any())->method('getStartDate')
            ->willReturn(new DateTime('2015-10-01'));

        $this->mockTranche->expects($this->any())->method('getLoan')
            ->willReturn($this->mockLoan);

        $this->mockTranche->expects($this->any())->method('getMaxAvailable')
            ->willReturn(new Money(1000, "GBP"));

        // getAlreadyInvested has reached max!
        $this->mockTranche->expects($this->any())->method('getAlreadyInvestedInCurrency')
            ->with($this->equalTo('GBP'))
            ->willReturn(new Money(1000, "GBP"));

        $this->mockWallet->expects($this->any())->method('getBalance')
            ->willReturn(new Money(5000, "GBP"));

        $this->mockInvestor->expects($this->any())->method('getWalletByCurrency')
            ->willReturn($this->mockWallet);

        $this->expectException(Exception::class);

        $this->investment = new Investment(
            1,
            $this->mockInvestor,
            $this->mockTranche,
            new Money(1000, "GBP"),
            new DateTime('2015-10-03')
        );
    }

    /** @throws Exception */
    public function testCannotConstructInvestmentMoreThanTrancheCanTake()
    {
        $this->mockLoan->expects($this->any())->method('getStartDate')
            ->willReturn(new DateTime('2015-10-01'));

        $this->mockTranche->expects($this->any())->method('getLoan')
            ->willReturn($this->mockLoan);

        $this->mockTranche->expects($this->any())->method('getMaxAvailable')
            ->willReturn(new Money(1000, "GBP"));

        $this->mockTranche->expects($this->any())->method('getAlreadyInvestedInCurrency')
            ->with($this->equalTo('GBP'))
            ->willReturn(new Money(500, "GBP"));

        $this->mockWallet->expects($this->any())->method('getBalance')
            ->willReturn(new Money(5000, "GBP"));

        $this->mockInvestor->expects($this->any())->method('getWalletByCurrency')
            ->willReturn($this->mockWallet);

        $this->expectException(InvalidArgumentException::class);

        // trying to invest 1 pound more thn Tranche's max!
        $this->investment = new Investment(
            1,
            $this->mockInvestor,
            $this->mockTranche,
            new Money(501, "GBP"),
            new DateTime('2015-10-03')
        );
    }

    /** @throws Exception */
    public function testGetEarningsInRunningMonth()
    {
        $this->mockLoan->expects($this->any())->method('getStartDate')
            ->willReturn(new DateTime('2015-10-01'));

        $this->mockLoan->expects($this->any())->method('getEndDate')
            ->willReturn(new DateTime('2015-11-15'));

        $this->mockTranche->expects($this->any())->method('getLoan')
            ->willReturn($this->mockLoan);

        $this->mockTranche->expects($this->any())->method('getMaxAvailable')
            ->willReturn(new Money(1000, "GBP"));

        $this->mockTranche->expects($this->any())->method('getAlreadyInvestedInCurrency')
            ->with($this->equalTo('GBP'))
            ->willReturn(new Money(0, "GBP"));

        $this->mockTranche->expects($this->any())->method('getDailySimpleInterestRate')
            ->willReturn(0.096774);

        $this->mockWallet->expects($this->any())->method('getBalance')
            ->willReturn(new Money(5000, "GBP"));

        $this->mockInvestor->expects($this->any())->method('getWalletByCurrency')
            ->willReturn($this->mockWallet);

        $this->investment = new Investment(
            1,
            $this->mockInvestor,
            $this->mockTranche,
            new Money(1000, "GBP"),
            new DateTime('2015-10-03')
        );

        $earnings = $this->investment->getEarningsInRunningMonth(new DateTime('2015-10-31'));

        $this->assertEquals(28.06, $earnings);
    }

    /** @throws Exception */
    public function testCannotConstructInvestmentNotEnoughBalanceInInvestorsWallet()
    {
        $this->mockLoan->expects($this->any())->method('getStartDate')
            ->willReturn(new DateTime('2015-10-01'));

        $this->mockLoan->expects($this->any())->method('getEndDate')
            ->willReturn(new DateTime('2015-11-15'));

        $this->mockTranche->expects($this->any())->method('getLoan')
            ->willReturn($this->mockLoan);

        $this->mockTranche->expects($this->any())->method('getMaxAvailable')
            ->willReturn(new Money(1000, "GBP"));

        $this->mockTranche->expects($this->any())->method('getAlreadyInvestedInCurrency')
            ->with($this->equalTo('GBP'))
            ->willReturn(new Money(0, "GBP"));

        $this->mockWallet->expects($this->any())->method('getBalance')
            ->willReturn(new Money(999, "GBP"));

        $this->mockInvestor->expects($this->any())->method('getWalletByCurrency')
            ->willReturn($this->mockWallet);

        $this->expectException(InvalidArgumentException::class);

        $this->investment = new Investment(
            1,
            $this->mockInvestor,
            $this->mockTranche,
            new Money(1000, "GBP"),
            new DateTime('2015-10-03')
        );
    }
}
