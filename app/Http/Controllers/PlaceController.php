<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Place;
use Exception;

class PlaceController extends Controller
{

    public function index()
    {
        $Place = Place::all();
        return $this->sendResponse($Place, "successfully");
    }

    public function store(Request $request)
    {
        try {

            $Place = new Place();
            $Place->name = $request->input('name');
            $Place->address = $request->input('address');
            $Place->save();
            return $this->sendResponse($Place, "successfully");
        } catch (Exception $e) {
            return $this->sendError("known error ", "error: $e", 200);
        }
    }

    public function show($id)
    {
        try {

            $Place = Place::where('place_id', $id)
                ->select('place_id', "name", "address")
                ->get();
            return $this->sendResponse($Place, "successfully");
        } catch (Exception $e) {
            return $this->sendError("known error", "error: $e", 200);
        }
    }

    public function update(Request $request)
    {
        try {

            $Place = Place::find($request->place_id);
            $Place->name = $request->input('name');
            $Place->address = $request->input('address');
            $Place->save();
            return $this->sendResponse($Place, "successfully");
        } catch (Exception $e) {
            return $this->sendError("known error", " error: $e", 200);
        }
    }

    public function destroy($id)
    {
        try {

            $Place = Place::find($id)
                ->delete();
            return $this->sendResponse($Place, "successfully");
        } catch (Exception $e) {
            return $this->sendError("known error", "error: $e", 200);
        }
    }
}
