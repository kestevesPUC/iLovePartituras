<?php namespace App\Libs\KryptonPay\Models;

use App\Libs\KryptonPay\Models\IAddress;

class ICreditCard
{
    /**
     * @var float valor do titulo ex: 200.00
     */
    public $value;

    /**
     * @var int numero de parcelas ex : 1
     */
    public $numberInstallments;

    /**
     * @var string data de vencimento ex: 1994-05-03
     */
    public $expirationDate;

    /**
     * @var string descição da venda ex: LOJA*TESTE*COMPRA-123
     */
    public $saleDescription;

    /**
     * @var string numero do cartão ex : 4012001037141112
     */
    public $cardNumber;

    /**
     * @var string primeiro nome ex : João
     */
    public $firstName;

    /**
     * @var string ultimo nome ex : João da silva
     */
    public $lastName;

    /**
     * @var string titular do cartão ex : João da silva
     */
    public $cardholder;

    /**
     * @var string código de segurança ex : 998
     */
    public $securityCode;

    /**
     * @var string mês de vencimento : 01
     */
    public $monthExpiration;

    /**
     * @var string ano de vencimento : 22
     */
    public $yearExpiration;

    /**
     * @var Payment\Models\IAddress
     */
    public $cardholderAddress;

    public function __construct()
    {
        $this->cardholderAddress = new IAddress();
    }

}
