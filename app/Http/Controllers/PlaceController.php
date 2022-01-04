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
            $Place = Place::where('address', $address)->select('id', 'address', 'name')->first();
            if ($Place) {
                $dataPlace = DB::select('select avg(assessment) as assessment, count(*) as quantity from reports where id_place = :id', ['id' => $Place->id]);
                return $this->sendResponse(['place' => $Place, 'assessment' => $dataPlace[0]->assessment, 'quantity' => $dataPlace[0]->quantity], 200);
            }
        } catch (Exception $error) {
            return $this->sendError($error, 405);
        }
    }

    public static function store(Request $request)
    {
        $Place = Place::where('address', $request->input('address_place'))->first();

        if (!$Place) {
            $Place = Place::create([
                'address' => $request->input('address_place'),
                'name' =>  $request->input('name_place')
            ]);
        }

        return $Place->id;
    }
}
