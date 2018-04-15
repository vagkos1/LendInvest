<?php

namespace LendInvest\Service;


use LendInvest\Repository\InvestmentRepositoryInterface;

class CalculateInterest
{
    /** @var InvestmentRepositoryInterface */
    private $investmentRepository;

    public function __construct(InvestmentRepositoryInterface $investmentRepository)
    {
        $this->investmentRepository = $investmentRepository;
    }

    /**
     * Processes the request to calculate interest. It also outputs the results.
     * In a real app there would be further separation of concerns here and calculation would not happen in the same
     * class as output.
     *
     * @throws \Exception
     */
    public function calculate(CalculateInterestRequest $request)
    {
        $earnings = null;

        $investments = $this->investmentRepository->findByPeriod($request->getStartDate(), $request->getEndDate());

        echo "\n" . $request->getEndDate()->format('d/m/Y') . ":\n";
        foreach ($investments as $investment) {
            $investorId = $investment->getInvestor()->getId();
            $earnings = $investment->getEarningsInRunningMonth($request->getEndDate());

            echo "\"Investor" . $investorId . "\" earns " .  $earnings . "\n";
        }
    }
}
