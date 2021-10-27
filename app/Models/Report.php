<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;
    protected $table = 'reports';
    protected $fillable = [
        'id',
        'date',
        'type_report',
        'description',
        'assessment',
        'num_reports',
        'active',
        'id_place',
        'photo'
    ];

    public function person()
    {
        return $this->hasOne(Person::class);
    }

    public function place()
    {
        return $this->hasOne(Place::class);
    }
}
