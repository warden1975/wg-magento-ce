<?php
/**
 */

class Citrus_Export_Model_Services_Extractors_Payments implements Citrus_Interfaces_Builder
{

    /**
     * @var array
     */
    protected $payments;

    /**
     * @var Mage_Sales_Model_Order_Address
     */
    protected $billing;

    public function __construct($payments, $billing){
        $this->payments = $payments;
        $this->billing = $billing;
    }

    public function build()
    {

        $total_payment = 0;

        foreach($this->payments as $payment) {

            if ($payment->getAmountPaid() || $payment->getBaseAmountPaid()) {

                if ($this->billing->getCountryId() == "AU")
                    $amountPaid = $payment->getAmountPaid();
                else $amountPaid = $payment->getBaseAmountPaid();

                $new_payment = array(
                    'origin' => 'CreditCard', // Could also be 'DirectDebit' but nothing else
                    'amount' => round($amountPaid, 2),
                    'message' => $payment->getMethod(),
                );
                $total_payment += $new_payment['amount'];
                $final_payments[] = $new_payment;
            }
        }

        $payments['payments'] = $final_payments;
        $payments['total_payment']= $total_payment;
        return $payments;
    }

}