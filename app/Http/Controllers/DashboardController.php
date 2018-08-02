<?php

namespace App\Http\Controllers;

class DashboardController extends Controller
{
    /**
     * Only authenticated users can access dashboard
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show dashboard page
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('dashboard');
    }
}
