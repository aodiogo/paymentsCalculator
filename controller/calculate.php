<?php
require_once('classes.php');

use model\BlendedPaymentCalculator;
use model\FixedPaymentCalculator;
use model\Investment;
use model\CompoundPaymentCalculator;

if (! isset($_POST["inv_amount"]) || ! isset($_POST["payment_type"]) || 
    ! isset($_POST["interest"]) || ! isset($_POST["term"])) {
    echo "<script type='text/javascript'>alert('Mandatory parameters missing!');</script>";
    return ;
} else {
    if (!preg_match('/^[0-9]+(?:\.[0-9]{0,2})?$/', $_POST["inv_amount"]) || 
        !is_numeric($_POST["interest"]) || $_POST["interest"]<=0 ||
        $_POST["inv_amount"] <= 0)
    {
        echo "Input is valid";
    } else {
        if(!session_id()) {
            session_start();
        }
        
        
        $investment = new Investment($_POST["inv_amount"], $_POST["payment_type"], $_POST["interest"], $_POST["term"]);
        
        $payCalc;
        
        if($_POST["payment_type"] == "blended") {
            $payCalc = new BlendedPaymentCalculator($investment);
        } else if($_POST["payment_type"] == "fixed") {
            $payCalc = new FixedPaymentCalculator($investment);
        } else if($_POST["payment_type"] == "compounding") {
            $payCalc = new CompoundPaymentCalculator($investment);
        }
        
        $_SESSION['paymentCalculator'] = $payCalc;
        
        echo $payCalc->getCumulativeInterests();
        
        $payments = $payCalc->calculate();
        
        
        echo 'Investment Amount:',$investment->getInv_amount(),'<br/>';
        echo 'Payment Type:',$investment->getPayment_type(),'<br/>';
        echo 'interest:',$investment->getInterest(),'<br/>';
        echo 'Term:',$investment->getTerm(),'<br/>';
        
        echo "<table border='0' style='width:70%;text-align:left'><br />";
        
        echo "<tr>";
        echo "<th>","Period","</th>";
        echo "<th>","interest","</th>";
        echo "<th>","remaining principal","</th>";
        echo "<th>","distribution","</th>";
        echo "<tr>";
        
        for ($row = 0; $row < count($payments); $row ++) {
            echo "<tr>";
            $payment = $payments[$row];
            echo "<td>", $payment->getPeriod(), "</td>";
            echo "<td>", number_format((float)$payment->getInterest(),2,'.',''), "</td>";
            echo "<td>", abs(number_format((float)$payment->getRemainingPrincipal(),2,'.','')), "</td>";
            echo "<td>", number_format((float)$payment->getDistribution(),2,'.',''), "</td>";
            
            echo "</tr>";
        }
        
        echo "</table><br />";
        echo "Cumulative Interest: ", number_format((float)$payCalc->getCumulativeInterests(),2,'.','');
    }
}

echo "<br /><br/><a href=".$_SERVER['HTTP_REFERER'].">Back</a>";
?>
