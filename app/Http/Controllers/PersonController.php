<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Person;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Exception;

class PersonController extends ApiController
{
    public function getAllPersons()
    {
        $Persons = Person::where('active', 1)
            ->join('users', 'persons.id', '=', 'users.id')
            ->get();
        return $Persons;
    }

    public function getPerson($id)
    {
        $heExist = Person::find($id);

        if ($heExist) {
            $Person = Person::where([['persons.id', $id], ['active', 1]])
                ->join('users', 'persons.id', '=', 'users.id')
                ->get();

            return $this->sendResponse($Person, 200);
        }

        return $this->sendError($heExist, 'user not found', 405);
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

            DB::table('users')
                ->where('id', $id)
                ->update([
                    'username' => $request->input('username'),
                    'email' => $request->input('email')
                ]);

            DB::table('persons')
                ->where('id', $id)
                ->update([
                    'name' => $request->input('name'),
                    'surname' => $request->input('surname'),
                    'date_birth' => $request->input('dateBirth'),
                    'photo_profile' => $path
                ]);

            $UserUpdated = Person::find($id)
                ->join('users', 'persons.id', '=', 'users.id')
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
