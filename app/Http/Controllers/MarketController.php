<?php

namespace App\Http\Controllers;

use Auth;
use App\Article;
use App\Comment;
use Illuminate\Http\Request;

class MarketController extends Controller
{
    private $category;

    public function __construct()
    {
        $this->middleware('auth');
        $this->category = 2;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $articles = Article::where('category_id', $this->category)->get();
        foreach ($articles as $article) {
            $article->price = (int)json_decode($article->content)->price;
        }

        return view('market.list', ['articles' => $articles]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('market.create');
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
            'attach' => 'required',
            'price' => 'required|integer|min:1',
            'content' => 'required'
        ]);

        $article = new Article;
        $article->category_id = $this->category;
        $article->user_id = Auth::id();
        $article->subject = $request->input('subject');
        $article->content = json_encode([
            'price' => $request->input('price'),
            'description' => $request->input('content')
        ]);

        $filename = $request->attach->getClientOriginalName();
        $path = $request->attach->storeAs('market', $filename);
        $article->attachs = [$path];

        $article->save();

        return redirect()->action('MarketController@index');
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

        $content = json_decode($article->content);
        $article->description = $content->description;
        $article->price = (int)$content->price;

        $comments = Comment::where('article_id', $article->id)->get();

        return view('market.show', ['article' => $article, 'comments' => $comments]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
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

        return redirect()->action('MarketController@index');
    }

    public function thumbnail($id)
    {
        $article = Article::findorFail($id);
        $path = 'app/'.$article->attachs[0];

        return response()->file(storage_path($path));
    }
}
