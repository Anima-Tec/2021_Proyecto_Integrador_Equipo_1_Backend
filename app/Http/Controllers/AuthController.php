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
                'dateBirth' => 'required',
                'photo' => 'file'
            ]);

            $photo_profile = time() . "." . $request->file('photo')->extension();
            $request->file('photo')->move(public_path('/photo_profiles'), $photo_profile);
            $path = "public/photo_profiles/$photo_profile";

            $User = User::create([
                'username' => $request->input('username'),
                'email' => $request->input('email'),
                'password' => bcrypt($request->input('password'))
            ]);

            $token = $User->createToken('myapptoken')->plainTextToken;

            $Person = Person::create([
                'id' => $User->id,
                'name' => $request->input('name'),
                'surname' => $request->input('surname'),
                'date_birth' => $request->input('dateBirth'),
                'photo_profile' => $path
            ]);

            $NewUser = User::find($User->id)
                ->join('persons', 'users.id', '=', 'persons.id')
                ->get();

            $response = [
                'user' => $NewUser,
                'token' => $token
            ];

            $details = [
                'title' => 'Registro con exito.',
                'body' => 'Bienvenido ' . $Person->name . '. Disfruta de la plataforma'
            ];

            Mail::to($User->email)->send(new \App\Mail\CreateAcount($details));

            return $this->sendResponse($response, 201);
        } catch (Exception $error) {
            return $this->sendError($error, 'error to create user', 405);
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
                return $this->sendError('', 'error to login', 405);
            }

            $token = $User->createToken('myapptoken')->plainTextToken;

            $FullUser = User::find($User->id)
                ->join('persons', 'users.id', '=', 'persons.id')
                ->get();

            $response = [
                'user' => $FullUser,
                'token' => $token
            ];

            return $this->sendResponse($response, 201);
        } catch (Exception $error) {
            return $this->sendError($error, 'error to create user', 405);
        }
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();

        return [
            'menssage' => 'Logged out'
        ];
    }
}
