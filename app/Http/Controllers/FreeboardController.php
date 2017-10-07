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
    public function index(Request $request)
    {
        $notices = Freeboard::where('open', true)
                    ->where('pin', true)
                    ->orderBy('id', 'desc')
                    ->get();

        $articles = Freeboard::where('open', true);

        // if (isset($category)) {
        //     $articles = FreeBoard::where('category', $category)->get();
        // }

        if ($request->has('q')) {
            $keword = '%'.$request->input('q').'%';
            $articles->where('content', 'like', $keword);

            $request->session()->put('q', $request->input('q'));
        } else {
            $request->session()->forget('q');
        }

        $list = $articles->orderBy('id', 'desc')
                        ->paginate(3);

        if ($request->has('page')) {
            $request->session()->put('page', $request->input('page'));
        } else {
            $request->session()->forget('page');
        }

        return view('freeboard.list', ['notices' => $notices, 'articles' => $list]);
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
    public function show(Request $request, $id)
    {
        $article = Freeboard::findorFail($id);
        if ($article->user_id != Auth::id()) {
            $article->hits += 1;
            $article->save();
        }

        $list_url = '/freeboard';
        $param = null;
        if ($request->session()->has('page')) {
            $param['page'] = $request->session()->get('page');
        }
        if ($request->session()->has('q')) {
            $param['q'] = $request->session()->get('q');
        }

        if ($param != null) {
            $list_url .= '?'.http_build_query($param);
        }

        $comments = FreeboardComment::where('freeboard_id', $article->id)->get();

        return view('freeboard.show', ['article' => $article, 'comments' => $comments, 'list' => $list_url]);
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
    public function destroy(Request $request, $id)
    {
        if (!$request->ajax()) {
            return response()->json(['errors' => 'invalid connection'], 406);
        }

        $article = Freeboard::findorFail($id);
        if ($article->user_id == Auth::id()) {
            $article->delete();
        }

        $comments = FreeboardComment::where('freeboard_id', $id);
        $comments->delete();

        return response()->json(['result' => 'success']);
    }

    // 댓글 확인
    public function getComment(Request $request, $article)
    {
        if (!$request->ajax()) {
            return response()->json(['errors' => 'invalid connection'], 406);
        }

        $comments = FreeboardComment::where('freeboard_id', $article)
                        ->where('parent_id', null)
                        ->with(['user:id,name', 'childs.user:id,name'])
                        ->get();

        return response()->json(['user'=> Auth::id(), 'comments' => $comments]);
    }

    // 댓글 작성
    public function storeComment(Request $request, $article)
    {
        $this->validate($request, [
            'content' => 'required'
        ]);

        $article = Freeboard::findorFail($article);
        $content = $request->input('content');
        $content = strip_tags($content, '<a><strong><p>');
        $content = nl2br($content);

        $comment = new FreeboardComment;
        $comment->user_id = Auth::id();
        $comment->freeboard_id = $article->id;
        $comment->content = $content;
        $comment->save();

        return redirect()->back();
    }

    // 댓글 수정
    public function updateComment(Request $request, $article, $comment)
    {
        if (!$request->ajax()) {
            return response()->json(['errors' => 'invalid connection'], 406);
        }

        $this->validate($request, [
            'content' => 'required'
        ]);

        $content = $request->input('content');
        $content = strip_tags($content, '<a><strong><p>');
        $content = nl2br($content);

        $comment = FreeboardComment::findorFail($comment);
        if ($comment->user_id != Auth::id()) {
            return response()->json(['error' => 'permission denied'], 403);
        }

        if ($comment->freeboard_id != $article) {
            return response()->json(['error' => 'miss match article id'], 403);
        }
        $comment->content = $content;
        $comment->save();

        return response()->json(['result' => 'success']);
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

    public function replyComment(Request $request, $article, $comment)
    {
        if (!$request->ajax()) {
            return response()->json(['errors' => 'invalid connection'], 406);
        }

        $parent = FreeboardComment::findorFail($comment);
        if ($parent->freeboard_id != $article) {
            return response()->json(['error' => 'miss match article id'], 403);
        }

        $content = $request->input('content');
        $content = strip_tags($content, '<a><strong><p>');
        $content = nl2br($content);

        $reply = new FreeboardComment;
        $reply->user_id = Auth::id();
        $reply->freeboard_id = $article;
        $reply->parent_id = $comment;
        $reply->content = $content;
        $reply->save();

        return response()->json(['result' => 'success']);
    }
}
