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
        $seminars = \App\Seminar::with(['user:id,user_id,name', 'category:id,name'])
                    ->withCount('comments')
                    ->where('open', true)
                    ->orderBy('id', 'desc')
                    ->limit(5)
                    ->get();
        $seminars = $seminars->each(function ($item, $key) {
            $item->board = 'seminars';
        });

        $notices = \App\Notice::with(['user:id,user_id,name', 'category:id,name'])
                    ->withCount('comments')
                    ->where('open', true)
                    ->orderBy('id', 'desc')
                    ->limit(5)
                    ->get();
        $notices = $notices->each(function ($item, $key) {
            $item->board = 'notices';
        });

        $data = $seminars->merge($notices);
        $data = $data->sortByDesc('created_at')->slice(0, 5);

        return response()->json($data);
    }
}
