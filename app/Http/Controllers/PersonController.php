<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
        $heExist = User::find($id);

        if ($heExist) {
            $User = User::where([['persons.id', $id], ['active', 1]])
                ->join('persons', 'users.id', '=', 'persons.id')
                ->get();

            return $this->sendResponse($User, 200);
        }

        return $this->sendError($heExist, 'usuario no encontrado', 405);
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
                'birth_date' => 'required'
            ]);

            if ($request->file('photo')) {
                $photo_profile = time() . "." . $request->file('photo')->extension();
                $request->file('photo')->move(public_path('/photo_profiles'), $photo_profile);
                $path = "public/photo_profiles/$photo_profile";
            } else {
                $path = '';
            }

            User::where('id', $id)
                ->update([
                    'username' => $request->input('username'),
                    'email' => $request->input('email')
                ]);

            Person::where('id', $id)
                ->update([
                    'name' => $request->input('name'),
                    'surname' => $request->input('surname'),
                    'birth_date' => $request->input('birth_date'),
                    'photo_profile' => $path
                ]);

            $UserUpdated = Person::find($id)
                ->join('users', 'persons.id', '=', 'users.id')
                ->get();

            return $this->sendResponse($UserUpdated, 200);
        } catch (Exception $error) {
            return $this->sendError($error, 'error al actualizar el usuario', 405);
        }
    }

    public function deletePerson($id)
    {
        try {
            $User = User::where('id', $id)
                ->update(['active' => 0]);
            return $this->sendResponse($User, 200);
        } catch (Exception $error) {
            return $this->sendError($error->errorInfo, 'error al eliminar el usuario', 405);
        }
    }
}
