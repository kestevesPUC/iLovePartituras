<?php namespace App\Libs\KryptonPay\Models;

use App\Libs\KryptonPay\Models\IAddress;

class IPayer
{
    /**
     * @var string nome pagamdor ex: Wander Alves da Silva
     */
    public $name;

    /**
     * @var string numero do documento CPF ou CNPJ ex: 12149025612
     */
    public $identity;

    /**
     * @var string data de aniversario ex: 1994-05-03
     */
    public $birthDate;

    /**
     * @var string email do pagador ex: wsilva@kryptonbpo.com.br
     */
    public $email;

    /**
     * @var string telefone ex: 31993917917
     */
    public $phone;

    /**
     * @var App\Libs\KryptonPay\Models\IAddress endereço
     */
    public $address;

    public function __construct()
    {
        $this->address = new IAddress();
    }
}
