<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends ReportController
{
    public function delete($id)
    {
        // send email with menssage
        return $this->deleteReport($id);
    }
    public function saveReport($id)
    {
        // send email with menssage
    }
}
