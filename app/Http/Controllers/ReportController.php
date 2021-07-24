<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Report;
use Exception;

class ReportController extends ApiController
{

    public function index()
    {
        $Report = Report::where('active', 1)
            ->select('report_id', 'date', 'description', 'assessment', 'label', 'photo', 'person_id', 'date_report', 'place_id', 'active')
            ->get();
        return $this->sendResponse($Report, "successfully");
    }

    public function store(Request $request)
    {
        try {

            $Report = new Report();
            $Report->date = $request->input('date');
            $Report->description = $request->input('description');
            $Report->assessment = $request->input('assessment');
            $Report->label = $request->input('label');
            $Report->photo = $request->input('photo');
            $Report->place_id = $request->input('place_id');
            $Report->date_report = $request->input('date_report');
            $Report->person_id = $request->input('person_id');
            $Report->save();
            return $this->sendResponse($Report, "successfully");
        } catch (Exception $e) {
            return $this->sendError("known error ", "error: $e", 200);
        }
    }

    public function show($id)
    {
        try {

            $Report = Report::where('report_id', $id)
                ->select('report_id', 'date', 'description', 'assessment', 'label', 'photo', 'person_id', 'date_report', 'place_id', 'active')
                ->get();
            return $this->sendResponse($Report, "successfully");
        } catch (Exception $e) {
            return $this->sendError("known error", "error: $e", 200);
        }
    }

    public function update(Request $request)
    {
        try {

            $Report = new Report();
            $Report->date = $request->input('date');
            $Report->description = $request->input('description');
            $Report->assessment = $request->input('assessment');
            $Report->label = $request->input('label');
            $Report->photo = $request->input('photo');
            $Report->place_id = $request->input('place_id');
            $Report->date_report = $request->input('date_report');
            $Report->person_id = $request->input('person_id');
            $Report->save();
            return $this->sendResponse($Report, "successfully");
        } catch (Exception $e) {
            return $this->sendError("known error ", "error: $e", 200);
        }
    }

    public function destroy($id)
    {
        try {

            $Report = Report::find($id)
                ->delete();
            return $this->sendResponse($Report, "successfully");
        } catch (Exception $e) {
            return $this->sendError(" known error ", "error: $e", 200);
        }
    }
}
