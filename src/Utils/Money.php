<?php

namespace LendInvest\Utils;

class Money
{
    /** @var int $amount */
    private $amount;
    
    /** @var string $currency */
    private $currency;

    const ALLOWED_CURRENCIES = [
        "GBP",
        "USD",
        "EUR"
    ];

    public function __construct(int $amount, string $currency)
    {
        if ($amount < 0) {
            throw new \InvalidArgumentException("The amount must be a positive integer");
        }

        if (!in_array($currency, self::ALLOWED_CURRENCIES)) {
            throw new \InvalidArgumentException("The currency must be one of GBP, USD, EUR");
        }

        $this->amount = $amount;
        $this->currency = $currency;
    }

    public function getAmount() : int
    {
        return $this->amount;
    }

    public function getCurrency() : string
    {
        return $this->currency;
    }

    public static function isSameCurrency(Money $money, Money $money2) : bool
    {
        if ($money->getCurrency() === $money2->getCurrency()) {
            return true;
        }

        return false;
    }

    public function __toString() : string
    {
        return $this->amount . " " . $this->currency;
    }
}
