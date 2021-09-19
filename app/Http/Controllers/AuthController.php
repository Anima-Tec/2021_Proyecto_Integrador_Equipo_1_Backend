<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Person;
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

            $token = $User->createToken('token')->plainTextToken;

            $Person = Person::create([
                'id' => $User->id,
                'name' => $request->input('name'),
                'surname' => $request->input('surname'),
                'date_birth' => $request->input('birth_date')
            ]);

            $NewUser = User::find($User->id)
                ->join('persons', 'users.id', '=', 'persons.id')
                ->get();

            Mail::to($User->email)->send(new \App\Mail\CreateAcount([
                'title' => 'Registro con exito.',
                'body' => 'Bienvenido ' . $Person->name . ' ' . $Person->surname . '. Disfruta de la plataforma'
            ]));

            return $this->sendResponse([
                'user' => $NewUser,
                'token' => $token
            ], 201);
        } catch (Exception $error) {
            return $this->sendError($error->errorInfo, 'Error al crear el usuario', 405);
        }
    }

    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|string',
                'password' => 'required|string'
            ]);

            $User = User::where('email', $request->input(('email')))->first();

            if (!$User || !Hash::check($request->input('password'), $User->password)) {
                return $this->sendError('', 'Las credenciales no coinciden', 405);
            }

            $token = $User->createToken('token')->plainTextToken;

            $FullUser = User::find($User->id)
                ->join('persons', 'users.id', '=', 'persons.id')
                ->get();

            return $this->sendResponse([
                'user' => $FullUser,
                'token' => $token
            ], 201);
        } catch (Exception $error) {
            return $this->sendError($error->errorInfo, 'error to create user', 405);
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
