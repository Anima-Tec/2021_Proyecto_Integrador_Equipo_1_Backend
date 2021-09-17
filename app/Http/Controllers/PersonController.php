<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Person;
use App\Models\User;
use Exception;

class PersonController extends ApiController
{
    public function getAllPersons()
    {
        $Users = User::where('active', 1)
            ->join('persons', 'users.id', '=', 'persons.id')
            ->get();
        return $Users;
    }

    public function getPerson($id)
    {
        $exist = User::find($id);

        if ($exist) {
            $User = User::where([['persons.id', $id], ['active', 1]])
                ->join('persons', 'users.id', '=', 'persons.id')
                ->get();

            return $this->sendResponse($User, 200);
        }

        return $this->sendError($exist, 'user not found', 405);
    }

    public function updatePerson(Request $request, $id)
    {
        try {
            $request->validate([
                'username' => 'required|string',
                'email' => 'required|string',
                'password' => 'required|string',
                'name' => 'required|string',
                'surname' => 'required|string',
                'birth_date' => 'required',
                'photo' => 'file'
            ]);

            $photo_profile = time() . "." . $request->file('photo')->extension();
            $request->file('photo')->move(public_path('/photo_profiles'), $photo_profile);
            $path = "public/photo_profiles/$photo_profile";

            User::where('id', $id)
                ->update([
                    'username' => $request->input('username'),
                    'email' => $request->input('email')
                ]);

            Person::where('id', $id)
                ->update([
                    'name' => $request->input('name'),
                    'surname' => $request->input('surname'),
                    'date_birth' => $request->input('dateBirth'),
                    'photo_profile' => $path
                ]);

            $UserUpdated = User::find($id)
                ->join('persons', 'users.id', '=', 'persons.id')
                ->get();

            return $this->sendResponse($UserUpdated, 200);
        } catch (Exception $error) {
            return $this->sendError($error->errorInfo, 'error to update user', 405);
        }
    }

    public function deletePerson($id)
    {
        try {
            $User = User::where('id', $id)
                ->update(['active' => 0]);
            return $User;
        } catch (Exception $error) {
            return $this->sendError($error->errorInfo, 'error to destroy user', 405);
        }
    }
}
