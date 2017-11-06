<?php

namespace App\Http\Controllers;

use Auth;
use App\Notice;
use App\Comment;
use App\Attachment;
use Illuminate\Http\Request;

class NoticeController extends Controller
{
    public $admin = ['kim109'];

    public function __construct()
    {
        $this->middleware('admin')->except(['index','show']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $articles = Notice::where('open', true);

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

        $admin = explode(',', env('ADMIN'));
        $admin = array_map('trim', $admin);
        $writable = in_array(Auth::user()->user_id, $admin);

        return view('notice.list', ['articles' => $list, 'writable' => $writable]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('notice.create');
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

        $article = new Notice;
        $article->user_id = Auth::id();
        $article->subject = $request->input('subject');
        $article->content = $request->input('content');
        $article->save();

        if ($request->has('attachments')) {
            $attachments = Attachment::whereIn('id', $request->input('attachments'))->get();
            $attachments->each(function ($attachment) use ($article) {
                $attachment->attach_id = $article->id;
                $attachment->attach_type = 'notice';
                $attachment->save();
            });
        }

        return redirect()->route('notice.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {
        $article = Notice::findorFail($id);
        if ($article->user_id != Auth::id()) {
            $article->hits += 1;
            $article->save();
        }

        $list_url = '/notice';
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

        return view('notice.show', ['article' => $article, 'list' => $list_url]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $article = Notice::findorFail($id);

        if ($article->user_id != Auth::id()) {
            return redirect()->route('notice.show', ['id' => $article->id]);
        }

        return view('notice.edit', ['article' => $article]);
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

        $article = Notice::findorFail($id);
        if ($article->user_id == Auth::id()) {
            $article->subject = $request->input('subject');
            $article->content = $request->input('content');
            $article->save();

            if ($request->hasFile('attach')) {
                $attach = $request->attach;
                $path = $request->attach->store('freeboard');

                $attachment = new Attachment;
                $attachment->user_id = Auth::id();
                $attachment->attach_id = $article->id;
                $attachment->attach_type = 'notice';
                $attachment->path = $path;
                $attachment->name = $attach->getClientOriginalName();
                $attachment->mime = $attach->getClientMimeType();
                $attachment->size = $attach->getClientSize();
                $attachment->save();
            }
        }

        return redirect()->route('notice.show', ['id' => $article->id]);
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

        $article = Notice::findorFail($id);
        if ($article->user_id == Auth::id()) {
            $article->delete();
        }

        $article->comments()->delete();
        $article->attachments()->delete();
        $article->delete();

        return response()->json(['list' => '/notice']);
    }
}
