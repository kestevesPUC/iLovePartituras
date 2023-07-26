<?php namespace App\Service\Person;

class PersonService
{
    /**
     * Cria um novo formato dos dados
     * para enviar para a pessoa
     * @return array
     */
    public function transformPerson (array $data) : array
    {
        $array = [];
        foreach($data['data'] as $a){
            $array[$a->name] = $a->value;
        }

        return $array;
    }

    /**
     * Pesquisa os dados da empresa no ReceitaWS
     * @param $cnpj
     * @return mixed|\stdClass
     */
    public static function getCNPJDataWithWebService($cnpj)
    {
        try {
            $cnpj = preg_replace('/\D/', '', $cnpj);
            $ch = curl_init();
            //curl_setopt($ch, CURLOPT_URL, 'https://ce8cozlvn4.execute-api.us-east-1.amazonaws.com/default/getCNPJ?cnpj=' . $cnpj);
            curl_setopt($ch, CURLOPT_URL, 'https://www.receitaws.com.br/v1/cnpj/'.$cnpj.'/days/5');
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                "Authorization: Bearer 79c2554d97e642bfe677c3cf99705cd73aaa155938ee8aaf365aff49791c2ab3",
                "Content-Type: application/json"
            ]);
            curl_setopt($ch, CURLOPT_FILETIME, true);
            curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
            $retorno = curl_exec($ch);
            curl_close($ch);
            return json_decode($retorno);
        } catch (Exception $e) {
            $class = new \stdClass();
            $class->status = 'ERROR';
            $class->message = $e->getMessage();
            return $class;
        }
    }
}
