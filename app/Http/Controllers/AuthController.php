<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Person;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Exception;

class AuthController extends ApiController
{
    public function register(Request $request)
    {
        try {
            $request->validate([
                'username' => 'required|string',
                'email' => 'required|string',
                'password' => 'required|string|confirmed',
                'name' => 'required|string',
                'surname' => 'required|string',
                'birth_date' => 'required',
            ]);

            $User = User::create([
                'username' => $request->input('username'),
                'email' => $request->input('email'),
                'password' => bcrypt($request->input('password'))
            ]);

            $Person = Person::create([
                'id' => $User->id,
                'name' => $request->input('name'),
                'surname' => $request->input('surname'),
                'birth_date' => $request->input('birth_date')
            ]);

            $token = $User->createToken('token')->plainTextToken;

            Mail::to($User->email)->send(new \App\Mail\CreateAcount([
                'title' => 'Registro con exito.',
                'body' => 'Bienvenido ' . $Person->name . ' ' . $Person->surname . '. Disfruta de la plataforma.'
            ]));

            return $this->sendResponse([
                'userId' => $User->id,
                'token' => $token
            ], 201);
        } catch (Exception $error) {
            return $this->sendError($error, 'error al registrar el usuario', 405);
        }
    }

    public function login(Request $request)
    {
        try {
            $request->validate([
                'username' => 'required|string',
                'password' => 'required|string',
            ]);

            $User = User::where('username', $request->input(('username')))->first();

            if (!$User || !Hash::check($request->input('password'), $User->password)) {
                return $this->sendError('', 'Las credenciales no coinciden', 405);
            }

            $token = $User->createToken('token')->plainTextToken;
            $typeUser = 'normal';

            if(Admin::find($User->id)){
                $typeUser = 'admin';
            }

            return $this->sendResponse([
                'userId' => $User->id,
                'typeUser' => $typeUser,
                'token' => $token
            ], 201);
        } catch (Exception $error) {
            return $this->sendError($error, 'error al loguearse', 405);
        }
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();

        return response()->json([
            'menssage' => 'Sesión finalizada'
        ], 200);
    }
}
