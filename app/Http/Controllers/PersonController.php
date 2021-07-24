<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Person;
use Exception;

class PersonController extends ApiController
{

    public function index()
    {
            $Person = Person::where('active', 1)
                ->select('person_id', 'name', 'surname', 'username', 'dateBirth', 'mail', "person_password", 'active')
                ->get();
            return $this->sendResponse($Person, "successfully");
    }

    public function store(Request $request)
    {
        try {

            $Person = new Person();
            $Person->name = $request->input('name');
            $Person->surname = $request->input('surname');
            $Person->username = $request->input('username');
            $Person->dateBirth = $request->input('dateBirth');
            $Person->mail = $request->input('mail');
            $Person->person_password = $request->input('person_password');
            $Person->save();
            return $this->sendResponse($Person, "successfully");
        } catch (Exception $e) {
            return $this->sendError("known error", "error: $e", 200);
        }
    }

    public function show($id)
    {
        try {

            $Person = Person::where('person_id', $id)
                ->select('person_id', 'name', 'surname', 'username', 'dateBirth', 'mail', "person_password", 'active')
                ->get();
            return $this->sendResponse($Person, "successfully");
        } catch (Exception $e) {
            return $this->sendError("known error", "error: $e", 200);
        }
    }

    public function update(Request $request)
    {
        try {

            $Person = Person::find($request->person_id);
            $Person->name = $request->input('name');
            $Person->surname = $request->input('surname');
            $Person->username = $request->input('username');
            $Person->dateBirth = $request->input('dateBirth');
            $Person->mail = $request->input('mail');
            $Person->person_password = $request->input('person_password');
            $Person->active = $request->input('active');
            $Person->save();
            return $this->sendResponse($Person, "successfully");
        } catch (Exception $e) {
            return $this->sendError("known error", "error: $e", 200);
        }
    }

    public function destroy($id)
    {
        try {

            $Person = Person::find($id)
                ->delete();
            return $this->sendResponse($Person, "successfully");
        } catch (Exception $e) {
            return $this->sendError("known error", "error: $e", 200);
        }
    }
}
