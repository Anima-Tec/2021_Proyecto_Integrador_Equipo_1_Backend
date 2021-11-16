<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportDeleted extends Model
{
    use HasFactory;
    protected $table = 'reports_deleted';
    protected $fillable = [
        'id_person',
        'id_report',
        'id_place',
        'id_admin'
    ];
}
