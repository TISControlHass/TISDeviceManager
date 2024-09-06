<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HandOverController extends Controller
{
    public function index()
    {
        return view('handover');
    }
}
