<?php

namespace App\Http\Controllers;

use App\Notice;
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
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }

    public function summary()
    {
        $data = [
            [
                'subject' => 'teegeg',
                'content' => '내용 내용',
                'hits' => 124
            ]
        ];

        return response()->json($data);
    }
}
