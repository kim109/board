<?php

namespace App\Http\Controllers;

use Auth;
use App\Freeboard;
use App\Comment;
use App\Attachment;
use Illuminate\Http\Request;

class FreeboardController extends Controller
{
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
                        ->paginate(10);

        if ($request->has('page')) {
            $request->session()->put('page', $request->input('page'));
        } else {
            $request->session()->forget('page');
        }

        return view('freeboards.list', ['notices' => $notices, 'articles' => $list]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = \App\Category::where('table', 'freeboards')->get();
        return view('freeboards.create', ['categories' => $categories]);
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
            'category' => 'required|integer',
            'subject' => 'required',
            'content' => 'required'
        ]);

        $article = new Freeboard;
        $article->user_id = Auth::id();
        $article->category_id = $request->input('category');
        $article->subject = $request->input('subject');
        $article->content = $request->input('content');
        $article->save();

        if ($request->has('attachments')) {
            $attachments = Attachment::whereIn('id', $request->input('attachments'))->get();
            $attachments->each(function ($attachment) use ($article) {
                $attachment->attach_id = $article->id;
                $attachment->attach_type = 'freeboards';
                $attachment->save();
            });
        }

        return redirect()->route('freeboards.index');
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

        $list_url = '/freeboards';
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

        $comments = $article->comments;

        return view('freeboards.show', ['article' => $article, 'list' => $list_url]);
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
        $categories = \App\Category::where('table', 'freeboards')->get();
        return view('freeboards.edit', ['article' => $article, 'categories' => $categories]);
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
            'category' => 'required|integer',
            'subject' => 'required',
            'content' => 'required'
        ]);

        $article = Freeboard::findorFail($id);
        if ($article->user_id == Auth::id()) {
            $article->category_id = $request->input('category');
            $article->subject = $request->input('subject');
            $article->content = $request->input('content');
            $article->save();

            if ($request->hasFile('attach')) {
                $attach = $request->attach;
                $path = $request->attach->store('freeboard');

                $attachment = new Attachment;
                $attachment->user_id = Auth::id();
                $attachment->attach_id = $article->id;
                $attachment->attach_type = 'freeboards';
                $attachment->path = $path;
                $attachment->name = $attach->getClientOriginalName();
                $attachment->mime = $attach->getClientMimeType();
                $attachment->size = $attach->getClientSize();
                $attachment->save();
            }
        }

        return redirect()->route('freeboards.show', ['id' => $article->id]);
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

        $article->comments()->delete();
        $article->attachments()->delete();
        $article->delete();

        return response()->json(['list' => '/market']);
    }
}
