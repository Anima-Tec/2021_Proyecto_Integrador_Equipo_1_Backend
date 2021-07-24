<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Person extends Model
{

    protected $connection='integrative_project';
    protected $table='person';
    protected $primaryKey = "person_id";
    public $timestamps=false;
}
