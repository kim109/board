<?php

namespace App\Http\Controllers;

use Auth;
use App\Article;
use App\Comment;
use Illuminate\Http\Request;

class FreeboardController extends Controller
{
    private $category;

    public function __construct()
    {
        $this->middleware('auth');
        $this->category = 1;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $articles = Article::all();

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
            'subject' => 'required',
            'content' => 'required'
        ]);

        $article = new Article;
        $article->category_id = $this->category;
        $article->user_id = Auth::id();
        $article->subject = $request->input('subject');
        $article->content = $request->input('content');
        $article->save();

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
        $article = Article::findorFail($id);
        $article->hits += 1;
        $article->save();

        $comments = Comment::where('article_id', $article->id)->get();

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
        $article = Article::findorFail($id);

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
            'subject' => 'required',
            'content' => 'required'
        ]);

        $article = Article::findorFail($id);
        $article->subject = $request->input('subject');
        $article->content = $request->input('content');
        $article->save();

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
        $article = Article::findorFail($id);
        $article->delete();

        return redirect()->action('FreeboardController@index');
    }
}
