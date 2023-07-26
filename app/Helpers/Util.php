<?php namespace App\Helpers;

use App\Helpers\Constants\ProductConstants;
use App\Helpers\Constants\SystemConstants;
use App\Service\Pss\Protocol;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Util
{
    /**
     * Função que faz validação de documento CPF
     *
     * @param string $cpf numero do documento da pessoa fisica
     */
    public static function validateCpf(string $cpf)
    {
        $cpf = preg_replace('/[^0-9]/', '', $cpf);

        if (!is_numeric($cpf)) {
            $status = false;
        } else {
            $dv_informado = substr($cpf, 9, 2);
            $digito = [];

            for ($i = 0; $i <= 8; ++$i) {
                $digito[$i] = substr($cpf, $i, 1);
            }
            $posicao = 10;
            $soma = 0;

            for ($i = 0; $i <= 8; ++$i) {
                $soma = $soma + $digito[$i] * $posicao;
                $posicao = $posicao - 1;
            }

            $digito[9] = $soma % 11;

            if ($digito[9] < 2) {
                $digito[9] = 0;
            } else {
                $digito[9] = 11 - $digito[9];
            }

            $posicao = 11;
            $soma = 0;

            for ($i = 0; $i <= 9; ++$i) {
                $soma = $soma + $digito[$i] * $posicao;
                $posicao = $posicao - 1;
            }

            $digito[10] = $soma % 11;

            if ($digito[10] < 2) {
                $digito[10] = 0;
            } else {
                $digito[10] = 11 - $digito[10];
            }

            $dv = $digito[9] * 10 + $digito[10];

            if ($dv != $dv_informado) {
                $status = false;
            } else {
                $status = true;
            }
        }

        return $status;
    }

    /**
     * Função que faz validação de documento CNPJ
     *
     * @param string $cnpj numero do documento da pessoa jurídica
     */
    public static function validateCnpj(string $cnpj)
    {
        // Check if a value was entered
        if(empty($cnpj)) {
            return false;
        }

        // Eliminates a possible mask
        $cnpj = preg_replace("/[^0-9]/", "", $cnpj);
        $cnpj = str_pad($cnpj, 14, '0', STR_PAD_LEFT);

        // Check if the number of digits entered is equal to 11
        if (strlen($cnpj) != 14) {
            return false;
        }

        // Checks that none of the invalid strings below have been entered. If so, returns false
        else if ($cnpj == '00000000000000' ||
            $cnpj == '11111111111111' ||
            $cnpj == '22222222222222' ||
            $cnpj == '33333333333333' ||
            $cnpj == '44444444444444' ||
            $cnpj == '55555555555555' ||
            $cnpj == '66666666666666' ||
            $cnpj == '77777777777777' ||
            $cnpj == '88888888888888' ||
            $cnpj == '99999999999999') {
            return false;

            // Calculates the check digits to verify that the Cnpj is valid
        } else {

            $j = 5;
            $k = 6;
            $soma1 = 0;
            $soma2 = 0;

            for ($i = 0; $i < 13; $i++) {

                $j = $j == 1 ? 9 : $j;
                $k = $k == 1 ? 9 : $k;

                $soma2 += ($cnpj[$i] * $k);

                if ($i < 12) {
                    $soma1 += ($cnpj[$i] * $j);
                }

                $k--;
                $j--;

            }

            $digito1 = $soma1 % 11 < 2 ? 0 : 11 - $soma1 % 11;
            $digito2 = $soma2 % 11 < 2 ? 0 : 11 - $soma2 % 11;

            return (($cnpj[12] == $digito1) and ($cnpj[13] == $digito2));

        }
    }


    /**
     * Função que faz validação de documento CPF ou CNPJ
     *
     * @param string $document numero do documento
     */
    public static function verifyCpfOrCnpj(string $document) {
        return strlen($document == 11) ? self::validateCpf($document) : self::validateCnpj($document);
    }

    /**
     * Função que formata valor em moeda brasileira
     *
     * @param int $valor
     */
    public static function FormatRealMoney($valor)
    {
        return 'R$' . number_format($valor, 2, ',', '.');
    }

    /**
     * Função que formata valor em moeda
     *
     * @param int $value
     */
    public static function FormatMoney($value)
    {
        try {
            return number_format($value, 2, ',', '.');
        }catch (Exception $e){
            return "";
        }

    }

    /**
     * Função que formata uma string de moeda brasileira e converte para valor correspondente ao formato do banco
     *
     * @param string $valor
     * @return float
     */
    public static function removeFormatRealMoneyStringToValue(string $valor): float
    {
        $str_remove_mask = substr($valor, 4, strlen($valor));
        return (float) str_replace(',', '.', str_replace('.', '', $str_remove_mask));
    }

    /**
     * Função que adiciona uma máscara de telefone em um numero.
     *
     * @param int $val número do telefone
     */
    public static function addPhoneMask($val)
    {
        try {
            $val = preg_replace('/\D/', '', $val);
            if (empty($val)) {
                return '';
            }

            $mask = '(##) ####-####';
            if (\strlen($val) == 11) {
                $mask = '(##) #####-####';
            } elseif (\strlen($val) == 13) {
                $val  = substr($val,2,strlen($val));
                $mask = '+55 (##) #####-####';
            }

            $maskared = '';
            $k = 0;
            for ($i = 0; $i <= \strlen($mask) - 1; ++$i) {
                if ($mask[$i] == '#') {
                    if (isset($val[$k])) {
                        $maskared .= $val[$k++];
                    }
                } else {
                    if (isset($mask[$i])) {
                        $maskared .= $mask[$i];
                    }
                }
            }
            return $maskared;
        } catch (\Exception $e) {
            return '';
        }
    }

    /**
     * Função que retorna um array com todos os estados do Brasil
     *
     * @return Array
     */
    public static function getBrazilianStates()
    {
        return [
            ['sigla' => 'AC', 'codigo' => '12', 'nomeEstado' => 'Acre'],
            ['sigla' => 'AL', 'codigo' => '27', 'nomeEstado' => 'Alagoas'],
            ['sigla' => 'AP', 'codigo' => '16', 'nomeEstado' => 'Amapá'],
            ['sigla' => 'AM', 'codigo' => '13', 'nomeEstado' => 'Amazonas'],
            ['sigla' => 'BA', 'codigo' => '29', 'nomeEstado' => 'Bahia'],
            ['sigla' => 'CE', 'codigo' => '23', 'nomeEstado' => 'Ceará'],
            ['sigla' => 'DF', 'codigo' => '53', 'nomeEstado' => 'Distrito Federal'],
            ['sigla' => 'ES', 'codigo' => '32', 'nomeEstado' => 'Espírito Santo'],
            ['sigla' => 'GO', 'codigo' => '52', 'nomeEstado' => 'Goiás'],
            ['sigla' => 'MA', 'codigo' => '21', 'nomeEstado' => 'Maranhão'],
            ['sigla' => 'MT', 'codigo' => '51', 'nomeEstado' => 'Mato Grosso'],
            ['sigla' => 'MS', 'codigo' => '50', 'nomeEstado' => 'Mato Grosso do Sul'],
            ['sigla' => 'MG', 'codigo' => '31', 'nomeEstado' => 'Minas Gerais'],
            ['sigla' => 'PA', 'codigo' => '15', 'nomeEstado' => 'Pará'],
            ['sigla' => 'PB', 'codigo' => '25', 'nomeEstado' => 'Paraíba'],
            ['sigla' => 'PR', 'codigo' => '41', 'nomeEstado' => 'Paraná'],
            ['sigla' => 'PE', 'codigo' => '26', 'nomeEstado' => 'Pernambuco'],
            ['sigla' => 'PI', 'codigo' => '22', 'nomeEstado' => 'Piauí'],
            ['sigla' => 'RJ', 'codigo' => '33', 'nomeEstado' => 'Rio de Janeiro'],
            ['sigla' => 'RN', 'codigo' => '24', 'nomeEstado' => 'Rio Grande do Norte'],
            ['sigla' => 'RO', 'codigo' => '11', 'nomeEstado' => 'Rondônia'],
            ['sigla' => 'RS', 'codigo' => '43', 'nomeEstado' => 'Rio Grande do Sul'],
            ['sigla' => 'RR', 'codigo' => '14', 'nomeEstado' => 'Roraima'],
            ['sigla' => 'SC', 'codigo' => '42', 'nomeEstado' => 'Santa Catarina'],
            ['sigla' => 'SP', 'codigo' => '35', 'nomeEstado' => 'São Paulo'],
            ['sigla' => 'SE', 'codigo' => '28', 'nomeEstado' => 'Sergipe'],
            ['sigla' => 'TO', 'codigo' => '17', 'nomeEstado' => 'Tocantins'],
        ];
    }

    /**
     * Função que retorna um array com todos os estados do Brasil pelo IBGE
     *
     * @return Array
     */
    public static function getBrazilianStatesIBGE()
    {
        return [
            'AC' => '12',
            'AL' => '27',
            'AP' => '16',
            'AM' => '13',
            'BA' => '29',
            'CE' => '23',
            'DF' => '53',
            'ES' => '32',
            'GO' => '52',
            'MA' => '21',
            'MT' => '51',
            'MS' => '50',
            'MG' => '31',
            'PA' => '15',
            'PB' => '25',
            'PR' => '41',
            'PE' => '26',
            'PI' => '22',
            'RJ' => '33',
            'RN' => '24',
            'RO' => '11',
            'RS' => '43',
            'RR' => '14',
            'SC' => '42',
            'SP' => '35',
            'SE' => '28',
            'TO' => '17',
        ];
    }

    /**
     * Função que monta uma máscara de acordo com os parâmetro informados
     *
     * @param string $val valor a ser fromatado
     * @param string $mask formato da máscara
     */
    public static function mask($val, string $mask)
    {
        try {

            $maskared = '';
            $k = 0;
            for ($i = 0; $i <= \strlen($mask) - 1; ++$i) {
                if ($mask[$i] == '#') {
                    if (isset($val[$k])) {
                        $maskared .= $val[$k++];
                    }
                } else {
                    if (isset($mask[$i])) {
                        $maskared .= $mask[$i];
                    }
                }
            }

            return $maskared;
        } catch (\Exception $e) {
            return '';
        }
    }

    /**
     * Função que monta uma máscara de acordo com os parâmetro informados
     *
     * @param int $tamanho tramanho da senha
     * @param bool $maiusculas adicionar caracter
     * @param bool $numeros adicionar caracter
     * @param bool $simbolos adicionar caracter
     */
    public static function generatePassword(int $tamanho = 10, bool $maiusculas = true, bool $numeros = true, bool $simbolos = false)
    {
        $lmin = 'abcdefghijklmnopqrstuvwxyz';
        $lmai = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $num = '123456789012345678901234567890';
        $simb = '!@#$%*-';
        $retorno = '';
        $caracteres = '';

        $caracteres .= $lmin;
        if ($maiusculas) {
            $caracteres .= $lmai;
        }
        if ($numeros) {
            $caracteres .= $num;
        }
        if ($simbolos) {
            $caracteres .= $simb;
        }

        $len = \strlen($caracteres);
        for ($n = 1; $n <= $tamanho; ++$n) {
            $rand = mt_rand(1, $len);
            $retorno .= $caracteres[$rand - 1];
        }

        return $retorno;
    }

    /**
     * Função que gera um número qualquer
     *
     * @param Int $tamanho
     */
    public static function generateNumbers($tamanho = 6)
    {
        $num = '123456789012345678901234567890';
        $caracteres = $num;
        $retorno = '';

        $len = \strlen($caracteres);
        for ($n = 1; $n <= $tamanho; ++$n) {
            $rand = mt_rand(1, $len);
            $retorno .= $caracteres[$rand - 1];
        }

        return $retorno;
    }

    /**
     * Função que retorna o prefix da URL
     */
    public static function prefix()
    {
        $prefix = explode('/', Request()->route()->getPrefix());
        if (isset($prefix[0])) {
            foreach ($prefix as $p) {
                if ($p != '') {
                    return $p . '.';
                }
            }
        } else {
            return '';
        }
    }

    /**
     * Função que retorna o prefix da URL
     */
    public static function prefixSemPonto()
    {
        $prefix = explode('/', Request()->route()->getPrefix());
        if (isset($prefix[0])) {
            foreach ($prefix as $p) {
                if ($p != '') {
                    return $p;
                }
            }
        } else {
            return '';
        }
    }

    /**
     * Função que retorna o prefix da URL
     */
    public static function prefixUrl()
    {
        $prefix = explode('/', Request()->route()->getPrefix());
        if (isset($prefix[0])) {
            foreach ($prefix as $p) {
                if ($p != '') {
                    return '/' . $p;
                }
            }
        } else {
            return '';
        }
    }

    /**
     * Função que adiciona máscara em documento do tipo CPF ou CNPJ
     *
     * @param string $document
     */
    public static function setMaskCpfCnpj($document)
    {
        try {
            if (!is_null($document) && !empty($document)) {
                if (\strlen($document) < 12) {
                    return self::mask($document, '###.###.###-##');
                }

                return self::mask($document, '##.###.###/####-##');
            }
            return '';
        } catch (\Exception $e) {
            return '';
        }
    }

    /**
     * Função que adiciona máscara em telefone
     *
     * @param string $documento
     */
    public static function setMaskPhone(string $number): string
    {
        if($number == ''){
            return '';
        }

        $number = trim(self::removerMaskTel($number));

        if (\strlen($number) >= 11) {
            return self::mask($number, '(##) # ####-####');
        }

        return self::mask($number, '(##) ####-####');
    }

    /**
     * Função que remove a máscara em documento do tipo CPF ou CNPJ
     *
     * @param string $documento
     */
    public static function removerMaskDocument(string $documento)
    {
        try {
            return preg_replace('/[^0-9]/', '', trim($documento));
        } catch (\Exception $e) {
            return '';
        }
    }

    public static function removeMaskPrice($value)
    {
        if (is_float($value) || is_numeric($value)) {
            return $value;
        } else if (!is_string($value)) {
            return 0;
        }
        return (float) str_replace(['.', ','], ['', '.'], $value);
    }

    /**
     * Função que adiciona a máscara de cep
     *
     * @param string $zipCode
     */
    public static function setMaskZipCode(string $zipCode)
    {
        $zipCode = trim(self::removeMaskZipCode($zipCode));
            return self::mask($zipCode, '##.###-###');
    }

    /**
     * Função que remove a máscara de cep
     *
     * @param string $zipCode
     */
    public static function removeMaskZipCode(string $zipCode)
    {
        return preg_replace('/[^0-9]/', '', trim($zipCode));
    }

    /**
     * Função que remove a máscara de telefone
     *
     * @param string $number
     */
    public static function removerMaskTel(string $number)
    {
        return preg_replace('/\D/', '', trim($number));
    }

    /**
     * Função que elimina as posições valiza do array
     *
     * @param array $data
     */
    public static function clearArray(array $datas)
    {
        foreach ($datas as $key => $data) {
            if (is_null($data)) {
                unset($datas[$key]);
            }
        }

        return $datas;
    }

    /**
     * Função que converte objecto em array
     *
     * @param array $data
     */
    public static function convertObjectToArray($data)
    {
        if (is_array($data) || is_object($data)) {
            $result = array();
            foreach ($data as $key => $value) {
                $result[$key] = self::convertObjectToArray($value);
            }
            return $result;
        }
        return $data;
    }

    public static function convertPngBas64ToJpegBase64($pngBase64)
    {

        $image = imagecreatefromstring(base64_decode(str_replace('data:image/png;base64,', '', $pngBase64)));
        $bg = imagecreatetruecolor(imagesx($image), imagesy($image));
        imagefill($bg, 0, 0, imagecolorallocate($bg, 255, 255, 255));
        imagealphablending($bg, true);
        imagecopy($bg, $image, 0, 0, 0, 0, imagesx($image), imagesy($image));
        imagedestroy($image);

        $temp = tempnam(sys_get_temp_dir(), 'TMP_');
        $path = $temp . Auth::id() . '.jpg';
        imagejpeg($bg, $path);

        $file = (file_get_contents($path));
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $imgBase64 = 'data:image/' . $type . ';base64,' . base64_encode($file);

        imagedestroy($bg);

        return $imgBase64;
    }

    public static function copyAndResizePartOfAnImage($imgBase64)
    {
        try {

            try {
                $original_img = imagecreatefromstring(base64_decode(str_replace('data:image/jpeg;base64,', '', $imgBase64)));
            } catch (Exception $e) {
                $original_img = imagecreatefromstring(base64_decode(str_replace('data:image/jpg;base64,', '', $imgBase64)));
            }

            if (imagesx($original_img) == 250 && imagesy($original_img) == 100) {
                $original_w = imagesx($original_img);
                $original_h = imagesy($original_img);

                $thumb_w = 500;
                $thumb_h = 200;
                $thumb_img = imagecreatetruecolor($thumb_w, $thumb_h);
                imagecopyresampled($thumb_img, $original_img, 0, 0, 0, 0, $thumb_w, $thumb_h, $original_w, $original_h);

                $temp = tempnam(sys_get_temp_dir(), 'TMP_');
                $path = $temp . Auth::id() . '.jpg';
                imagejpeg($thumb_img, $path);

                $file = (file_get_contents($path));
                $type = pathinfo($path, PATHINFO_EXTENSION);
                $imgBase64 = 'data:image/' . $type . ';base64,' . base64_encode($file);

                imagedestroy($thumb_img);
                imagedestroy($original_img);

                return $imgBase64;
            } else {
                return $imgBase64;
            }
        } catch (Exception $e) {
            return $imgBase64;
        }

    }

    public static function formatDatePatternUSA($date)
    {
        if (!$date)
            return null;
        return Carbon::createFromFormat('d/m/Y', $date)->format('Y-m-d');
    }

    public static function formatDateTimePatternUSA(string $data)
    {
        try {
            if (strlen($data) > 19) {
                // Fix trailing data
                return Carbon::createFromFormat('d/m/Y H:i:s', substr($data, 0, 19))->format('Y-m-d H:i:s');
            }
            return Carbon::createFromFormat('d/m/Y H:i:s', $data)->format('Y-m-d H:i:s');
        } catch (Exception $e){
            return null;
        }
    }

    public static function formatDatePatternBR($date)
    {
        try {
            if (!is_string($date))
                return '';
            return Carbon::createFromFormat('Y-m-d', $date)->format('d/m/Y');
        } catch (Exception $e) {
            return '';
        }
    }

    public static function formatDateTimePatternBR($data)
    {
        try {
            if (strlen($data) > 19) {
                // Fix trailing data
                return Carbon::createFromFormat('Y-m-d H:i:s', substr($data, 0, 19))->format('d/m/Y H:i:s');
            }
            return Carbon::createFromFormat('Y-m-d H:i:s', $data)->format('d/m/Y H:i:s');
        } catch (\Exception $e) {
            return '';
        }
    }

    public static function formatDate($date): ?string
    {
        try {
            return Carbon::create($date)->format('Y-m-d H:i:s');
        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * Seta o mês de acordo com o numero passado
     * Ex: '1' para 'Janeiro', '2' para 'Fevereiro'.
     *
     * @param string month
     * @param mixed $month
     */
    public static function Month($Month)
    {
        $month = [];
        $month[1] = 'Janeiro';
        $month[2] = 'Fevereiro';
        $month[3] = 'Março';
        $month[4] = 'Abril';
        $month[5] = 'Maio';
        $month[6] = 'Junho';
        $month[7] = 'Julho';
        $month[8] = 'Agosto';
        $month[9] = 'Setembro';
        $month[10] = 'Outubro';
        $month[11] = 'Novembro';
        $month[12] = 'Dezembro';

        return $month[$Month];
    }

    /**
     * Retorna um array dos meses com a chave do array correspondendo ao número do mês.
     *
     * @return array
     *
     */
    public static function getMonths():array {
        return [
            1 => 'Janeiro',
            2 => 'Fevereiro',
            3 => 'Março',
            4 => 'Abril',
            5 => 'Maio',
            6 => 'Junho',
            7 => 'Julho',
            8 => 'Agosto',
            9 => 'Setembro',
            10 => 'Outubro',
            11 => 'Novembro',
            12 => 'Dezembro',
        ];
    }

    /**
     *  Transform Value money in extensive
     * @param int $valor
     * @param bool $bolExibirMoeda
     * @param false $bolPalavraFeminina
     * @return string
     */
    public static function moneyExtensive($valor = 0, $bolExibirMoeda = true, $bolPalavraFeminina = false)
    {
//        $valor = self::removerFormatacaoNumero($valor);
        $singular = null;
        $plural = null;
        if ($bolExibirMoeda) {
            $singular = ['centavo', 'real', 'mil', 'milhão', 'bilhão', 'trilhão', 'quatrilhão'];
            $plural = ['centavos', 'reais', 'mil', 'milhões', 'bilhões', 'trilhões', 'quatrilhões'];
        } else {
            $singular = ['', '', 'mil', 'milhão', 'bilhão', 'trilhão', 'quatrilhão'];
            $plural = ['', '', 'mil', 'milhões', 'bilhões', 'trilhões', 'quatrilhões'];
        }
        $c = ['', 'cem', 'duzentos', 'trezentos', 'quatrocentos', 'quinhentos', 'seiscentos', 'setecentos', 'oitocentos', 'novecentos'];
        $d = ['', 'dez', 'vinte', 'trinta', 'quarenta', 'cinquenta', 'sessenta', 'setenta', 'oitenta', 'noventa'];
        $d10 = ['dez', 'onze', 'doze', 'treze', 'quatorze', 'quinze', 'dezesseis', 'dezesete', 'dezoito', 'dezenove'];
        $u = ['', 'um', 'dois', 'três', 'quatro', 'cinco', 'seis', 'sete', 'oito', 'nove'];
        if ($bolPalavraFeminina) {
            if ($valor == 1) {
                $u = ['', 'uma', 'duas', 'três', 'quatro', 'cinco', 'seis', 'sete', 'oito', 'nove'];
            } else {
                $u = ['', 'um', 'duas', 'três', 'quatro', 'cinco', 'seis', 'sete', 'oito', 'nove'];
            }

            $c = ['', 'cem', 'duzentas', 'trezentas', 'quatrocentas', 'quinhentas', 'seiscentas', 'setecentas', 'oitocentas', 'novecentas'];
        }
        $z = 0;
        $valor = number_format($valor, 2, '.', '.');
        $inteiro = explode('.', $valor);
        for ($i = 0; $i < \count($inteiro); ++$i) {
            for ($ii = mb_strlen($inteiro[$i]); $ii < 3; ++$ii) {
                $inteiro[$i] = '0' . $inteiro[$i];
            }
        }

        // $fim identifica onde que deve se dar junção de centenas por "e" ou por "," ;)
        $rt = null;
        $fim = \count($inteiro) - ($inteiro[\count($inteiro) - 1] > 0 ? 1 : 2);
        for ($i = 0; $i < \count($inteiro); ++$i) {
            $valor = $inteiro[$i];
            $rc = (($valor > 100) && ($valor < 200)) ? 'cento' : $c[$valor[0]];
            $rd = ($valor[1] < 2) ? '' : $d[$valor[1]];
            $ru = ($valor > 0) ? (($valor[1] == 1) ? $d10[$valor[2]] : $u[$valor[2]]) : '';
            $r = $rc . (($rc && ($rd || $ru)) ? ' e ' : '') . $rd . (($rd && $ru) ? ' e ' : '') . $ru;
            $t = \count($inteiro) - 1 - $i;
            $r .= $r ? ' ' . ($valor > 1 ? $plural[$t] : $singular[$t]) : '';
            if ($valor == '000') {
                ++$z;
            } elseif ($z > 0) {
                --$z;
            }

            if (($t == 1) && ($z > 0) && ($inteiro[0] > 0)) {
                $r .= (($z > 1) ? ' de ' : '') . $plural[$t];
            }

            if ($r) {
                $rt = $rt . ((($i > 0) && ($i <= $fim) && ($inteiro[0] > 0) && ($z < 1)) ? (($i < $fim) ? ', ' : ' e ') : ' ') . $r;
            }
        }
        $rt = mb_substr($rt, 1);

        return $rt ? trim($rt) : 'zero';
    }

    public static function correctName($nome)
    {
        $separador = "_";

        // Subtitui caracteres especiais.
        $a = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿŔŕ';
        $b = 'aaaaaaaceeeeiiiidnoooooouuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr';
        $nome = utf8_decode($nome);
        $nome = strtr($nome, utf8_decode($a), $b);

        $nome = iconv('UTF-8', 'ASCII//TRANSLIT', utf8_encode($nome));
        $nome = trim($nome);
        $nome = preg_replace('/[^a-zA-Z0-9\/_| -.]/', '', $nome);
        $nome = strtoupper(trim($nome, $separador));
        $nome = preg_replace('/[\/_| -]+/', $separador, $nome);
        $nome = str_replace("'", "", $nome);

        return $nome;
    }

    public static function temporaryUserDBTable()
    {
        DB::statement('CREATE TEMPORARY TABLE IF NOT EXISTS current_app_user(id integer);');
        DB::statement('INSERT INTO current_app_user VALUES (?);', [Auth::id() ?? 1]);
    }

    /**
     * Remove Special Character String.
     *
     * @param string $string
     *
     * @return string $string
     */
    public static function removeSpecialCharacter($string)
    {
        $what = ['ä', 'ã', 'à', 'á', 'â', 'ê', 'ë', 'è', 'é', 'ï', 'ì', 'í', 'ö', 'õ', 'ò', 'ó', 'ô', 'ü', 'ù', 'ú', 'û', 'À', 'Á', 'Ã', 'É', 'Í', 'Ó', 'Ú', 'ñ', 'Ñ', 'ç', 'Ç', ' ', '-', '(', ')', ',', ';', ':', '|', '!', '"', '#', '$', '%', '&', '/', '=', '?', '~', '^', '>', '<', 'ª', 'º'];
        $by = ['a', 'a', 'a', 'a', 'a', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'A', 'A', 'A', 'E', 'I', 'O', 'U', 'n', 'n', 'c', 'C', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', 'e', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' '];

        return str_replace($what, $by, $string);
    }

    public static function dec2hex($number)
    {
        $hexvalues = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'A', 'B', 'C', 'D', 'E', 'F');
        $hexval = '';
        while($number != '0')
        {
            $hexval = $hexvalues[bcmod($number,'16')].$hexval;
            $number = bcdiv($number,'16',0);
        }
        return $hexval;
    }

    public static function removeHttp($url = '')
    {
        if ($url == 'http://' || $url == 'https://'){
            return $url;
        }
        $matches = substr($url, 0, 7);
        if ($matches=='http://') {
            $url = substr($url, 7);
        } else {
            $matches = substr($url, 0, 8);
            if ($matches=='https://') {
                $url = substr($url, 8);
            }
        }
        return rtrim($url, '/');
    }

    public static function discoverCnpjAR($emissionType, $contract)
    {
        $cnpjLink = !app()->environment('production') ? SystemConstants::CNPJ_LINK_CERTIFICACAO : SystemConstants::CNPJ_LOOK;
        return in_array($emissionType, [ProductConstants::EMISSION_TYPE_PRESENTIAL, ProductConstants::EMISSION_TYPE_RENEWAL_ONLINE])
            ? SystemConstants::CNPJ_LINK_CERTIFICACAO
            : (in_array($contract->id, Protocol::TRANSFER_LOOK)
                ? $cnpjLink
                : ($contract->person->personLegal->cnpj == '28131567000116' ? '28131567000205' : $contract->person->personLegal->cnpj));
    }

    public static function getTypeAdministrator () : int
    {
        if (isset(Auth::user()->id_group) && Auth::user()->id_group == SystemConstants::MASTER_ADMIN) {
            return SystemConstants::MASTER_ADMIN;
        }

        return SystemConstants::NOT_ADMIN;
    }
}
