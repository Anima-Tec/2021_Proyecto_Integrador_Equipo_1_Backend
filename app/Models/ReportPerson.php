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
}
