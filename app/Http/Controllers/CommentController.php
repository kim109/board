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

        $comment = new Comment;
        $comment->article_id = $article->id;
        $comment->user_id = Auth::id();
        $comment->content = $request->input('content');
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
    public function destroy($comment)
    {
        //
    }
}
