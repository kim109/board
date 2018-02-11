<?php

namespace App\Http\Controllers;

use Auth;
use App\Insurance;
use Illuminate\Http\Request;
use App\InsuranceReply;

class InsuranceController extends Controller
{
    // 글 목록
    public function list(Request $request)
    {
        if (!$request->ajax()) {
            return response()->json(['errors' => 'invalid connection'], 406);
        }
        $keyword = $request->input('keyword');

        $articles = Insurance::with(['user:id,user_id,name', 'category:id,name'])
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

    // 글 보기
    public function show($id, Request $request)
    {
        $article = Insurance::findorFail($id);
        if ($article->user_id != Auth::id()) {
            $article->hits += 1;
            $article->save();
        }

        $replies = $article->replies()->get();

        $list_url = route('insurances.index');

        $writable = false;
        if (Auth::check()) {
            $admin = explode(',', env('ADMIN'));
            $admin = array_map('trim', $admin);
            $writable = in_array(Auth::user()->user_id, $admin) || $article->user_id == Auth::id();
        }

        return view('insurances.show', [
            'article' => $article,
            'replies' => $replies,
            'list' => $list_url,
            'writable' => $writable
        ]);
    }

    // 글 작성 View
    public function create()
    {
        $categories = \App\Category::where('table', 'insurances')->get();
        return view('insurances.create', ['categories' => $categories]);
    }

    // 글 등록
    public function store(Request $request)
    {
        $this->validate($request, [
            'category' => 'required|integer',
            'subject' => 'required',
            'content' => 'required'
        ]);

        $article = new Insurance;
        $article->user_id = Auth::id();
        $article->category_id = $request->input('category');
        $article->subject = $request->input('subject');
        $article->content = $request->input('content');
        $article->save();

        if ($request->has('attachments')) {
            $attachments = Attachment::whereIn('id', $request->input('attachments'))->get();
            $attachments->each(function ($attachment) use ($article) {
                $attachment->attach_id = $article->id;
                $attachment->attach_type = 'insurances';
                $attachment->save();
            });
        }

        return redirect()->route('insurances.index');
    }

    public function reply($id, Request $request)
    {
        $article = Insurance::findorFail($id);
        return view('insurances.reply', ['article' => $article]);
    }

    public function storeReply($id, Request $request)
    {
        $this->validate($request, [
            'content' => 'required'
        ]);

        $article = Insurance::findorFail($id);

        $reply = new InsuranceReply;
        $reply->user_id = Auth::id();
        $reply->insurance_id = $article->id;
        $reply->content = $request->input('content');
        $reply->save();

        return redirect()->route('insurances.show', ['id' => $id]);
    }

    public function destroy(Request $request, $id)
    {
        if (!$request->ajax()) {
            return response()->json(['errors' => 'invalid connection'], 406);
        }

        $article = Insurance::findorFail($id);

        $admin = explode(',', env('ADMIN'));
        $admin = array_map('trim', $admin);
        if (!in_array(Auth::user()->user_id, $admin) && $article->user_id != Auth::id()) {
            return response()->json(['errors' => '삭제 권한이 없습니다.'], 403);
        }

        $article->comments()->delete();
        $article->replies()->delete();
        $article->attachments()->delete();
        $article->delete();

        return response()->json(['list' => route('insurances.index')]);
    }
}
