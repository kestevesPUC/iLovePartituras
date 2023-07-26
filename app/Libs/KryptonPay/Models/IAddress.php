<?php namespace App\Libs\KryptonPay\Models;

class IAddress
{
    /**
     * @var string rua ex: Av das Bandeiras
     */
    public $street;

    /**
     * @var string numero ex: 370
     */
    public $number;

    /**
     * @var string bairro : Jardim Laguna
     */
    public $district;

    /**
     * @var string cep ex: 32140300
     */
    public $zipCode;

    /**
     * @var string complemento do endereço ex: Casa de esquina
     */
    public $complement;

    /**
     * @var string estado ex: MG
     */
    public $stateInitials;

    /**
     * @var string cidade ex: Belo Horizonte
     */
    public $cityName;

    /**
     * @var string pais ex: Brasil
     */
    public $countryName;
}
