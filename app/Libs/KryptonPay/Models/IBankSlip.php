<?php namespace App\Libs\KryptonPay\Models;

class IBankSlip
{
    /**
     * @var float valor do boleto ex: 150.00
     */
    public $value;

    /**
     * @var float valor desconto ex: 0
     */
    public $discountValue;

    /**
     * @var string data limit desconto ex: '2020-10-8'
     */
    public $discountLimitDate;

    /**
     * @var array observações
     */
    public $observations = [];

    /**
     * @var string instruções ex: "Pagável em qualquer agência lotérica"
     */
    public $instruction;

    /**
     * @var string multa por atraso ex: 3.0
     */
    public $penaltyRate;

    /**
     * @var string data limite de multa ex: '2020-08-10'
     */
    public $interestRate;

    /**
     * @var string data de vencimento ex: '2020-08-10'
     */
    public $dueDate;

}
