<?php

namespace App\Http\Controllers;

use App\Models\Person;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
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

            $NewUser = DB::table('users')
                ->join('persons', 'users.id', '=', 'persons.id')
                ->select('users.username', 'users.email', 'persons.name', 'persons.surname', 'persons.birth_date', 'persons.photo_profile')
                ->where('users.id', $User->id)
                ->get();

            Mail::to($User->email)->send(new \App\Mail\CreateAcount([
                'title' => 'Registro con exito.',
                'body' => 'Bienvenido ' . $Person->name . ' ' . $Person->surname . '. Disfruta de la plataforma.'
            ]));

            return $this->sendResponse([
                'user' => $NewUser,
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
                'password' => 'required|string'
            ]);

            $User = User::where('username', $request->input(('username')))->first();
            Person::find($User->id);

            if (!$User || !Hash::check($request->input('password'), $User->password)) {
                return $this->sendError('', 'Las credenciales no coinciden', 405);
            }

            $token = $User->createToken('token')->plainTextToken;

            $FullUser = DB::table('users')
                ->join('persons', 'users.id', '=', 'persons.id')
                ->select('users.username', 'users.email', 'persons.name', 'persons.surname', 'persons.birth_date', 'persons.photo_profile')
                ->where('users.id', $User->id)
                ->get();

            return $this->sendResponse([
                'user' => $FullUser,
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
