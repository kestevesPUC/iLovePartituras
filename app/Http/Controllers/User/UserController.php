<?php namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Repositories\User\UserRepo;
use Illuminate\Http\Request;

class UserController extends Controller
{
    private $request;
    private $userRepo;

    public function __construct(Request $request, UserRepo $userRepo)
    {
        $this->request = $request;
        $this->userRepo = $userRepo;

    }

    public function profile (string $userId)
    {
        $person = $this->userRepo->onGet(['user_id' => $userId], 'getProfile');
        return view('user.userProfile')->with(['person' => $person]);
    }

    public function editProfile (string $userId)
    {
        return json_encode($this->userRepo->onPost(['user_id' => $userId, 'data' => json_decode($this->request->data)], 'saveProfile'));
    }

    public function getPerson ()
    {
        return json_encode($this->userRepo->onGet($this->request->all(), 'getPerson'));
    }

    public function getPicture (string $idUser)
    {
        return response($this->userRepo->onGet(['id_user' => $idUser], 'getPicture'))->header('Content-Type', 'image/jpg');
    }
}
