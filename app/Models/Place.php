<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Place extends Model
{
    use HasFactory;
    protected $table = 'places';
    protected $fillable = [
        'id',
        'name',
        'address'
    ];

    public function report()
    {
        return $this->hasMany(Report::class, 'id_place');
    }

    public function personReport()
    {
        return $this->hasMany(ReportPerson::class, 'id_place');
    }
}
