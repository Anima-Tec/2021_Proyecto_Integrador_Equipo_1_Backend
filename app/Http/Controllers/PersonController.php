<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Person;
use App\Models\Report;
use App\Models\User;
use App\Http\Traits\FunctionsTrait;
use App\Models\ReportsReview;
use Exception;

class PersonController extends ApiController
{
    use FunctionsTrait;

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

            Person::where('id', $id)
                ->update([
                    'name' => $request->input('name'),
                    'surname' => $request->input('surname'),
                    'photo_profile' => $this->createPathPhoto($request, "persons"),
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

    public function report(Request $request)
    {
        $Report = Report::find($request->input('id_report'));
        $wasReported = ReportsReview::where([['id_report', $Report->id], ['id_person', $request->input('id_person')]])->first();

        if (!$wasReported) {
            Report::where('id', $Report->id)
                ->update(['num_reports' => $Report->num_reports + 1]);

            if (Report::find($Report->id)->num_reports >= 5) {
                Report::where('id', $Report->id)
                    ->update(['active' => 2]);
            }

            ReportsReview::create([
                'id_report' => $Report->id,
                'id_person' => $request->input('id_person')
            ]);

            return $this->sendResponse("Reportado correctamente.", 200);
        }

        return $this->sendResponse("Su reporte ya esta en revisi??n", 200);
    }
}
