<?php

namespace App\Http\Controllers;

use App\Models\Place;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PlaceController extends ApiController
{
    public function show($address)
    {
        try {
            $Place = Place::where('address', $address)->select('id','address')->first();
            if ($Place) {
                $avgAssessmentPlace = DB::select('select avg(assessment) as assessment from reports where id_place = :id', ['id' => $Place->id]);
                return $this->sendResponse(['place' => $Place, 'assessment' => $avgAssessmentPlace[0]], 200);
            }
        } catch (Exception $error) {
            return $this->sendError($error, 405);
        }
    }

    public static function store(Request $request)
    {
        $Place = Place::where('address', $request->input('address'))->first();

        if (!$Place) {
            $Place = Place::create([
                'address' => $request->input('address'),
            ]);
        }

        return $Place->id;
    }
}
