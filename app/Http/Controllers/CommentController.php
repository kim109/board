<?php

namespace App\Http\Controllers;

use Auth;
use App\Article;
use App\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int $article
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $article)
    {
        $this->validate($request, [
            'content' => 'required'
        ]);

        $article = Article::findorFail($article);
        $content = nl2br($request->input('content'));
        $content = strip_tags($content, '<a><strong><br><p>');

        $comment = new Comment;
        $comment->article_id = $article->id;
        $comment->user_id = Auth::id();
        $comment->content = $content;
        $comment->save();

        return redirect()->back();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int $article
     * @param  int $comment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $article, $comment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $article, $comment)
    {
        if (!$request->ajax()) {
            return response()->json(['errors' => 'invalid connection'], 406);
        }

        $comment = Comment::findorFail($comment);
        $comment->delete();

        return response()->json(['result' => 'success']);
    }
}
