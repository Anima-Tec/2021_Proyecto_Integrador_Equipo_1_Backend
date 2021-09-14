<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Place;
use Exception;

class PlaceController extends ApiController
{
    public function getPlaces()
    {
        $Places = Place::all();
        return $this->sendResponse($Places, 200);
    }

    public function getPlace($id)
    {
        $Place = Place::find($id);

        if ($Place) {
            return $this->sendResponse($Place, 200);
        }
        return $this->sendError('place not found', 405);
    }

    public function createPlace(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required',
                'address' => 'required',
            ]);

            if (Place::where('address', $request->input('address'))) {
                $Place = Place::create(
                    [
                        'name' => $request->input('name'),
                        'address' => $request->input('address')
                    ]
                );

                return $this->sendResponse($Place, 201);
            }
        } catch (Exception $error) {
            return $this->sendError($error, 405);
        }
    }

    public function updatePlace(Request $request, $id)
    {
        try {
            $request->validate([
                'name' => 'required',
                'address' => 'required',
            ]);

            Place::where('id', $id)
                ->update(
                    [
                        'name' => $request->input('name'),
                        'address' => $request->input('address')
                    ]
                );
            return $this->sendResponse(Place::find($id), 201);
        } catch (Exception $error) {
            return $this->sendError($error, 405);
        }
    }
}
