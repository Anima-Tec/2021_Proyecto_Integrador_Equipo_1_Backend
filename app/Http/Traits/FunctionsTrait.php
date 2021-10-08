<?php

namespace App\Http\Traits;

use Illuminate\Http\Request;
use DateTime;

trait FunctionsTrait
{
    public function createPathPhoto(Request $request, $folder)
    {
        if ($request->file('photo')) {
            $photo_profile = time() . '.' . $request->file('photo')->extension();
            $request->file('photo')->move(public_path('/' . $folder), $photo_profile);
            $path = '/' . $folder . '/' . $photo_profile;
            return $path;
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

    
}
