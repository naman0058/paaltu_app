<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if(\Auth::user()->user_type == 'vendor')
        {
             return view('vendor.dashboard.index');
        }
        if(\Auth::user()->user_type == 'admin')
        {
            return view('admin.dashboard.index');
        }
        //return view('home');
    }
}