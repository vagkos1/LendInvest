<?php

require_once "../vendor/autoload.php";

use LendInvest\Utils\Money;
use LendInvest\Entity\Wallet;
use LendInvest\Entity\Investor;

/**
 * Class WalletTest
 *
 * I will not mock Money, it's an extremely simple class. I'll test it here in the context of a wallet.
 */
class WalletTest extends \PHPUnit\Framework\TestCase
{
    /** @var Investor | \PHPUnit\Framework\MockObject\MockObject */
    private $mockInvestor;

    /** @var Wallet */
    private $wallet;

    /** @throws Exception */
    public function setup()
    {
        $this->mockInvestor = $this->getMockBuilder(Investor::class)
            ->disableOriginalConstructor()
            ->getMock();

        /** I could use a new Wallet for each test, but maybe it makes more sense to test a wallet's journey */
        $this->wallet = new Wallet(1, $this->mockInvestor, "GBP");
    }

    /** @throws Exception */
    public function testCanDeposit()
    {
        $this->wallet->deposit(new Money(200, "GBP"));

        $this->assertEquals(200, $this->wallet->getBalance()->getAmount());
        $this->assertEquals("GBP", $this->wallet->getBalance()->getCurrency());
    }

    /** @throws Exception */
    public function testCanWithdraw()
    {
        $this->wallet->deposit(new Money(200, "GBP"));
        $this->wallet->withdraw(new Money(150, "GBP"));

        $this->assertEquals(50, $this->wallet->getBalance()->getAmount());
        $this->assertEquals("GBP", $this->wallet->getBalance()->getCurrency());
    }

    /** @throws Exception */
    public function testCannotWithdrawMoreThanWalletsBalance()
    {
        $this->wallet->deposit(new Money(200, "GBP"));
        $this->wallet->withdraw(new Money(350, "GBP"));

        $this->assertEquals(0, $this->wallet->getBalance()->getAmount());
        $this->assertEquals("GBP", $this->wallet->getBalance()->getCurrency());
    }

    /**
     * This test in essence tests Money's constructor, I could put it in a MoneyTest class
     * But since Money is extremely simple, I'll test it here in conjunction with a Wallet.
     *
     * @throws Exception
     */
    public function testCannotWithdrawNegativeAmount()
    {
        $this->expectException(InvalidArgumentException::class);

        $this->wallet->withdraw(new Money(-100, "GBP"));
    }

    /**
     * This test in essence tests Money's constructor, I could put it in a MoneyTest class
     * But since Money is extremely simple, I'll test it here in conjunction with a Wallet.
     *
     * @throws Exception
     */
    public function testCannotDepositNegativeAmount()
    {
        $this->expectException(InvalidArgumentException::class);

        $this->wallet->deposit(new Money(-100, "GBP"));
    }

    /** @throws Exception */
    public function testCannotWithdrawWithDifferentCurrency()
    {
        $this->expectException(InvalidArgumentException::class);

        $this->wallet->deposit(new Money(100, "USD"));
    }

    /** @throws Exception */
    public function testCannotDepositWithDifferentCurrency()
    {
        $this->expectException(InvalidArgumentException::class);

        $this->wallet->deposit(new Money(100, "USD"));
    }
}
