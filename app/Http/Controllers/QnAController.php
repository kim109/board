<?php

namespace App\Http\Controllers;

use Auth;
use App\Question;
use App\Answer;
use App\Attachment;
use Illuminate\Http\Request;

class QnAController extends Controller
{
    // 카테고리 정보
    public function category(Request $request)
    {
        if (!$request->ajax()) {
            return response()->json(['errors' => 'invalid connection'], 406);
        }

        $categories = \App\Category::where('table', 'questions')->get(['id', 'name']);

        return response()->json($categories);
    }

    // 인기글 목록
    public function popularity(Request $request)
    {
        if (!$request->ajax()) {
            return response()->json(['errors' => 'invalid connection'], 406);
        }

        $articles = Question::with('category:id,name')
                    ->withCount('comments')
                    ->where('open', true)
                    ->orderBy('hits', 'desc')
                    ->limit(5)
                    ->get();

        return response()->json($articles);
    }

    // 글 목록
    public function list(Request $request)
    {
        if (!$request->ajax()) {
            return response()->json(['errors' => 'invalid connection'], 406);
        }
        $category = $request->input('category');
        $keyword = $request->input('keyword');

        $articles = Question::with(['user:id,user_id,name', 'category:id,name'])
                    ->withCount('comments')
                    ->where('open', true)
                    ->when($category, function ($query) use ($category) {
                        return $query->where('category_id', $category);
                    })
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
        $article = Question::findorFail($id);
        if ($article->user_id != Auth::id()) {
            $article->hits += 1;
            $article->save();
        }

        $answers = $article->answers()->get();

        $list_url = route('qna.index');

        $writable = false;
        if (Auth::check()) {
            $admin = explode(',', env('ADMIN'));
            $admin = array_map('trim', $admin);
            $writable = in_array(Auth::user()->user_id, $admin) || $article->user_id == Auth::id();
        }

        return view('qna.show', [
            'article' => $article,
            'answers' => $answers,
            'list' => $list_url,
            'writable' => $writable
        ]);
    }

    // 글 작성 View
    public function create()
    {
        $categories = \App\Category::where('table', 'questions')->get();
        return view('qna.create', ['categories' => $categories]);
    }

    // 질문 등록
    public function store(Request $request)
    {
        $this->validate($request, [
            'category' => 'required|integer',
            'subject' => 'required',
            'content' => 'required'
        ]);

        $article = new Question;
        $article->user_id = Auth::id();
        $article->category_id = $request->input('category');
        $article->subject = $request->input('subject');
        $article->content = $request->input('content');
        $article->save();

        if ($request->has('attachments')) {
            $attachments = Attachment::whereIn('id', $request->input('attachments'))->get();
            $attachments->each(function ($attachment) use ($article) {
                $attachment->attach_id = $article->id;
                $attachment->attach_type = 'qna';
                $attachment->save();
            });
        }

        return redirect()->route('qna.index');
    }

    public function answer($id, Request $request)
    {
        $article = Question::findorFail($id);
        return view('qna.answer', ['article' => $article]);
    }

    public function storeAnswer($id, Request $request)
    {
        $this->validate($request, [
            'content' => 'required'
        ]);

        $article = Question::findorFail($id);

        $answer = new Answer();
        $answer->user_id = Auth::id();
        $answer->question_id = $article->id;
        $answer->content = $request->input('content');
        $answer->save();

        return redirect()->route('qna.show', ['id' => $id]);
    }

    public function edit($id)
    {
        $article = Question::findorFail($id);
        $categories = \App\Category::where('table', 'questions')->get();
        return view('qna.edit', ['article' => $article, 'categories' => $categories]);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'category' => 'required|integer',
            'subject' => 'required',
            'content' => 'required'
        ]);

        $article = Question::findorFail($id);

        $admin = explode(',', env('ADMIN'));
        $admin = array_map('trim', $admin);
        if (!in_array(Auth::user()->user_id, $admin) && $article->user_id != Auth::id()) {
            return back()->withErrors(['errors' => '수정 권한이 없습니다.']);
        }

        $article->category_id = $request->input('category');
        $article->subject = $request->input('subject');
        $article->content = $request->input('content');
        $article->save();

        if ($request->hasFile('attach')) {
            $attach = $request->attach;
            $path = $request->attach->store('attachments');

            $attachment = new Attachment;
            $attachment->user_id = Auth::id();
            $attachment->attach_id = $article->id;
            $attachment->attach_type = 'qna';
            $attachment->path = $path;
            $attachment->name = $attach->getClientOriginalName();
            $attachment->mime = $attach->getClientMimeType();
            $attachment->size = $attach->getClientSize();
            $attachment->save();
        }

        return redirect()->route('qna.show', ['id' => $article->id]);
    }

    public function destroy(Request $request, $id)
    {
        if (!$request->ajax()) {
            return response()->json(['errors' => 'invalid connection'], 406);
        }

        $article = Question::findorFail($id);

        $admin = explode(',', env('ADMIN'));
        $admin = array_map('trim', $admin);
        if (!in_array(Auth::user()->user_id, $admin) && $article->user_id != Auth::id()) {
            return response()->json(['errors' => '삭제 권한이 없습니다.'], 403);
        }

        $article->comments()->delete();
        $article->answers()->delete();
        $article->attachments()->delete();
        $article->delete();

        return response()->json(['list' => route('qna.index')]);
    }
}
