<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Report;
use Exception;

class ReportController extends ApiController
{
    public function getAllReports()
    {
        $Report = Report::where('active', 1)
            ->get();

        return $this->sendResponse($Report, 200);
    }

    public function getReport($id)
    {
        $Report = Report::find($id);

        if ($Report) {
            return $this->sendResponse($Report, 200);
        }
        return $this->sendError('report not found', 405);
    }

    public function createReport(Request $request)
    {
        try {
            $request->validate([
                'date' => 'required',
                'description' => 'required',
                'type_report' => 'required',
                'assessment' => 'required',
                'id_place' => 'required',
            ]);

            $Report = Report::create([
                'date' => $request->input('date'),
                'description' => $request->input('description'),
                'type_report' => $request->input('typeReport'),
                'assessment' => $request->input('assessment'),
                'id_place' => $request->input('idPlace'),
            ]);

            return $this->sendResponse($Report, 201);
        } catch (Exception $error) {
            return $this->sendError($error, 405);
        }
    }

    public function updateReport(Request $request, $id)
    {
        try {
            $request->validate([
                'date' => 'required',
                'description' => 'required',
                'type_report' => 'required',
                'assessment' => 'required',
                'id_place' => 'required',
            ]);

            $Report = Report::where('id', $id)
                ->update([
                    'date' => $request->input('date'),
                    'description' => $request->input('description'),
                    'type_report' => $request->input('typeReport'),
                    'assessment' => $request->input('assessment'),
                    'id_place' => $request->input('idPlace'),
                ]);

            return $this->sendResponse($Report, 200);
        } catch (Exception $error) {
            return $this->sendError($error, 405);
        }
    }

    public function deleteReport($id)
    {
        try {
            $Report = Report::where('id', $id)
                ->update(['active' => 0]);

            return $this->sendResponse($Report, 200);
        } catch (Exception $error) {
            return $this->sendError($error, 405);
        }
    }
}
