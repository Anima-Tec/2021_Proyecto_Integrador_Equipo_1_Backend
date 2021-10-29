<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportPerson extends Model
{
    use HasFactory;
    protected $table = 'reports_created';
    protected $fillable = [
        'id_person',
        'id_report',
        'id_place'
    ];

    public function reports()
    {
        return $this->hasOne(Report::class);
    }

    public function places()
    {
        return $this->hasOne(Place::class);
    }

    public function persons()
    {
        return $this->hasOne(Person::class);
    }
}
