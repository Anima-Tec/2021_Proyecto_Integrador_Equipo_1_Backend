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

    public function personReport()
    {
        return $this->hasMany(ReportPerson::class, 'id_report');
    }
}
