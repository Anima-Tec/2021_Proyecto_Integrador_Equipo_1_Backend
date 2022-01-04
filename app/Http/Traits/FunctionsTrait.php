<?php

namespace App\Http\Traits;

use Illuminate\Http\Request;
use App\Models\Report;
use DateTime;

trait FunctionsTrait
{
    public function createPathPhoto(Request $request, $folder)
    {
        if ($request->hasFile('photo')) {
            $response = cloudinary()->upload($request->file('photo')->getRealPath(), [
                'folder' => $folder
            ])->getSecurePath();
            return $response;
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
            return $item->date_ago = $diference->format('%Y años %m meses %d días %H horas %i minutos %s segundos');
        });
        return $reports;
    }

    public function getReports($conditions)
    {
        $Reports = Report::where($conditions)
            ->select('reports.id', 'type_report', 'description', 'assessment', 'photo as photo_place', 'address', 'rc.created_at as date', 'username', 'photo_profile')
            ->join('places', 'reports.id_place', '=', 'places.id')
            ->join('reports_created as rc', 'reports.id', '=', 'rc.id_report',)
            ->join('users', 'users.id', '=', 'rc.id_person')
            ->join('persons', 'persons.id', '=', 'rc.id_person')
            ->get();
        return $Reports;
    }
}
