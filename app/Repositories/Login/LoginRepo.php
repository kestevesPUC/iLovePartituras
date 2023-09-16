<?php namespace App\Repositories\Login;

use App\Helpers\EmailHelper;
use App\Models\User;
use App\Repositories\Person\PersonRepo;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class LoginRepo
{
    private $user;

    public function __construct ()
    {
        $this->user = new User();
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

    private function login(array $data) : array
    {
        $infUser = $this->getUserLogin($data);
        if($infUser){

            if(!$infUser->email_verified_at){
                $this->sendEmailVerify(['person' => $infUser, 'id_user' => $infUser->id, 'name' => $infUser->name]);
                throw new Exception ("Usuário não encontrado, verifique o email e senha e tente novamente!.", 400);
            }

            $user = Auth::loginUsingId($infUser->id);
            $this->createSession($user, $infUser);

            return ['success' => true, 'message' => 'Login realizado com sucesso.'];
        }

        throw new Exception ("Usuário não encontrado, verifique o email e senha e tente novamente!.", 400);

    }

    public function getUserLogin (array $data) : object|null
    {
            $user = $this->user::query()
                ->from('access_control.user as U')
                ->select('U.id', 'P.name', 'U.id_group', 'P.email', 'U.email_verified_at')
                ->join('contact.person as P', 'U.id_person', '=', 'P.id')
                ->leftJoin('contact.physical_person as PP', 'P.id', 'PP.id')
                ->leftJoin('contact.juridical_person as JP', 'P.id', 'JP.id')
                ->where('U.email', strtolower($data['email']));

            if(Arr::get($data, 'register', 0) != 0){
                return $user->first();
            }

            return $user->where('U.password', '=', hash('sha512', $data['password']))->first();
    }

    private function createSession (object $user, object $infoUser) : bool
    {

        $user->cpf_cnpj = $infoUser->cpf ?? $infoUser->cnpj ?? null;
        $user->email = strtolower($user->email);
        $user->name = $infoUser->name;

        $login = $this->user::find($infoUser->id);
        $login->logged = true;
        $login->last_longin = Carbon::now()->format('Y-m-d H:i:s');
        $login->save();

        Auth::setUser($user);
        Session::put('usuario', $user);

        return true;
    }

    public function register (array $data) : array
    {
        $data['register'] = 1;
        $infUser = $this->getUserLogin($data);

        if(!$infUser){
            $user = new User();
            $user->email = strtolower($data['email']);
            $user->password = hash('sha512', $data['password']);

            if(Arr::get($data, 'id_group', 0) != 0){
                $user->id_group = $data['id_group'];
            }

            $person = (new PersonRepo)->savePerson($data);

            $user->id_person = $person->id;
            $user->save();

            $this->sendEmailVerify(['person' => $person, 'id_user' => $user->id, 'name' => $person->name]);

            return ['success' => true, 'message' => 'Usuario cadastrado com sucesso, verifique a caixa de entrada do email para confirmar o cadastro.'];
        }

        throw new Exception('Não foi possível realizar o cadastro verifique todos os dados e tente novamente!', 400);
    }

    private function logout (array $data) : array
    {
        if(Auth::hasUser()) {
            $login = $this->user::find(Auth::id());
            $login->logged = false;
            $login->save();

            Session::flush();
            Auth::logout();

            return ['success' => true, 'message' => 'Usuario deslogado com sucesso!'];
        }

        throw new Exception ('Não foi possível deslogar o usuario.', 400);
    }

    private function sendEmailVerify (array $data) : bool
    {

        $email = [
            'title' => 'Confirmação de usuario',
            'template' => 'emails.verifyEmail',
            'name_system' => 'Market Research',
            'description' => 'Prezado '.$data['name'].' por favor clique no link abaixo para que seja feita a validação da sua conta. Caso não reconheça esse email por favor ignore a menssagem.',
            'text_button' => 'Confirmar Usuario',
            'link' => route('email.verify', ['token' => Crypt::encryptString($data['id_user'] .' '. Carbon::now()->format('Y-m-d H:i:s'))]),
            'emails' => [$data['person']]
        ];

        return (new EmailHelper)->getParameters($email);
    }

    private function confirmEmail (array $data) : array
    {
       $user = $this->user::find(explode(' ', Crypt::decryptString($data['token']))[0]);
       $user->email_verified_at = Carbon::now()->format('Y-m-d H:i:s');
       $user->save();

       return ['success' => true, 'Email confirmado com sucesso.'];
    }

    private function resetPassword (array $data) : array
    {
        $user = $this->user::query()->where('email', strtolower($data['email']))->first();
        $user->password = hash('sha512', $data['password']);
        $user->remember_token = Str::random(60);
        $user->save();

        return ['success' => true, 'message' => 'Senha alterada com sucesso!'];
    }
}
