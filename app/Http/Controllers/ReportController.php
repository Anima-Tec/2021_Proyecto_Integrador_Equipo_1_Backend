<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Report;
use App\Models\Place;
use App\Models\Person;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\PlaceController;
use Exception;

class ReportController extends ApiController
{

    public function createPathPhoto(Request $request)
    {
        if ($request->file('photo')) {
            $photo_profile = time() . "." . $request->file('photo')->extension();
            $request->file('photo')->move(public_path('/photo_reports'), $photo_profile);
            return "public/photo_reports/$photo_profile";
        }
        return NULL;
    }

    public function indexAdmin()
    {
        $Reports = Report::where('active', 2)
            ->select('id', 'date', 'type_report', 'description', 'assessment', 'photo')
            ->get();

        return $this->sendResponse($Reports, 200);
    }

    public function indexPlace($address)
    {
        $Place = Place::where('address', $address)->first();
        $Reports = Report::where([['active', 1], ['id_place', $Place->id]])
            ->select('id', 'date', 'type_report', 'description', 'assessment', 'photo')
            ->get();

        return $this->sendResponse($Reports, 200);
    }

    public function indexPerson($id)
    {
        $Person = Person::where('id', $id)->first();
        $Reports = DB::select('select r.id, r.date, r.type_report, r.description, r.assessment, r.photo  from reports_created as rd inner join reports as r on rd.id_report = r.id where rd.id_person = :id && r.active = 1', ['id' => $Person->id]);
        return $this->sendResponse($Reports, 200);
    }

    public function index()
    {
        $Reports = Report::where('active', 1)
            ->select('id', 'date', 'type_report', 'description', 'assessment', 'photo')
            ->get();
        return $this->sendResponse($Reports, 200);
    }

    public function show($id)
    {
        $Report = Report::where([['id', $id], ['active', 1]])
            ->select('id', 'date', 'type_report', 'description', 'assessment', 'photo')
            ->get();

        if ($Report) {
            return $this->sendResponse($Report, 200);
        }
        return $this->sendError('report not found', 405);
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'date' => 'required',
                'description' => 'required|string',
                'type_report' => 'required|string',
                'address' => 'required|string',
                'assessment' => 'required',
            ]);

            $idPlace = PlaceController::store($request);

            $Report = Report::create([
                'date' => $request->input('date'),
                'description' => $request->input('description'),
                'type_report' => $request->input('type_report'),
                'assessment' => $request->input('assessment'),
                'id_place' => $idPlace,
                'photo' => $this->createPathPhoto($request),
            ]);

            DB::table('reports_created')
                ->insert([
                    'id_report' => $Report->id,
                    'id_place' => $idPlace,
                    'id_person' => $request->input('id_person')
                ]);

            return $this->sendResponse('creado correctamente', 201);
        } catch (Exception $error) {
            return $this->sendError($error, 405);
        }
    }

    public function update(Request $request, $id)
    {
        try {

            $idPlace = PlaceController::store($request);

            Report::where('id', $id)
                ->update([
                    'date' => $request->input('date'),
                    'description' => $request->input('description'),
                    'type_report' => $request->input('typeReport'),
                    'assessment' => $request->input('assessment'),
                    'photo' => $this->createPathPhoto($request),
                    'id_place' => $idPlace,
                ]);

            return $this->sendResponse('actualizado correctamente', 200);
        } catch (Exception $error) {
            return $this->sendError($error, 405);
        }
    }

    public function delete($id)
    {
        try {
            Report::where('id', $id)
                ->update(['active' => 0]);

            return $this->sendResponse('reporte eliminado', 200);
        } catch (Exception $error) {
            return $this->sendError($error, 405);
        }
    }
}
