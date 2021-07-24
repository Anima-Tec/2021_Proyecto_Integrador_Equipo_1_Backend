<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Place extends Model
{

    protected $connection='integrative_project';
    protected $table='place';
    protected $primaryKey = "place_id";
    public $timestamps=false;
}
