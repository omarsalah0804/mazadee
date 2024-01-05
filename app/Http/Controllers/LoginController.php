<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\LoginRequest;

class LoginController extends Controller
{
    /**
     * Display login page.
     * 
     * @return Renderable
     */
    public function show()
    {
        return view('auth.login');
    }

    /**
     * Handle account login request
     * 
     * @param LoginRequest $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function login(LoginRequest $request)
    {
        $credentials = $request->getCredentials();

        $userProvider = Auth::createUserProvider('users');
        $companyProvider = Auth::createUserProvider('companies');

        if (!$userProvider->validateCredentials($userProvider->retrieveByCredentials($credentials), $credentials)
            && !$companyProvider->validateCredentials($companyProvider->retrieveByCredentials($credentials), $credentials)) {
            return redirect()->to('login')->withErrors(trans('auth.failed'));
        }

        $user = $userProvider->retrieveByCredentials($credentials);
        $company = $companyProvider->retrieveByCredentials($credentials);

        if ($user) {
            Auth::guard('users')->login($user);
            return $this->authenticated($request, $user);
        } elseif ($company) {
            Auth::guard('company')->login($company);
            return $this->authenticated($request, $company);
        }
        // $credentials = $request->getCredentials();

        // if(!Auth::validate($credentials)):
        //     return redirect()->to('login')
        //         ->withErrors(trans('auth.failed'));
        // endif;

        // $user = Auth::getProvider()->retrieveByCredentials($credentials);

        // Auth::login($user);

        // return $this->authenticated($request, $user);
    }

    /**
     * Handle response after user authenticated
     * 
     * @param Request $request
     * @param Auth $user
     * 
     * @return \Illuminate\Http\Response
     */
    protected function authenticated(Request $request, $user) 
    {
        return redirect()->intended();
    }
}
