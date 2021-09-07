<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Person;
use App\Models\User;
use Exception;

class PersonController extends ApiController
{
    public function getAllUsers()
    {
        $Users = User::where('activo', 1)
            ->join('person', 'users.id', '=', 'person.id')
            ->get();
        return $Users;
    }

    public function createUser(Request $request)
    {
        try {
            $request->validate([
                'username' => 'required',
                'email' => 'required',
                'password' => 'required',
                'name' => 'requiered',
                'surname' => 'requiered',
                'dateBirth' => 'requiered',
                'photo' => 'file'
            ]);

            /*if($req->file()) {
                $fileName = time().'_'.$req->file->getClientOriginalName();
                $filePath = $req->file('file')->storeAs('uploads', $fileName, 'public');
    
                $fileModel->name = time().'_'.$req->file->getClientOriginalName();
                $fileModel->file_path = '/storage/' . $filePath;
                $fileModel->save();
    
                return back()
                ->with('success','File has been uploaded.')
                ->with('file', $fileName);
            }*/

            $User = User::create(
                [
                    'username' => $request->input('username'),
                    'email' => $request->input('email'),
                    'password' => $request->input('password')
                ]
            );
            $Person = Person::create(
                [
                    'name' => $request->input('name'),
                    'surname' => $request->input('surname'),
                    'date_birth' => $request->input('dateBirth'),
                    'photo_profile' => $request->file('photo')->getClientOriginalName()
                ]
            );
            return $Person;
        } catch (Exception $error) {
            return $this->sendError($error, 'error to create user', 405);
        }
    }

    public function getUser($id)
    {
        $User = User::where('id', $id)
            ->join('person', 'users.id', '=', 'person.id')
            ->get();
        return $User;
    }

    public function updateUser(Request $request, $id)
    {
        try {
            $request->validate([
                'username' => 'required',
                'email' => 'required',
                'password' => 'required',
                'name' => 'requiered',
                'surname' => 'requiered',
                'dateBirth' => 'requiered',
                'photo' => 'file'
            ]);

            $User = User::where('id', $id)
                ->update(
                    [
                        'username' => $request->input('username'),
                        'email' => $request->input('email'),
                        'password' => $request->input('password')
                    ]
                );
            $Person = Person::where('id', $id)
                ->update(
                    [
                        'name' => $request->input('name'),
                        'surname' => $request->input('surname'),
                        'date_birth' => $request->input('dateBirth'),
                        'photo_profile' => $request->input('photo')
                    ]
                );
            return $User;
        } catch (Exception $error) {
            return $this->sendError($error, 'error to update user', 405);
        }
    }

    public function deleteUser($id)
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
