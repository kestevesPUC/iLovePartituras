<?php namespace App\Repositories\User;

use App\Helpers\Util;
use App\Models\User;
use App\Repositories\Person\PersonRepo;
use App\Service\Person\PersonService;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class UserRepo
{
    private $user;
    private $personRepo;

    public function __construct()
    {
        $this->user = new User();
        $this->personRepo = new PersonRepo();
    }


    public function onGet (array $data,string $action) : array|object|null|string
    {
        try{
            $response = $this->{$action}($data);
            return $response;
        }catch(Exception $e){
            dd($e);
            return ['success' => false, 'message' => "Não foi possível realizar a operação. Motivo: " . $e->getMessage()];
        }
    }

    public function onPost (array $data, string $action) : array
    {
        try{
            DB::beginTransaction();
            $response = $this->{$action}($data);
            DB::commit();
            return $response;
        }catch(Exception $e){
            DB::rollBack();
            dd($e);
            return ['success' => false, 'message' => "Não foi possível realizar a operação. Motivo: " . $e->getMessage()];
        }
    }


    private function getUser (array $data) : array|object|null
    {
        $user = $this->user::query()
        ->select('U.*', 'PP.cpf', 'JP.cnpj')
        ->from('access_control.user as U')
        ->leftJoin('contact.physical_person as PP', 'U.id_person', 'PP.id')
        ->leftJoin('contact.juridical_person as JP', 'U.id_person', 'JP.id');

        if(Arr::get($data, 'id_user', 0) != 0){
            return $user->where('U.id', $data['id_user'])->first();
        }

        return $user->where('PP.cpf', $data['cpf_cnpj'])->orWhere('JP.cnpj', $data['cpf_cnpj'])->first();

    }

    private function getProfile (array $data) : object
    {
        $userId = $data['user_id'];

        if(Session::get('usuario')->id_group != 1 && Arr::get($data, 'getPerson', 0) == 0){
            $userId = Auth::id();
        }

        $person = $this->user::query()
        ->select(
            'P.id',
            'P.name',
            'P.email',
            'P.observation',
            'P.pis',
            'P.job',
            'P.company',
            'P.telephone_contact',
            'PP.cpf',
            'PP.birth_date',
            'PP.cei',
            'PP.rg',
            'PP.issuing_agency',
            'PP.date_issui',
            'JP.cnpj',
            'JP.state_registration',
            'JP.municipal_registration',
            'JP.fantasy_name',
            'JP.tax_substitution',
            'JP.website',
            'JP.name_contact',
            'JP.telephone_contact as company_contact',
            'JP.email_contact',
            'A.street',
            'A.number',
            'A.neighborhood',
            'A.cep',
            'A.complement',
            'A.uf',
            'C.name as city',
            'C.id as code_city',
            'CC.description as country',
            'U.email_verified_at',
            'U.id as id_user',
            'U.logged'
        )
        ->from('access_control.user as U')
        ->join('contact.person as P', 'U.id_person', 'P.id')
        ->leftJoin('address.address as A', 'P.id', 'A.id_person')
        ->leftJoin('address.city as C', DB::raw('"A".city_code::integer'), 'C.id')
        ->leftJoin('address.country as CC', 'A.country_code', 'CC.id')
        ->leftJoin('contact.physical_person as PP', 'P.id', 'PP.id')
        ->leftJoin('contact.juridical_person as JP', 'P.id', 'JP.id')
        ->where('U.id', $userId)->first();

        $person->birth_date_br = Util::formatDatePatternBR($person->birth_date ?? '');
        $person->img_profile = $this->personRepo->getImageProfile(['id_person' => $person->id]);
        return $person;
    }

    private function saveProfile (array $data) : array
    {
        $array = (new PersonService)->transformPerson($data);
        $this->personRepo->savePerson($array);
        return ['success' => true, 'message' => 'Dados cadastrais atualizados com sucesso!'];
    }

    private function getPerson (array $data) : array|object
    {
        $user = $this->getUser(['cpf_cnpj' => Util::removerMaskDocument($data['cpf_cnpj'])]);

        if(!$user) {
          throw new Exception ('Não foi encontrado nenhum usuario com este CPF / CNPJ, por favor complete os dados manualmente.');
        }

        $person = $this->getProfile(['user_id' => $user->id, 'getPerson' => 1]);

        return ['success' => true, 'message' => 'Dados coletados com sucesso!', 'person' => $person];
    }

    private function getPicture (array $data) : string|null
    {
        $user = $this->getUser($data);

        return base64_decode($this->personRepo->getImageProfile(['id_person' => $user->id_person]));
    }
}
