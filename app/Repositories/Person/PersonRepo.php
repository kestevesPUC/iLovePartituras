<?php namespace App\Repositories\Person;

use App\Helpers\Constants\SystemConstants;
use App\Helpers\Util;
use App\Models\Address\Address;
use App\Models\Contact\JuridicalPerson;
use App\Models\Contact\Person;
use App\Models\Contact\PhysicalPerson;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PersonRepo
{
    private $person;
    private $physical_person;
    private $juridical_person;
    private $address;

    public function __construct()
    {
        $this->person = new Person();
        $this->physical_person = new PhysicalPerson();
        $this->juridical_person = new JuridicalPerson();
        $this->address = new Address();
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

    public function onGet (array $data, string $action) : array|object
    {
        try{
            $response = $this->{$action}($data);
            return $response;
        }catch(Exception $e){
            dd($e);
            return ['success' => false, 'message' => "Não foi possível realizar a operação. Motivo: " . $e->getMessage()];
        }
    }


    private function getPerson (array $data) : array|object
    {
        return $this->person::query()
        ->select(
            'contact.person.*',
            'A.street',
            'A.number',
            'A.neighborhood',
            'A.cep',
            'A.complement',
            'A.uf',
            'C.name as city_desc',
            'PP.cpf',
            'PP.birth_date',
            'PP.rg',
            'PP.issuing_agency',
            'PP.date_issui',
            'PP.cei',
            'JP.cnpj',
            'JP.state_registration',
            'JP.municipal_registration',
            'JP.fantasy_name',
            'JP.tax_substitution',
            'JP.website',
            'JP.name_contact',
            'JP.telephone_contact as company_contact',
            'JP.email_contact',
        )
        ->join('address.address as A', 'contact.person.id', 'A.id_person')
        ->join('address.city as C', DB::raw('"A".city_code::integer'), 'C.id')
        ->leftJoin('contact.physical_person as PP', 'contact.person.id', 'PP.id')
        ->leftJoin('contact.juridical_person as JP', 'contact.person.id', 'JP.id')
        ->where('contact.person.id', $data['id_person'])->first();
    }

    public function savePerson (array $data) : object
    {
        $type_person = null;

        if(Arr::get($data, 'cpf_cnpj', 0) != 0){
            $type_person = strlen(Util::removerMaskDocument($data['cpf_cnpj'])) == 11 ? SystemConstants::TYPE_PERSON_CPF : SystemConstants::TYPE_PERSON_CNPJ;
            $data['cpf_cnpj'] = Util::removerMaskDocument($data['cpf_cnpj']);
        }

        $person = Arr::get($data, 'id_person', 0) != 0 ? $this->person::find($data['id_person']) ?? new Person : new Person;
        $person->id_type =  $type_person ?? null;
        $person->name = $data['first_name'];
        $person->email = strtolower(Arr::get($data, 'email', $person->email));
        $person->observation = Arr::get($data, 'observation', null);
        $person->active = Arr::get($data, 'active', true);
        $person->pis = Arr::get($data, 'pis', null);
        $person->job = Arr::get($data, 'job', null);
        $person->company = Arr::get($data, 'company', null);
        $person->telephone_contact = Arr::get($data, 'telephone_contact', 0) != 0 ? Util::removerMaskTel($data['telephone_contact']) : null;
        $person->save();

        $data['id_person'] = $person->id;

        if(Arr::get($data, 'cpf_cnpj', 0) != 0){
            $type_person == SystemConstants::TYPE_PERSON_CPF ? $this->savePhysicalPerson($data, $person) : $this->saveJuridicalPerson($data, $person);
        }

        $this->saveAddress($data);
        $this->saveImageProfile($data);

        return $person;
    }

    public function savePhysicalPerson (array $data, object $person) : object
    {
        DB::delete('DELETE FROM contact.juridical_person WHERE id = ?', [$data['id_person']]);

        $personP = $this->physical_person::find($data['id_person']) ?? new PhysicalPerson();
        $personP->id = $data['id_person'];

        if($this->getTypePerson(['cpf_cnpj' => $data['cpf_cnpj'], 'type_person' => 1])){
            $personP->cpf = Arr::get($data, 'cpf_cnpj', 0) != 0 ? Util::removerMaskDocument($data['cpf_cnpj']) : null;
        }

        $personP->birth_date = Arr::get($data, 'birth_date', null) ? Util::formatDatePatternUSA($data['birth_date']) : null;
        $personP->rg = Arr::get($data, 'rg', null);
        $personP->issuing_agency = Arr::get($data, 'issuing_agency', null);
        $personP->date_issui = Arr::get($data, 'date_issui', null);
        $personP->cei = Arr::get($data, 'cei', null);
        $personP->save();

        return $personP;
    }

    public function saveJuridicalPerson (array $data, object $person) : object
    {
        DB::delete('DELETE FROM contact.physical_person WHERE id = ?', [$data['id_person']]);

        $personJ = $this->juridical_person::find($data['id_person']) ?? new JuridicalPerson();
        $personJ->id = $data['id_person'];

        if(!$this->getTypePerson(['cpf_cnpj' => $data['cpf_cnpj'], 'type_person' => 2])){
            $personJ->cnpj = Arr::get($data, 'cpf_cnpj', 0) != 0 ? Util::removerMaskDocument($data['cpf_cnpj']) : null;
        }

        $personJ->municipal_registration = Arr::get($data, 'municipal_registration', null);
        $personJ->fantasy_name = Arr::get($data, 'fantasy_name', null);
        $personJ->tax_substitution = Arr::get($data, 'tax_substitution', false);
        $personJ->website = Arr::get($data, 'website', null);
        $personJ->name_contact = Arr::get($data, 'name_contact', null);
        $personJ->telephone_contact = Arr::get($data, 'telephone_contact', null);
        $personJ->email_contact = Arr::get($data, 'email_contact', $person->email);
        $personJ->save();

        return $personJ;
    }

    public function saveAddress (array $data) : object
    {
        $address = $this->address::where('id_person', $data['id_person'])->first() ?? new Address();
        $address->id_person = $data['id_person'];
        $address->street = Arr::get($data, 'street', null);
        $address->number = Arr::get($data, 'number', null);
        $address->neighborhood = Arr::get($data, 'neighborhood', null);
        $address->cep = Arr::get($data, 'cep', null);
        $address->complement = Arr::get($data, 'complement', null);
        $address->city_code = Arr::get($data, 'city_code', null);
        $address->country_code = Arr::get($data, 'country_code', null);
        $address->uf = Arr::get($data, 'uf', null);
        $address->save();

        return $address;
    }

    public function saveImageProfile (array $data)
    {
        if(Arr::get($data, 'profile', 0) != 0){
            Storage::disk('DIR_ORD')->put('img/avatar/profile_'.$data['id_person'].'.png', base64_decode($data['profile']));
        }

        return true;
    }

    public function getImageProfile (array $data) : string|null
    {
        if(Storage::disk('DIR_ORD')->exists('img/avatar/profile_'.$data['id_person'].'.png')){
            return base64_encode(Storage::disk('DIR_ORD')->get('img/avatar/profile_'.$data['id_person'].'.png'));
        }else{
            return base64_encode(Storage::disk('DIR_ORD')->get('img/avatar/profile_default.png'));
        }
    }

    private function getTypePerson (array $data) : object|bool
    {
        if($data['type_person'] == 1){
            return $this->physical_person::query()->where('cpf', $data['cpf_cnpj'])->first() ?? false;
        }else {
            return $this->juridical_person::query()->where('cnpj', $data['cpf_cnpj'])->first() ?? false;
        }
    }
}
