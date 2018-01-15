<?php

namespace App\Http\Controllers;

use Auth;
use App\Seminar;
use App\Comment;
use App\Attachment;
use Illuminate\Http\Request;

class SeminarController extends Controller
{
    public function index(Request $request)
    {
        $writable = Auth::check();
        return view('seminars.list', ['writable' => $writable]);
    }

    public function list(Request $request)
    {
        if (!$request->ajax()) {
            return response()->json(['errors' => 'invalid connection'], 406);
        }
        $keyword = $request->input('keyword');
 
        $articles = Seminar::with(['user:id,user_id,name', 'category:id,name'])
                    ->withCount('comments')
                    ->where('open', true)
                    ->when($keyword, function ($query) use ($keyword) {
                        return $query->where('subject', 'like', '%'.$keyword.'%')
                                ->orWhere('content', 'like', '%'.$keyword.'%');
                    })
                    ->orderBy('id', 'desc')
                    ->get();

        return response()->json($articles);
    }

    public function create()
    {
        $categories = \App\Category::where('table', 'seminars')->get();
        return view('seminars.create', ['categories' => $categories]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'category' => 'required|integer',
            'subject' => 'required',
            'content' => 'required',
            'thumbnail_id' => 'required|integer'
        ]);

        $article = new Seminar;
        $article->user_id = Auth::id();
        $article->category_id = $request->input('category');
        $article->subject = $request->input('subject');
        $article->content = $request->input('content');
        $article->thumbnail_id = $request->input('thumbnail_id');
        $article->save();

        if ($request->has('attachments')) {
            $attachments = Attachment::whereIn('id', $request->input('attachments'))->get();
            $attachments->each(function ($attachment) use ($article) {
                $attachment->attach_id = $article->id;
                $attachment->attach_type = 'seminars';
                $attachment->save();
            });
        }

        return redirect()->route('seminars.index');
    }

    public function show($id, Request $request)
    {
        $article = Seminar::findorFail($id);
        if ($article->user_id != Auth::id()) {
            $article->hits += 1;
            $article->save();
        }

        $list_url = route('seminars.index');

        $writable = false;
        if (Auth::check()) {
            $admin = explode(',', env('ADMIN'));
            $admin = array_map('trim', $admin);
            $writable = in_array(Auth::user()->user_id, $admin) || $article->user_id == Auth::id();
        }

        return view('seminars.show', ['article' => $article, 'list' => $list_url, 'writable' => $writable]);
    }
}
