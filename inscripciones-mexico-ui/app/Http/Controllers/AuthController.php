<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Http\Request;
use App\Service\AuthService;
use App\Models\User;

class AuthController extends Controller {

    const AUTHENTICATION_SOURCE_GOOGLE = 2;
    const AUTHENTICATION_SOURCE_FACEBOOK = 3;

    private AuthService $authService;

    public function __construct(AuthService $authService) {
        $this->authService = $authService;
    }

    public function login() {
        return view('components/login');
    }

    public function logout(Request $request) {
        $request->session()->put('user', null);
        $request->session()->forget('user');
        $request->session()->flush();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return view('welcome');
    }

    public function loginGoogle() {
        return Socialite::driver('google')->redirect();
    }

    public function handleLoginGoogle(Request $request) {
        $googleUser = Socialite::driver('google')->user();
        $user = $this->authService->handleLoginSocialite($googleUser, self::AUTHENTICATION_SOURCE_GOOGLE);
        $request->session()->put('user', $user);
        $_GET['state'] = "";
        return view('components/login-google-callback');
    }

    public function loginFacebook() {
        return Socialite::driver('facebook')->redirect();
    }

    public function handleLoginFacebook(Request $request) {
        $facebookUser = Socialite::driver('facebook')->user();
        $user = $this->authService->handleLoginSocialite($facebookUser, self::AUTHENTICATION_SOURCE_FACEBOOK);
        $request->session()->put('user', $user);
        $_GET['state'] = "";
        return view('components/login-facebook-callback');
    }

    public function profile(Request $request) {
        $encodedUserId = $request->userId;
        if (!isset($encodedUserId) || $encodedUserId == null) {
            return abort(404);
        }
        $decodedUserId = $this->authService->decode($encodedUserId);
        $user = $this->authService->getUserById($decodedUserId);
        if ($user == null || $user->id == null || $user->id < 1) {
            return abort(404);
        }
        $user->roles = $this->authService->getRolesByUser($user);
        $user->permissions = $this->authService->getPermissionsByUser($user);
        $user->authentication_sources = $this->authService->getAuthenticationSourcesByUser($user->id);

        return view('components/profile', [
            "profile_user" => $user
        ]);
    }

    public function updateProfile(Request $request) {
        $user = $this->authService->updateProfile($request);
        if (!isset($user) || $user == null) {
            return abort(404);
        }
        return redirect('/profile?userId=' . $user->encoded_id);
    }

    public function getAuthenticationSourcesByUser(Request $request) {
        $encodedUserId = $request->user_id;
        if (!isset($request) || $request == null || $request->session() == null || $request->session()->get('user') == null || $request->session()->get('user')->encoded_id != $encodedUserId) {
            return response()->json(array('message' => "forbidden"), 403);
        }
        if (!isset($encodedUserId) || $encodedUserId == null) {
            return response()->json(array('message' => "user_id is mandatory"), 400);
        }
        $decodedUserId = $this->authService->decode($encodedUserId);
        $authenticationSources = $this->authService->getAuthenticationSourcesByUser($decodedUserId);
        if (!isset($authenticationSources) || $authenticationSources == null
                || $authenticationSources[0] == null || $authenticationSources[0]->authentication_source_id == null
                || $authenticationSources[0]->authentication_source_id < 1) {
            return response()->json(array('message'=> "no content found"), 204);
        }
        return response()->json(array('authentication_sources' => $authenticationSources), 200);
    }

}
