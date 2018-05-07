<?php
namespace model;

class Investment
{
    private $inv_amount;
    private $payment_type;
    private $interest;
    private $term;
    
    public function __construct($inv_amount, $payment_type, $interest, $term)
    {
        $this->inv_amount = $inv_amount;
        $this->payment_type = $payment_type;
        $this->interest = $interest;
        $this->term = $term;
    }
    
    /**
     * @return mixed
     */
    public function getInv_amount()
    {
        return $this->inv_amount;
    }
    
    /**
     * @return mixed
     */
    public function getPayment_type()
    {
        return $this->payment_type;
    }
    
    /**
     * @return mixed
     */
    public function getInterest()
    {
        return $this->interest/100;
    }
    
    /**
     * @return mixed
     */
    public function getTerm()
    {
        return $this->term;
    }
    
    /**
     * @return mixed
     */
    public function getQuarterlyPeriods()
    {
        return $this->term*4;
    }
}

class Payment
{
    private $period;
    private $interest;
    private $remainingPrincipal;
    private $distribution;
    
    public function __construct($period, $interest, $remainingPrincipal, $distribution)
    {
        $this->period = $period;
        $this->interest = $interest;
        $this->remainingPrincipal = $remainingPrincipal;
        $this->distribution = $distribution;
    }
    
    /**
     * @return mixed
     */
    public function getPeriod()
    {
        return $this->period;
    }
    
    /**
     * @return mixed
     */
    public function getInterest()
    {
        return $this->interest;
    }
    
    /**
     * @return mixed
     */
    public function getRemainingPrincipal()
    {
        return $this->remainingPrincipal;
    }
    
    /**
     * @return mixed
     */
    public function getDistribution()
    {
        return $this->distribution;
    }
    
}

abstract class PaymentCalculator
{
    protected $investment;
    protected $payments = array();
    protected $pmtValue;
    protected $cumulativeInterests;

    public abstract function calculate();

    /**
     * @return mixed
     */
    public function getCumulativeInterests()
    {
        return $this->cumulativeInterests;
    }
}

class BlendedPaymentCalculator extends PaymentCalculator
{	

    public function __construct(Investment $investment)
    {
        $this->investment = $investment;
        $this->pmtValue = $this->PMT($investment->getInterest()/4, $investment->getQuarterlyPeriods(), $investment->getInv_amount());
    }

    public function calculate()
    {
        if(!isset($this->investment)) {
            return NULL;
        }
        $remainingPrincipal = $this->investment->getInv_amount();
        $quarterlyRate = $this->investment->getInterest()/4;
        $cumulativeInterest = 0;
        for ($i = 1; $i <= $this->investment->getQuarterlyPeriods(); $i++) {
            $interest = $quarterlyRate*$remainingPrincipal;
            $remainingPrincipal += $interest - $this->pmtValue;
            $distribution = $this->pmtValue;
            $cumulativeInterest += $interest;
            array_push($this->payments, new Payment($i, $interest, $remainingPrincipal, $distribution));
        }
        $this->cumulativeInterests = $cumulativeInterest;
        return $this->payments;
    }


    private function PMT($interest,$num_of_payments,$PV,$FV = 0.00, $Type = 0){
	$xp=pow((1+$interest),$num_of_payments);
	return
		($PV* $interest*$xp/($xp-1)+$interest/($xp-1)*$FV)*
		($Type==0 ? 1 : 1/($interest+1));
    }
}

class FixedPaymentCalculator extends PaymentCalculator
{
    
    public function __construct(Investment $investment)
    {
        $this->investment = $investment;
        $this->pmtValue = 0;
    }
    
    public function calculate()
    {
        if(!isset($this->investment)) {
            return NULL;
        }
        $remainingPrincipal = $this->investment->getInv_amount();
        $quarterlyRate = $this->investment->getInterest()/4;
        $cumulativeInterest = 0;
        for ($i = 1; $i <= $this->investment->getQuarterlyPeriods(); $i++) {
            $interest = $quarterlyRate*$remainingPrincipal;
            $remainingPrincipal += $this->pmtValue;
            $distribution = $interest;
            $cumulativeInterest += $interest;
            array_push($this->payments, new Payment($i, $interest, $remainingPrincipal, $distribution));
        }
        $this->cumulativeInterests = $cumulativeInterest;
        return $this->payments;
    }
}

class CompoundPaymentCalculator extends PaymentCalculator
{
    
    public function __construct(Investment $investment)
    {
        $this->investment = $investment;
        $this->pmtValue = 0;
    }
    
    public function calculate()
    {
        if(!isset($this->investment)) {
            return NULL;
        }
        $remainingPrincipal = $this->investment->getInv_amount();
        $quarterlyRate = $this->investment->getInterest()/4;
        $cumulativeInterest = 0;
        for ($i = 1; $i <= $this->investment->getQuarterlyPeriods(); $i++) {
            $interest = $quarterlyRate*$remainingPrincipal;
            $remainingPrincipal += $interest;
            $distribution = $this->pmtValue;
            $cumulativeInterest += $interest;
            array_push($this->payments, new Payment($i, $interest, $remainingPrincipal, $distribution));
        }
        $this->cumulativeInterests = $cumulativeInterest;
        return $this->payments;
    }
}
?>

