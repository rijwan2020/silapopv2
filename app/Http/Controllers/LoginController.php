<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
// use Auth;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $user = User::where('username', $request->username)->first();
        if($user){
            $imprintingcode = base64_encode($request->username.'.'.$user->level.'.'.md5($request->password));
            if($imprintingcode != $user->imprintingcode){
                return $this->sendError('Password Salah.', ['error' => 'Unauthorised']);
            }
            Auth::login($user);
            $user = Auth::user();
            $success['token'] = Str::random(60);
            $success['data'] = $user;
            $success['data']['penyuluh'] = $user->penyuluh;
            $success['data']['image_url'] = url('img/avatars/avatar.png');
            if($user->level == 49 || $user->level == 50){
                $file = Storage::exists('/profile/' . $success['data']['penyuluh']['id'] . '/image/' . $success['data']['penyuluh']['image']);
                if($file){
                    $success['data']['image_url'] = Storage::url('/profile/' . $success['data']['penyuluh']['id'] . '/image/' . $success['data']['penyuluh']['image']);
                }
            }

            return $this->sendResponse($success, 'User login successfully.');
        }else{
            return $this->sendError('Data user tidak ditemukan.', ['error' => 'Unauthorised']);
        }
    }
    public function sendResponse($result, $message)
    {
        $response = [
            'success' => true,
            'data' => $result,
            'message' => $message,
        ];

        return response()->json($response, 200);
    }

    public function sendError($error, $errorMessages = [], $code = 422)
    {
        $response = [
            'success' => false,
            'message' => $error,
        ];

        if (!empty($errorMessages)) {
            $response['data'] = $errorMessages;
        }

        return response()->json($response, $code);
    }

}
