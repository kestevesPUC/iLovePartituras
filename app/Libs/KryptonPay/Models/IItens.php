<?php namespace App\Libs\KryptonPay\Models;

class IItens
{
    /**
     * @var string codigo produto ex: 105
     */
    public $code;

    /**
     * @var string descrição do produto ex: ECP-A1
     */
    public $description;

    /**
     * @var float valor unitario do produto ex: 130.00
     */
    public $unitPrice;

    /**
     * @var int quantidade ex: 1
     */
    public $quantity;
}
