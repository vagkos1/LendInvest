<?php

namespace LendInvest\Service;

use LendInvest\Entity\Investment;
use LendInvest\Repository\InvestmentRepository;
use LendInvest\Repository\InvestorRepositoryInterface;
use LendInvest\Repository\TrancheRepository;
use LendInvest\Repository\TrancheRepositoryInterface;

class Invest
{
    /** @var InvestorRepositoryInterface */
    private $investorRepository;

    /** @var TrancheRepositoryInterface */
    private $trancheRepository;

    /** @var InvestmentRepository */
    private $investmentRepository;

    public function __construct(
        InvestorRepositoryInterface $investorRepository,
        TrancheRepositoryInterface $trancheRepository,
        InvestmentRepository $investmentRepository
    ) {
        $this->investorRepository = $investorRepository;
        $this->trancheRepository = $trancheRepository;
        $this->investmentRepository = $investmentRepository;

        TrancheRepository::init();
    }

    /**
     * Processes the request to invest. It also outputs the results.
     * If it does not throw then we assume the investment was made and we echo the new Balance of that wallet.
     *
     * In a real app there would be further separation of concerns here and investing would not happen in the same
     * class as output.
     *
     * @throws \Exception
     */
    public function invest(InvestRequest $investRequest)
    {
        $investor = $this->investorRepository->findById($investRequest->getInvestorId());

        $tranche = $this->trancheRepository->findByName($investRequest->getTrancheName());

        try {
            $investment = new Investment(
                1,
                $investor,
                $tranche,
                $investRequest->getAmount(),
                $investRequest->getDate()
            );
        } catch(\Exception $e) {
            echo "exception \n";
            return;
        }

        $investor->invest($investment);

        // in a real world app the investment would be persisted to the persistence layer from a call that would happen
        // inside \LendInvest\Entity\Investor::invest.
        // Now we don't have a persistence layer so we'll keep the $investment in memory.
        $this->investmentRepository->addInvestment($investment);

        echo "ok \n";
    }
}
