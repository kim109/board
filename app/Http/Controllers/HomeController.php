<?php

namespace App\Http\Controllers;

use App\Notice;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function summary(Request $request)
    {
        if (!$request->ajax()) {
            return response()->json(['errors' => 'invalid connection'], 406);
        }

        $insurances = \App\Insurance::with(['user:id,user_id,name', 'category:id,name'])
                    ->withCount('comments')
                    ->where('open', true)
                    ->orderBy('id', 'desc')
                    ->limit(8)
                    ->get();
        $insurances = $insurances->each(function ($item, $key) {
            $item->board = 'insurances';
        });

        $seminars = \App\Seminar::with(['user:id,user_id,name', 'category:id,name'])
                    ->withCount('comments')
                    ->where('open', true)
                    ->orderBy('id', 'desc')
                    ->limit(8)
                    ->get();
        $seminars = $seminars->each(function ($item, $key) {
            $item->board = 'seminars';
        });

        $notices = \App\Notice::with(['user:id,user_id,name', 'category:id,name'])
                    ->withCount('comments')
                    ->where('open', true)
                    ->orderBy('id', 'desc')
                    ->limit(8)
                    ->get();
        $notices = $notices->each(function ($item, $key) {
            $item->board = 'notices';
        });

        $data = $insurances->merge($seminars)->merge($notices);
        $data = $data->sortByDesc('created_at')->slice(0, 9);

        return response()->json($data);
    }

    public function list(Request $request)
    {
        if (!$request->ajax()) {
            return response()->json(['errors' => 'invalid connection'], 406);
        }

        $this->validate($request, [
            'mode' => 'required|in:qna,seminars,notices'
        ]);

        $mode = $request->input('mode');

        if ($mode == 'qna') {
            $data = \App\Question::with(['user:id,user_id,name', 'category:id,name'])
                    ->withCount('comments')
                    ->where('open', true)
                    ->orderBy('id', 'desc')
                    ->limit(10)
                    ->get();
        } elseif ($mode == 'seminars') {
            $data = \App\Seminar::with(['user:id,user_id,name', 'category:id,name'])
                    ->withCount('comments')
                    ->where('open', true)
                    ->orderBy('id', 'desc')
                    ->limit(10)
                    ->get();
        } elseif ($mode == 'notices') {
            $data = \App\Notice::with(['user:id,user_id,name', 'category:id,name'])
                    ->withCount('comments')
                    ->where('open', true)
                    ->orderBy('id', 'desc')
                    ->limit(10)
                    ->get();
        }

        return response()->json($data);
    }
}
