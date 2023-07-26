<?php

namespace App\Libs\KryptonPay\Service;

use App\Libs\KryptonPay\Models\IPaymentService;
use Exception;
use KryptonPay\Api\Address as KryptonPayAddress;
use KryptonPay\Api\ApiContext;
use KryptonPay\Api\Calendar;
use KryptonPay\Api\CreditCard as KrayptonPayCreditCard;
use KryptonPay\Api\Item as KryptonPayItem;
use KryptonPay\Api\KryptonPay;
use KryptonPay\Api\Payer as KryptonPayPayer;
use KryptonPay\Api\Pix as KrayptonPayPix;
use KryptonPay\Api\Slip as KrayptonPaySlip;
use KryptonPay\Api\Split as KryptonPaySplit;
use KryptonPay\Api\Transaction as KryptonPayTransaction;
use KryptonPay\Models\Api\Response as KryptonPayResponse;

class Payment
{
    private $apiContext;
    private $transaction;

    const INTERNAL_ERROR = 0;
    const ERROR_REGISTER_SLIP_BANK = 1;
    const ERROR_REGISTER_CREDIT_CARD = 2;

    public function __construct()
    {
        $this->apiContext = new ApiContext();
        $this->apiContext->setIsSandbox(env('APP_ENV') == 'production' ? false : true);
        $this->transaction = new KryptonPayTransaction();
    }

    public function startTransaction(IPaymentService $paymentData)
    {
        $this->apiContext->setApiToken($paymentData->tokenApi);
        $this->transaction->setPaymentType($paymentData->paymentType);
        $this->transaction->setIsQuickSale($paymentData->isQuickSale);
        $this->transaction->setApplication(env('KP_APPLICATION', 346));
        $this->transaction->setReference($paymentData->reference ?? '1');

        $this->transaction->setAssumeTax(false);

        $this->addPayer($paymentData);
        $this->addItem($paymentData);
        $this->addSplit($paymentData);

        if ($paymentData->paymentType == ApiContext::SLIPBANK) {
            $this->startSlipBankPayment($paymentData);
        } elseif ($paymentData->paymentType == ApiContext::CREDIT_CARD) {
            $this->startCreditCartPayment($paymentData);
        } elseif($paymentData->paymentType == ApiContext::PIX){
            $this->startPix($paymentData);
        }

        return $this->createPayment();
    }

    private function addPayer(IPaymentService $paymentData)
    {
        $payerAddress = new KryptonPayAddress();
        $payerAddress->setStreet($paymentData->payer->address->street);
        $payerAddress->setNumber($paymentData->payer->address->number);
        $payerAddress->setDistrict($paymentData->payer->address->district);
        $payerAddress->setZipCode(preg_replace('/[^0-9]/', '', trim($paymentData->payer->address->zipCode)));
        $payerAddress->setComplement($paymentData->payer->address->complement ?? '');
        $payerAddress->setStateInitials($paymentData->payer->address->stateInitials);
        $payerAddress->setCityName($paymentData->payer->address->cityName);
        $payerAddress->setCountryName($paymentData->payer->address->countryName);

        $payer = new KryptonPayPayer();
        $payer->setType(ApiContext::PERSON_LEGAL);
        if (\strlen($paymentData->payer->identity) == 11) {
            $payer->setType(ApiContext::PERSON_NATURAL);
            $payer->setBirthDate($paymentData->payer->birthDate ?? null);
        }
        $payer->setName($paymentData->payer->name);
        $payer->setFantasyName($paymentData->payer->name);
        $payer->setIdentity($paymentData->payer->identity);
        $payer->setEmail($paymentData->payer->email);
        $payer->setPhone(preg_replace('/[^0-9]/', '', trim($paymentData->payer->phone)));
        $payer->setAddress($payerAddress);
        $this->transaction->setPayer($payer);
    }

    private function addItem(IPaymentService $paymentData)
    {
        $kryptonPayItem = new KryptonPayItem();
        $kryptonPayItem->setCode($paymentData->itens->code);
        $kryptonPayItem->setDescription($paymentData->itens->description);
        $kryptonPayItem->setUnitPrice($paymentData->itens->unitPrice);
        $kryptonPayItem->setQuantity($paymentData->itens->quantity);
        $this->transaction->addItem($kryptonPayItem);
    }

