<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportsReview extends Model
{
    use HasFactory;
    protected $table = 'reports_reviews';
    protected $fillable = [
        'id_person',
        'id_report'
    ];
}
