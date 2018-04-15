#!/bin/php
<?php

require_once '../vendor/autoload.php';

use LendInvest\Utils\Money;
use LendInvest\Service\InvestRequest;
use LendInvest\Service\Invest;
use LendInvest\Service\CalculateInterest;
use LendInvest\Service\CalculateInterestRequest;
use LendInvest\Repository\InvestmentRepository;
use LendInvest\Repository\InvestorRepository;
use LendInvest\Repository\TrancheRepository;
use LendInvest\Repository\LoanRepository;


$investRequest1 = new InvestRequest(
    1,
    1,
    'A',
    new Money(1000, 'GBP'),
    new \DateTime('2015-10-03')
);

$investRequest2 = new InvestRequest(
    2,
    2,
    'A',
    new Money(1, 'GBP'),
    new \DateTime('2015-10-04')
);

$investRequest3 = new InvestRequest(
    3,
    3,
    'B',
    new Money(500, 'GBP'),
    new \DateTime('2015-10-10')
);

$investRequest4 = new InvestRequest(
    4,
    4,
    'B',
    new Money(1100, 'GBP'),
    new \DateTime('2015-10-25')
);

$investmentRepository = new InvestmentRepository();

$invest = new Invest(
    new InvestorRepository(),
    new TrancheRepository(
        new LoanRepository()
    ),
    $investmentRepository
);

$invest->invest($investRequest1);
$invest->invest($investRequest2);
$invest->invest($investRequest3);
$invest->invest($investRequest4);

$calculateInterestRequest = new CalculateInterestRequest(
    1,
    new \DateTime('2015-10-01'),
    new \DateTime('2015-10-31')
);

(new CalculateInterest($investmentRepository))->calculate($calculateInterestRequest);