    private function addSplit(IPaymentService $paymentData)
    {
        if ($paymentData->split->document && $paymentData->split->value) {
            $split = new KryptonPaySplit();
            $split->setDocument($paymentData->split->document);
            if($paymentData->split->value){
                $split->setValue($paymentData->split->value);
            }
            if($paymentData->split->tax){
                $split->setTax($paymentData->split->tax);
            }
            $this->transaction->addSplit($split);
        }
    }

    private function startCreditCartPayment(IPaymentService $paymentData)
    {
        $cardholderAddress = new KryptonPayAddress();
        $cardholderAddress->setStreet($paymentData->creditCard->cardholderAddress->street);
        $cardholderAddress->setNumber($paymentData->creditCard->cardholderAddress->number);
        $cardholderAddress->setDistrict($paymentData->creditCard->cardholderAddress->district);
        $cardholderAddress->setZipCode(preg_replace('/[^0-9]/', '', trim($paymentData->creditCard->cardholderAddress->zipCode)));
        $cardholderAddress->setComplement($paymentData->creditCard->cardholderAddress->complement ?? '-');
        $cardholderAddress->setStateInitials($paymentData->creditCard->cardholderAddress->stateInitials);
        $cardholderAddress->setCityName($paymentData->creditCard->cardholderAddress->cityName);
        $cardholderAddress->setCountryName($paymentData->creditCard->cardholderAddress->countryName);

        $creditCard = new KrayptonPayCreditCard();
        $creditCard->setValue($paymentData->creditCard->value);
        $creditCard->setNumberInstallments($paymentData->creditCard->numberInstallments);
        $creditCard->setSaleDescription($paymentData->creditCard->saleDescription);
        $creditCard->setCardNumber($paymentData->creditCard->cardNumber);
        $creditCard->setCardholder($paymentData->creditCard->cardholder);
        $creditCard->setSecurityCode($paymentData->creditCard->securityCode);
        $creditCard->setMonthExpiration($paymentData->creditCard->monthExpiration);
        $creditCard->setYearExpiration($paymentData->creditCard->yearExpiration);
        $creditCard->setAddress($cardholderAddress);
        $this->transaction->setCreditCard($creditCard);
    }

    private function startSlipBankPayment(IPaymentService $slipBankData)
    {
        $slipBank = new KrayptonPaySlip();
        $slipBank->setValue($slipBankData->bankSlip->value);
        $slipBank->setInstruction($slipBankData->bankSlip->instruction);
        $slipBank->setDueDate($slipBankData->bankSlip->dueDate);
        if (is_array($slipBankData->bankSlip->observations) && !empty($slipBankData->bankSlip->observations)) {
            foreach ($slipBankData->bankSlip->observations as $observation) {
                $slipBank->addObservation($observation);
            }
        }
        $slipBank->addObservation($slipBankData->bankSlip->value);
        $this->transaction->setSlip($slipBank);
    }

    private function startPix(IPaymentService $pixData)
    {
        $calendar = new Calendar();
        $calendar->setDueDate($pixData->bankSlip->dueDate);
        $pix = new KrayptonPayPix();
        $pix->setValue($pixData->bankSlip->value);
        $pix->setPayerRequest($pixData->payer->identity);
        $pix->setCalendar($calendar);
        $this->transaction->setPix($pix);
    }


    protected function createPayment()
    {
        $this->apiContext->setTransaction($this->transaction);
        return $this->handlePaymentReturn(KryptonPay::createPayment($this->apiContext));
    }

    private function handlePaymentReturn($paymentReturn)
    {
        if (isset($paymentReturn->code) && ($paymentReturn->code == 400 || $paymentReturn->code == 500)) {
            if (isset($paymentReturn->errorCode) && $paymentReturn->errorCode == self::ERROR_REGISTER_CREDIT_CARD) {
                throw new Exception('A operadora não autorizou seu pagamento, verifique se suas informações estão corretas, caso esteja pode ser necessário ligar na operadora para fazer a liberação deste pagamento.', 500);
            }
            throw new Exception('Falha ao processar seu pagamento, verifique suas informações e tente novamente.', 500);
        }

        if ($paymentReturn instanceof KryptonPayResponse) {
            $code = null;
            switch ($this->transaction->getPaymentType()) {
                case ApiContext::SLIPBANK:
                    $code = self::ERROR_REGISTER_SLIP_BANK;
                    break;
                case ApiContext::CREDIT_CARD:
                    $code = self::ERROR_REGISTER_CREDIT_CARD;
                    break;
            }

            dd($paymentReturn);
            throw new Exception(json_encode($paymentReturn->messages), $code);
        }

        return $paymentReturn;
    }

}
