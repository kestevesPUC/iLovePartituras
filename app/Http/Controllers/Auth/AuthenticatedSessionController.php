<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use App\Repositories\Login\LoginRepo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;


class AuthenticatedSessionController extends Controller
{
    private $loginRepo;
    private $request;

    public function __construct(Request $request, LoginRepo $loginRepo)
    {
        $this->loginRepo = $loginRepo;
        $this->request = $request;
    }


    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store ()
    {
        return json_encode($this->loginRepo->onPost($this->request->all(), 'login'));
    }

    public function logout ()
    {
        $this->loginRepo->onPost([], 'logout');
        return Redirect::to('/login');
    }
}
