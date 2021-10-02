<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Person;
use App\Models\Report;
use App\Models\User;
use Exception;

class PersonController extends ApiController
{
    public function show($id)
    {
        $heExist = User::find($id);
        if ($heExist) {
            $User = User::where([['persons.id', $id], ['active', 1]])
                ->join('persons', 'users.id', '=', 'persons.id')
                ->select('users.username', 'users.email', 'persons.name', 'persons.surname', 'persons.birth_date', 'persons.photo_profile')
                ->get();

            return $this->sendResponse($User, 200);
        }
        return $this->sendError($heExist, 'usuario no encontrado', 405);
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'name' => 'required|string',
                'surname' => 'required|string',
                'password' => 'required|string|confirmed',
            ]);

            if ($request->file('photo')) {
                $photo_profile = time() . "." . $request->file('photo')->extension();
                $request->file('photo')->move(public_path('/photo_profiles'), $photo_profile);
                $path = "public/photo_profiles/$photo_profile";
            } else {
                $path = NULL;
            }

            Person::where('id', $id)
                ->update([
                    'name' => $request->input('name'),
                    'surname' => $request->input('surname'),
                    'photo_profile' => $path
                ]);

            $UserUpdated = Person::find($id)
                ->join('users', 'persons.id', '=', 'users.id')
                ->select('users.username', 'users.email', 'persons.name', 'persons.surname', 'persons.birth_date', 'persons.photo_profile')
                ->get();

            return $this->sendResponse($UserUpdated, 200);
        } catch (Exception $error) {
            return $this->sendError($error, 'error al actualizar el usuario', 405);
        }
    }

    public function delete($id)
    {
        try {
            $User = User::where('id', $id)
                ->update(['active' => 0]);
            return $this->sendResponse(["userId" => $User], 200);
        } catch (Exception $error) {
            return $this->sendError($error, 'error al eliminar el usuario', 405);
        }
    }

    public function report($id)
    {
        $Report = Report::find($id);

        Report::where('id', $id)
            ->update(['num_reports' => $Report->num_reports + 1]);

        if (Report::find($id)->num_reports >= 5) {
            Report::where('id', $id)
                ->update(['active' => 2]);
        }
    }
}
