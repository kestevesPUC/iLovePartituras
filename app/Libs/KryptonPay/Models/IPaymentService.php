<?php namespace App\Libs\KryptonPay\Models;

use App\Libs\KryptonPay\Models\IPayer;
use App\Libs\KryptonPay\Models\ICreditCard;
use App\Libs\KryptonPay\Models\IBankSlip;
use App\Libs\KryptonPay\Models\ISplit;

class IPaymentService
{
    /**
     * @var string Token KryptonPay ex: eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwczovL2hvbW9sb2dhY2FvLmFwaS5rcnlwdG9ucGF5LmNvbS5ici91c2Vycy8yL3Rva2VuIiwiaWF0IjoxNTg2OTU5NDY4LCJuYmYiOjE1ODY5NTk0NjgsImp0aSI6IkZ0OEUzVFdQZktPM0xyQmIiLCJzdWIiOjIsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjciLCJsY2wiOiJwdC1iciIsInRrbiI6dHJ1ZSwiZGF0ZXRpbWUiOiIyMDIwLTA0LTE1VDE0OjA0OjI4KzAwMDAifQ.XR2LwE9q-vgikPaUtq7cd4EM8E57PTnpeKYDzWSr-BY
     */
    public $tokenApi;

    /**
     * @var string numero no pedido ex: 712269
     */
    public $reference;

    /**
     * @var int tipo de pagamento ex: 1 - boleto | 2 - cartão de crédito
     */
    public $paymentType;

    /**
     * @var bool venda rapida ex: true | false
     */
    public $isQuickSale;

    /**
     * @var App\Libs\KryptonPay\Models\IPayer
     */
    public $payer;

    /**
     * @var App\Libs\KryptonPay\Models\ICreditCard
     */
    public $creditCard;

    /**
     * @var App\Libs\KryptonPay\Models\IBankSlip
     */
    public $bankSlip;

    /**
     * @var App\Libs\KryptonPay\Models\ISplit
     */
    public $split;

    /**
     * @var App\Libs\KryptonPay\Models\IItem
     */
    public $itens;

    public function __construct()
    {
        $this->payer = new IPayer();
        $this->creditCard = new ICreditCard();
        $this->split = new ISplit();
        $this->bankSlip = new IBankSlip();
    }

}
