<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{

    protected $connection = 'integrative_project';
    protected $table = 'report';
    protected $primaryKey = "report_id";
    public $timestamps = false;
}
