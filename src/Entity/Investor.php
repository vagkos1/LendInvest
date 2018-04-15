<?php

namespace LendInvest\Entity;

use LendInvest\Entity\Wallet as Wallet;
use LendInvest\Entity\Investment as Investment;

class Investor
{
    /** @var int $id - PK to Investor*/
    private $id;

    /**
     * An investor can have one wallet per allowed currency.
     * In this instance we'll just use one GBP wallet.
     *
     * @var Wallet[] $wallets - inverse side M-1 to Wallet
     */
    private $wallets;

    /** @var Investment[] $investments - inverse side M-1 to Investment */
    private $investments;

    /** @var string - optional in this pseudo app */
    private $firstName;

    /** @var string - optional in this pseudo app */
    private $lastName;

    /** @var string - optional in this pseudo app */
    private $address;

    /** @var string - optional in this pseudo app */
    private $email;

    public function __construct(int $id)
    {
        $this->id = $id;
        $this->investments = [];
        $this->wallets = [];
    }

    /** @param Investment[] $investments */
    public function setInvestments(array $investments) : void
    {
        foreach ($investments as $investment) {
            $this->addInvestment($investment);
        }
    }

    private function addInvestment(Investment $investment) {
        $this->investments[] = $investment;
    }

    /** @return Investment[] */
    public function getInvestments(): array
    {
        return $this->investments;
    }

    /** @param Wallet[] $wallets */
    public function setWallets(array $wallets) : void
    {
        foreach ($wallets as $wallet) {
            $this->addWallet($wallet);
        }
    }

    public function addWallet(Wallet $wallet)
    {
        $this->wallets[] = $wallet;
    }

    /** @return Wallet[] */
    public function getWallets() : array
    {
        return $this->wallets;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function setAddress(string $address): void
    {
        $this->address = $address;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getId() : int
    {
        return $this->id;
    }

    /** @throws \Exception */
    public function invest(Investment $investment)
    {
        $this->addInvestment($investment);

        // let the Tranche know that an investment has taken place
        $investment->getTranche()->addInvestment($investment);

        // assuming the investor has one wallet / currency
        $wallet = $this->getWalletByCurrency($investment->getAmount()->getCurrency());
        $wallet->withdraw($investment->getAmount());
    }

    /** @throws \Exception */
    public function getWalletByCurrency(string $currency) : Wallet
    {
        foreach ($this->wallets as $wallet) {
            if ($wallet->getCurrency() === $currency) {
                return $wallet;
            }
        }

        throw new \Exception("No wallet found for this currency");
    }
}
