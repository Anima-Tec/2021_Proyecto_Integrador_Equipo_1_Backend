<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;

class UserController extends ApiController
{

    public function index()
    {
        $Users = User::where('activo', 1)
            ->get();
        return $this->sendResponse($Users, 200);
    }

    public function store(Request $request)
    {
        try {
            $User = new User();
            $User->username = $request->input('username');
            $User->email = $request->input('email');
            $User->password = $request->input('password');
            $User->save();
            return $this->sendResponse($User, 201);
        } catch (Exception $error) {
            return $this->sendError($error, 'error to create user', 405);
        }
    }

    public function show($id)
    {
        $User = User::where('id', $id)
            ->select();
        return $this->sendResponse($User, 200);
    }

    public function update(Request $request, $id)
    {
        try {
            $User = User::where('id', $id)
                ->update(
                    ['username' => $request->input('username')],
                    ['email' => $request->input('email')],
                    ['password' => $request->input('password')]
                );
            $User->save();
            return $this->sendResponse($User, 200);
        } catch (Exception $error) {
            return $this->sendError($error, 'error to update user', 405);
        }
    }

    public function destroy($id)
    {
        try {
            $User = User::where('id', $id)
                ->update(['active' => 0]);
            return $this->sendResponse($User, 200);
        } catch (Exception $error) {
            return $this->sendError($error, 'error to destroy user', 405);
        }
    }
}
