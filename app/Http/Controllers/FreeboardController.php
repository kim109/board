<?php

namespace App\Http\Controllers;

use Auth;
use App\Freeboard;
use App\FreeboardComment;
use App\Attachment;
use Illuminate\Http\Request;

class FreeboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // if (isset($category)) {
        //     $articles = FreeBoard::where('category', $category)->get();
        // }
        $articles = Freeboard::orderBy('id', 'desc')->get();

        return view('freeboard.list', ['articles' => $articles]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('freeboard.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'category' => 'required|in:일상,유머,치과경영,의료윤리,의료사고',
            'subject' => 'required',
            'content' => 'required'
        ]);

        $article = new Freeboard;
        $article->user_id = Auth::id();
        $article->category = $request->input('category');
        $article->subject = $request->input('subject');
        $article->content = $request->input('content');
        $article->save();

        if ($request->hasFile('attach')) {
            $attach = $request->attach;
            $path = $request->attach->store('freeboard');

            $attachment = new Attachment;
            $attachment->user_id = Auth::id();
            $attachment->attach_id = $article->id;
            $attachment->attach_type = 'App\\Freeboard';
            $attachment->path = $path;
            $attachment->name = $attach->getClientOriginalName();
            $attachment->mime = $attach->getClientMimeType();
            $attachment->size = $attach->getClientSize();
            $attachment->save();
        }

        return redirect()->action('FreeboardController@index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $article = Freeboard::findorFail($id);
        if ($article->user_id != Auth::id()) {
            $article->hits += 1;
            $article->save();
        }

        $comments = FreeboardComment::where('freeboard_id', $article->id)->get();

        return view('freeboard.show', ['article' => $article, 'comments' => $comments]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $article = Freeboard::findorFail($id);

        return view('freeboard.edit', ['article' => $article]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'category' => 'required|in:일상,유머,치과경영,의료윤리,의료사고',
            'subject' => 'required',
            'content' => 'required'
        ]);

        $article = Freeboard::findorFail($id);
        if ($article->user_id == Auth::id()) {
            $article->category = $request->input('category');
            $article->subject = $request->input('subject');
            $article->content = $request->input('content');
            $article->save();

            if ($request->hasFile('attach')) {
                $attach = $request->attach;
                $path = $request->attach->store('freeboard');

                $attachment = new Attachment;
                $attachment->user_id = Auth::id();
                $attachment->attach_id = $article->id;
                $attachment->attach_type = 'App\\Freeboard';
                $attachment->path = $path;
                $attachment->name = $attach->getClientOriginalName();
                $attachment->mime = $attach->getClientMimeType();
                $attachment->size = $attach->getClientSize();
                $attachment->save();
            }
        }

        return redirect('/freeboard/'.$id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $article = Freeboard::findorFail($id);
        if ($article->user_id == Auth::id()) {
            $article->delete();
        }

        return redirect()->action('FreeboardController@index');
    }

    // 댓글 작성
    public function storeComment(Request $request, $article)
    {
        $this->validate($request, [
            'content' => 'required'
        ]);

        $article = Freeboard::findorFail($article);
        $content = nl2br($request->input('content'));
        $content = strip_tags($content, '<a><strong><br><p>');

        $comment = new FreeboardComment;
        $comment->user_id = Auth::id();
        $comment->freeboard_id = $article->id;
        $comment->content = $content;
        $comment->save();

        return redirect()->back();
    }

    // 댓글 삭제
    public function destroyeComment(Request $request, $article, $comment)
    {
        if (!$request->ajax()) {
            return response()->json(['errors' => 'invalid connection'], 406);
        }

        $comment = FreeboardComment::findorFail($comment);
        if ($comment->user_id == Auth::id()) {
            $comment->delete();
        }

        return response()->json(['result' => 'success']);
    }
}
