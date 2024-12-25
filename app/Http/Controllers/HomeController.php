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
        return view('home');
    }
    
    public function score()
    {
        //return an array of scores
        return response()->json([
            'score' => 100,
            'swipedRightCount' => 60,
            'swipedLeftCount' => 40,
        ]);
    }
}
