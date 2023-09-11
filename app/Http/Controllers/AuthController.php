<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Repositories\FacebookUserRepository;
use App\Presenters\UserPresenter;


class AuthController extends Controller
{
    public function login (Request $request) {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $token = $user->createToken('fb-auto')->plainTextToken;
        return ['token' => $token];
    }

    public function logout (Request $request) {
        // Revoke the token that was used to authenticate the current request...
        return $request->user()->currentAccessToken()->delete();
    }

    public function storeFbToken(Request $request)
    {
        $result = [
            'success' => false,
            'data'    => [],
            'message' => 'failed'
        ];
        $user = $request->user();
        $fbUserRepo = App(FacebookUserRepository::class);
        $resultCheck = $fbUserRepo->checkTokenValid($request->get('access_token'));
        if ($resultCheck['success']) {
            $user->fb_access_token = $request->get('access_token');
            $user->save();
            return [
                'success' => true,
                'data'    => $resultCheck['data'],
                'message' => 'Save fb token successfully'
            ];
        }
        return $result;
    }

    public function getUserInfo (Request $request) {
        $user = $request->user();
        $fbUserRepo = App(FacebookUserRepository::class);
        $resultCheck = $fbUserRepo->checkTokenValid($user->fb_access_token);
        $user->fbUserInfo = !empty($resultCheck['data']) ? $resultCheck['data'] : null;
        return app(UserPresenter::class)->present($user)['data'];
    }

    public function storeSettingUser(Request $request)
    {
        $user = $request->user();
        $user->params = $request->get('params');
        $user->save();
        return [
            'success' => true,
            'data'    => $user,
            'message' => 'Save setting successfully'
        ];
    }
}
