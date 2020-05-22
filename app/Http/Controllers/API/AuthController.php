<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;

/**
 * @group Autenticacion
 *
 * APIs para administrar autenticacion
 */

class AuthController extends Controller
{

    /**
	 * Autenticar usuario
	 *
     * Servicio para poder autenticar usuarios
     * 
	 * @bodyParam name string required parametro para poder registrar/identificar al usuario
	 * @bodyParam email string required parametro para poder registrar/identificar al usuario
     * @bodyParam password string required parametro para poder completar la autenticación
     * 
     * @response {
     *  "token": String,
     *  "user": Object
     * }
     * 
	 */

    public function authenticate(Request $request)
    {
        try {
            $checkUser = User::where('email', $request->email)->first();

            if (!$checkUser) {
                $createUser = new User;
                $createUser->name = $request->name;
                $createUser->email = $request->email;
                $createUser->password = \Hash::make($request->name);
                $createUser->save();
                $loginUser = \Auth::attempt(['email' => $createUser->email, 'password' => $createUser->name]);

            } else {
                
                $loginUser = \Auth::attempt(['email' => $request->email, 'password' => $request->name]);

            }

            if ($loginUser) {
                $user = \Auth::user();

                $token = $user->createToken('PokemonFinderToken')->accessToken;

                return response()->json(['token' => $token, 'user' => $user], 200);
            }
        } catch (\Throwable $th) {

            return response()->json(['error' => $th->getMessage(), 'line' => $th->getLine()], 500);

        }
    }


}