<?php
use model\BlendedPaymentCalculator;
use model\Investment;

require_once 'model/Calculator.php';

/**
 * BlendedPaymentCalculator test case.
 */
class BlendedPaymentCalculatorTest extends PHPUnit_Framework_TestCase
{

    /**
     *
     * @var BlendedPaymentCalculator
     */
    private $blendedPaymentCalculator;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
        parent::setUp();
        
        $investment = new Investment(5000, "blended", 5.0, 5);
        
        // TODO Test other types - fixed and compounding
        
        $this->blendedPaymentCalculator = new BlendedPaymentCalculator($investment);
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown()
    {
        // TODO Auto-generated BlendedPaymentCalculatorTest::tearDown()
        $this->blendedPaymentCalculator = null;
        
        parent::tearDown();
    }

    /**
     * Constructs the test case.
     */
    public function __construct()
    {
        // TODO Auto-generated constructor
    }

    /**
     * Tests BlendedPaymentCalculator->__construct()
     */
    public function test__construct()
    {
        // TODO Auto-generated BlendedPaymentCalculatorTest->test__construct()
        $this->markTestIncomplete("__construct test not implemented");
        
        $this->blendedPaymentCalculator->__construct(/* parameters */);
    }

    /**
     * Tests BlendedPaymentCalculator->calculate()
     */
    public function testCalculate()
    {
        // TODO Missing fixed and compounding tests.
        $this->markTestIncomplete("calculate test not implemented");
        
        $payments = $this->blendedPaymentCalculator->calculate();
        $payment = $payments[19];
        $this->assertEquals(
            20,
            $payment->getPeriod()
            );
        $this->assertEquals(
            3.51,
            number_format((float)$payment->getInterest(),2,'.','')
            );
        $this->assertEquals(
            0,
            abs(number_format((float)$payment->getRemainingPrincipal(),2,'.',''))
            );
        $this->assertEquals(
            284.1,
            abs(number_format((float)$payment->getDistribution(),2,'.',''))
            );
        
        $payment = $payments[0];
        $this->assertEquals(
            1,
            $payment->getPeriod()
            );
        $this->assertEquals(
            62.5,
            number_format((float)$payment->getInterest(),2,'.','')
            );
        $this->assertEquals(
            4778.4,
            abs(number_format((float)$payment->getRemainingPrincipal(),2,'.',''))
            );
        $this->assertEquals(
            284.1,
            abs(number_format((float)$payment->getDistribution(),2,'.',''))
            );
        
        $payment = $payments[10];
        $this->assertEquals(
            11,
            $payment->getPeriod()
            );
        $this->assertEquals(
            33.19,
            number_format((float)$payment->getInterest(),2,'.','')
            );
        $this->assertEquals(
            2404.17,
            abs(number_format((float)$payment->getRemainingPrincipal(),2,'.',''))
            );
        $this->assertEquals(
            284.1,
            abs(number_format((float)$payment->getDistribution(),2,'.',''))
            );
    }
}

