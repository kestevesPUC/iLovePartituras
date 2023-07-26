<?php namespace App\Libs\NFse;

use Exception;
use NFse\Config\Boot;
use NFse\Helpers\Utils;
use NFse\Models\Settings;

class Configuration
{
    public $settings;

    public function __construct()
    {
        try {
            //ambiente
            $settings = new Settings();
            $settings->environment = (env('APP_ENV') == 'local') ? 'homologacao' : 'producao';

            //Emitente
            $settings->issuer->name = 'KRYPTON TECH DESENVOLVIMENTO DE SOFTWARE LTDA';
            $settings->issuer->cnpj = 40569411000117;
            $settings->issuer->imun = 12784350012;
            $settings->issuer->codMun = 3106200;

            //certificado digital
            $settings->certificate->folder = __DIR__ . '/../../../storage/certificates/'. $settings->issuer->cnpj .'/';
            $settings->certificate->certFile = 'certificate.pfx';
            $settings->certificate->mixedKey = 'mixedKey.pem';
            $settings->certificate->privateKey = 'privateKey.pem';
            $settings->certificate->publicKey = 'publicKey.pem';
            $settings->certificate->password = 'Ktech2021';
            $settings->certificate->noValidate = true;

            //dev
            if ($settings->environment == 'homologacao') {
                Utils::xdebugMode();
            }

            //efetua o boot no lib
            $system = new Boot($settings);
            $system->init();

            $this->settings = $settings;
        } catch (Exception $e) {
            throw $e;
        }
    }
}
