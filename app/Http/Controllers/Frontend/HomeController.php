<?php

namespace App\Http\Controllers\Frontend;

class HomeController
{
    public function index()
    {
        return view('frontend.home');
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
