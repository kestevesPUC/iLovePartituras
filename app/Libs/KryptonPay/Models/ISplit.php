<?php namespace App\Libs\KryptonPay\Models;

class ISplit
{
    /**
     * @var string  ex: 1234
     */
    public $document;

    /**
     * @var float ex: 123,00
     */
    public $value;

    /**
     * @var float ex: 123,00
     */
    public $tax;
}
