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

    public function createPerson(Request $request)
    {
        try {
            $exist = Person::where('username', $request->input('username'));

            if (!$exist) {
                $photo_profile = time() . "." . $request->file('photo')->extension();
                $request->file('photo')->move(public_path('/photo_profiles'), $photo_profile);
                $path = "public/images/$photo_profile";

                $User = User::create(
                    [
                        'username' => $request->input('username'),
                        'email' => $request->input('email'),
                        'password' => Hash::make($request->input('password'))
                    ]
                );

                $Person = Person::create(
                    [
                        'id' => $User->id,
                        'name' => $request->input('name'),
                        'surname' => $request->input('surname'),
                        'date_birth' => $request->input('dateBirth'),
                        'photo_profile' => $path
                    ]
                );

                return $this->sendResponse([$Person, $User], 201);
            }
            return $this->sendError($exist, 'person alredy exist', 405);
        } catch (Exception $error) {
            return $this->sendError($error, 'error to create user', 405);
        }
    }

    public function getPerson($id)
    {

        $exist = User::find($id);

        if (!$exist) {
            $User = User::where([['persons.id', $id], ['active', 1]])
                ->join('persons', 'users.id', '=', 'persons.id')
                ->get();

            return $this->sendResponse($User, 200);
        }
        return $this->sendError($exist, 'user alredy exist', 405);
    }

    public function updatePerson(Request $request, $id)
    {
        try {
            $photo_profile = time() . "." . $request->file('photo')->extension();
            $request->file('photo')->move(public_path('/photo_profiles'), $photo_profile);
            $path = "public/images/$photo_profile";

            User::where('id', $id)
                ->update(
                    [
                        'username' => $request->input('username'),
                        'email' => $request->input('email')
                    ]
                );
            Person::where('id', $id)
                ->update(
                    [
                        'name' => $request->input('name'),
                        'surname' => $request->input('surname'),
                        'date_birth' => $request->input('dateBirth'),
                        'photo_profile' => $path
                    ]
                );
            return $this->sendResponse([User::where('id', $id)->get(), Person::where('id', $id)->get()], 200);
        } catch (Exception $error) {
            return $this->sendError($error, 'error to update user', 405);
        }
    }

    public function deletePerson($id)
    {
        try {
            $User = User::where('id', $id)
                ->update(['active' => 0]);
            return $User;
        } catch (Exception $error) {
            return $this->sendError($error, 'error to destroy user', 405);
        }
    }
}
