<?php

namespace App\Http\Controllers;

use Auth;
use App\Support;
use App\Comment;
use App\Attachment;
use Illuminate\Http\Request;

class SupportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $articles = Support::where('open', true);

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

        return view('supports.list', ['articles' => $list]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = \App\Category::where('table', 'supports')->get();
        return view('supports.create', ['categories' => $categories]);
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

        $article = new Support;
        $article->user_id = Auth::id();
        $article->category_id = $request->input('category');
        $article->subject = $request->input('subject');
        $article->question = $request->input('content');
        $article->save();

        if ($request->has('attachments')) {
            $attachments = Attachment::whereIn('id', $request->input('attachments'))->get();
            $attachments->each(function ($attachment) use ($article) {
                $attachment->attach_id = $article->id;
                $attachment->attach_type = 'supports';
                $attachment->save();
            });
        }

        return redirect()->route('supports.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $article = Support::findorFail($id);
        if ($article->user_id != Auth::id()) {
            $article->hits += 1;
            $article->save();
        }

        $list_url = '/supports';
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

        $admin = explode(',', env('ADMIN'));
        $admin = array_map('trim', $admin);
        $answerable = in_array(Auth::user()->user_id, $admin);

        $writable = ($article->user_id == Auth::id());

        return view('supports.show', [
            'article' => $article,
            'list' => $list_url,
            'writable' => $writable,
            'answerable'=>$answerable
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $article = Support::findorFail($id);
        $categories = \App\Category::where('table', 'supports')->get();
        return view('supports.edit', ['article' => $article, 'categories' => $categories]);
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

        $article = Support::findorFail($id);
        $article->category_id = $request->input('category');
        $article->subject = $request->input('subject');
        $article->question = $request->input('content');
        $article->save();

        if ($request->hasFile('attach')) {
            $attach = $request->attach;
            $path = $request->attach->store('attachments');

            $attachment = new Attachment;
            $attachment->user_id = Auth::id();
            $attachment->attach_id = $article->id;
            $attachment->attach_type = 'supports';
            $attachment->path = $path;
            $attachment->name = $attach->getClientOriginalName();
            $attachment->mime = $attach->getClientMimeType();
            $attachment->size = $attach->getClientSize();
            $attachment->save();
        }

        return redirect()->route('supports.show', ['id' => $article->id]);
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

        $article = Support::findorFail($id);
        $article->comments()->delete();
        $article->attachments()->delete();
        $article->delete();

        return response()->json(['list' => '/supports']);
    }

    public function answer(Request $request, $id)
    {
        $this->validate($request, [
            'content' => 'required'
        ]);

        $article = Support::findorFail($id);
        $article->answer = $request->input('content');
        $article->save();

        return redirect()->route('supports.show', ['id' => $article->id]);
    }
}
