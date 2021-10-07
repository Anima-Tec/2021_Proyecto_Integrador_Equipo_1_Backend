<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Report;
use App\Models\Place;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\PlaceController;
use DateTime;
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

    public function generateDateAgo($reports)
    {
        $reports->map(function ($item) {
            $today = new DateTime();
            $dateReport = new DateTime($item->date);
            $diference = $today->diff($dateReport);
            unset($item->date);
            return $item->date_ago = $diference->format('%Y años %m meses %d days %H horas %i minutos %s segundos');
        });
        return $reports;
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
        $Reports = Report::where([['rd.id_place', $Place->id], ['active', 1]])
            ->select('reports.id', 'type_report', 'description', 'assessment', 'photo', 'address', 'rd.created_at as date')
            ->join('places', 'reports.id_place', '=', 'places.id')
            ->join('reports_created as rd', 'reports.id', '=', 'rd.id_report',)
            ->get();
        return $this->sendResponse($this->generateDateAgo($Reports), 200);
    }

    public function indexPerson($id)
    {
        $Reports = Report::where([['rd.id_person', $id], ['active', 1]])
            ->select('reports.id', 'type_report', 'description', 'assessment', 'photo', 'address', 'rd.created_at as date')
            ->join('places', 'reports.id_place', '=', 'places.id')
            ->join('reports_created as rd', 'reports.id', '=', 'rd.id_report',)
            ->get();
        return $this->sendResponse($this->generateDateAgo($Reports), 200);
    }

    public function index()
    {
        $Reports = Report::where('active', 1)
            ->select('reports.id', 'type_report', 'description', 'assessment', 'photo', 'address', 'rd.created_at as date')
            ->join('places', 'reports.id_place', '=', 'places.id')
            ->join('reports_created as rd', 'reports.id', '=', 'rd.id_report',)
            ->get();
        return $this->sendResponse($this->generateDateAgo($Reports), 200);
    }

    public function show($id)
    {
        $Report = Report::where([['reports.id', $id], ['active', 1]])
            ->select('reports.id', 'type_report', 'description', 'assessment', 'photo', 'address', 'rd.created_at as date')
            ->join('places', 'reports.id_place', '=', 'places.id')
            ->join('reports_created as rd', 'reports.id', '=', 'rd.id_report',)
            ->get();

        if ($Report) {
            return $this->sendResponse($this->generateDateAgo($Report), 200);
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
                    'id_person' => $request->input('id_person'),
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s"),
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
