<?php

namespace LendInvest\Entity;

use LendInvest\Entity\Investor as Investor;
use LendInvest\Utils\Money;

/**
 * Each investor has [1 wallet/currency] with some balance on it.
 * They can use this balance to invest on Loans with the same currency.
 *
 * @package LendInvest\Entity
 */
class Wallet
{
    /** @var int $id - Behaves like a primary key to a wallet */
    private $id;

    /** @var Investor - Behaves like a foreign key to an Investor (M-1) */
    private $investor;

    /** @var string */
    private $currency;

    /** @var Money */
    private $balance;

    /**
     * Creates an empty wallet with the passed in $currency.
     * In a real-world application we would not be setting $id, we would use an autoincrement strategy upon DB insertion
     *
     * @throws \Exception
     */
    public function __construct(int $id, Investor $investor, string $currency)
    {
        $this->id = $id;
        $this->investor = $investor;
        $this->currency = $currency;
        $this->balance = new Money(0, $currency);
    }

    public function getId() : int
    {
        return $this->getId();
    }

    public function getInvestor() : Investor
    {
        return $this->investor;
    }

    public function getCurrency() : string
    {
        return $this->currency;
    }

    public function getBalance() : Money
    {
        return $this->balance;
    }

    /** @throws \Exception */
    public function deposit(Money $money)
    {
        if (Money::isSameCurrency($this->balance, $money)) {
            $this->updateBalanceAfterDeposit($money);
        } else {
            throw new \InvalidArgumentException("Unable to deposit money");
        }
    }

    /** @throws \Exception */
    public function withdraw(Money $money)
    {
        if (Money::isSameCurrency($this->balance, $money)) {
            $this->updateBalanceAfterWithdraw($money);
        } else {
            throw new \InvalidArgumentException("Unable to withdraw money, currency mismatch");
        }
    }

    /** @throws \Exception */
    private function updateBalanceAfterDeposit(Money $money)
    {
        $this->balance = new Money($this->balance->getAmount() + $money->getAmount(), $this->currency);
    }

    /** @throws \Exception */
    private function updateBalanceAfterWithdraw(Money $money)
    {
        $this->balance = new Money($this->getAmountAfterWithdraw($money), $this->currency);
    }

    /**
     * Cannot withdraw more money than what is in the wallet!
     */
    private function getAmountAfterWithdraw(Money $money)
    {
        $newAmount =
            $this->balance->getAmount() - $money->getAmount() > 0 ?
            $this->balance->getAmount() - $money->getAmount() :
            0
        ;

        return $newAmount;
    }
}
