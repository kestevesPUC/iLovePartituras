<?php

namespace App\Libs\KryptonPay\Service;

use KryptonPay\Api\ApiContext;
use KryptonPay\Service\Transaction\GetTransaction as kryptonPay;

class GetTransaction
{
    private $apiContext;
    private $kryptonPay;

    public function __construct(string $token)
    {
        $this->apiContext = new ApiContext();
        $this->apiContext->setApiToken($token);
        $this->apiContext->setIsSandbox(env('APP_ENV') == 'production' ? false : true);
        $this->kryptonPay = new kryptonPay($this->apiContext);
    }

    public function getTransaction(int $reference)
    {
        $this->kryptonPay->setReference($reference);
        return $this->kryptonPay->execute();
    }
}
