<?php

namespace App\Http\Controllers;

use Auth;
use App\Market;
use App\Comment;
use App\Attachment;
use Illuminate\Http\Request;

class MarketController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $articles = Market::where('open', true)
                    ->orderBy('id', 'desc')
                    ->paginate(10);

        return view('market.list', ['articles' => $articles]);
    }

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
            'price' => 'required|integer|min:1',
            'content' => 'required',
            'attachments' => 'required'
        ]);

        $market = new Market;
        $market->status = '판매중';
        $market->user_id = Auth::id();
        $market->subject = $request->input('subject');
        $market->price = $request->input('price');
        $market->content = $request->input('content');
        $market->thumbnail_id = $request->input('attachments')[0];
        $market->save();

        if ($request->has('attachments')) {
            $attachments = Attachment::whereIn('id', $request->input('attachments'))->get();
            $attachments->each(function ($attachment) use ($market) {
                $attachment->attach_id = $market->id;
                $attachment->attach_type = 'market';
                $attachment->save();
            });
        }

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
        $article = Market::findorFail($id);
        if ($article->user_id != Auth::id()) {
            $article->hits += 1;
            $article->save();
        }

        return view('market.show', ['article' => $article]);
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
        $content = json_decode($article->content);
        $article->description = $content->description;
        $article->price = (int)$content->price;

        return view('market.edit', ['article' => $article]);
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
            'attach' => 'required',
            'price' => 'required|integer|min:1',
            'content' => 'required'
        ]);

        $article = Article::findorFail($id);
        $article->subject = $request->input('subject');
        $article->content = json_encode([
            'price' => $request->input('price'),
            'description' => $request->input('content')
        ]);
        $article->save();

        return redirect('/market/'.$id);
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

        $article = Market::findorFail($id);
        if ($article->user_id == Auth::id()) {
            $article->delete();
        }

        $article->comments()->delete();
        $article->delete();

        return response()->json(['list' => '/market']);
    }
}
