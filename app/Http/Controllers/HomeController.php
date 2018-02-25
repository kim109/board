<?php

namespace App\Http\Controllers;

use App\Question;
use App\Column;
use App\Seminar;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function popularity(Request $request)
    {
        if (!$request->ajax()) {
            return response()->json(['errors' => 'invalid connection'], 406);
        }

        $qna = Question::with(['user:id,user_id,name', 'category:id,name'])
                    ->where('open', true)
                    ->orderBy('hits', 'desc')
                    ->limit(4)
                    ->get();
        $qna = $qna->each(function ($item, $key) {
            $item->board = 'qna';
        });

        $columns = Column::with(['user:id,user_id,name', 'category:id,name'])
                    ->where('open', true)
                    ->orderBy('hits', 'desc')
                    ->limit(4)
                    ->get();
        $columns = $columns->each(function ($item, $key) {
            $item->board = 'columns';
        });

        $seminars = Seminar::with(['user:id,user_id,name', 'category:id,name'])
                    ->where('open', true)
                    ->orderBy('hits', 'desc')
                    ->limit(4)
                    ->get();
        $seminars = $seminars->each(function ($item, $key) {
            $item->board = 'seminars';
        });

        $data = $qna->merge($columns)->merge($seminars);
        $data = $data->sortByDesc('created_at')->slice(0, 6);

        return response()->json($data);
    }

    public function list(Request $request)
    {
        if (!$request->ajax()) {
            return response()->json(['errors' => 'invalid connection'], 406);
        }

        $this->validate($request, [
            'mode' => 'required|in:qna,columns,seminars,notices'
        ]);

        $mode = $request->input('mode');

        if ($mode == 'qna') {
            $data = Question::with(['user:id,user_id,name', 'category:id,name'])
                    ->withCount('comments')
                    ->where('open', true)
                    ->orderBy('id', 'desc')
                    ->limit(10)
                    ->get();
        } elseif ($mode == 'columns') {
            $data = Column::with(['user:id,user_id,name', 'category:id,name'])
                    ->withCount('comments')
                    ->where('open', true)
                    ->orderBy('id', 'desc')
                    ->limit(10)
                    ->get();
        } elseif ($mode == 'seminars') {
            $data = Seminar::with(['user:id,user_id,name', 'category:id,name'])
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
