<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Report;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\ReportController;
use Illuminate\Http\Request;

class AdminController extends ApiController
{
    public function delete(Request $request, $id)
    {
        $idPerson = DB::table('reports_created')
            ->where('id_report', $id)
            ->select('id_person');

        $User = User::find($idPerson);

        $Report = Report::find($id);

        Mail::to($User->email)->send(new \App\Mail\ReportDeleted([
            'title' => 'Reporte eliminado.',
            'body' => 'El siguiente reporte fue eliminado porque no se adecua a la comunidad: ' . $Report
        ]));

        DB::table('reports_deleted')
            ->insert([
                'id_report' => $Report->id,
                'id_place' => $Report->id_place,
                'id_person' => $idPerson,
                'id_admin' => $request->input('id_admin'),
            ]);

        return ReportController::delete($id);
    }

    public function saveReport($id)
    {
        Report::where('id', $id)
            ->update(['active' => 1]);
    }
}
