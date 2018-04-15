<?php

require_once "../vendor/autoload.php";

use LendInvest\Entity\Loan;
use LendInvest\Entity\Tranche;

/**
 * Class WalletTest
 *
 * I will not mock Money, it's an extremely simple class.
 */
class LoanTest extends \PHPUnit\Framework\TestCase
{
    /** @var Tranche | \PHPUnit\Framework\MockObject\MockObject */
    private $mockTranche1;

    /** @var Tranche | \PHPUnit\Framework\MockObject\MockObject */
    private $mockTranche2;

    /** @var Loan */
    private $loan;

    /** @throws Exception */
    public function setup()
    {
        $this->mockTranche1 = $this->getMockBuilder(Tranche::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->mockTranche2 = $this->getMockBuilder(Tranche::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->loan = new Loan(1, new \DateTime('2015-10-01'), new \DateTime('2015-11-15'), "GBP");
        $this->loan->setTranches([$this->mockTranche1, $this->mockTranche2]);
    }

    public function testHasTranches()
    {
        $this->assertEquals(true, $this->loan->hasTranches());
    }
}
