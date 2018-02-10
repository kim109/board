<?php

namespace App\Http\Controllers;

use Auth;
use App\Notice;
use App\Comment;
use App\Attachment;
use Illuminate\Http\Request;

class NoticeController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin')->except(['index', 'list', 'show']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // $articles = Notice::where('open', true);

        // if ($request->has('q')) {
        //     $keword = '%'.$request->input('q').'%';
        //     $articles->where('content', 'like', $keword);

        //     $request->session()->put('q', $request->input('q'));
        // } else {
        //     $request->session()->forget('q');
        // }

        // $list = $articles->orderBy('id', 'desc')
        //                 ->paginate(10);

        // if ($request->has('page')) {
        //     $request->session()->put('page', $request->input('page'));
        // } else {
        //     $request->session()->forget('page');
        // }

        $writable = false;
        if (Auth::check()) {
            $admin = explode(',', env('ADMIN'));
            $admin = array_map('trim', $admin);
            $writable = in_array(Auth::user()->user_id, $admin);
        }

        return view('notices.list', ['writable' => $writable]);
    }

    public function list(Request $request)
    {
        if (!$request->ajax()) {
            return response()->json(['errors' => 'invalid connection'], 406);
        }
        $keyword = $request->input('keyword');

        $articles = Notice::with(['user:id,user_id,name', 'category:id,name'])
                    ->withCount('comments')
                    ->where('open', true)
                    ->when($keyword, function ($query) use ($keyword) {
                        return $query->where('subject', 'like', '%'.$keyword.'%')
                                ->orWhere('content', 'like', '%'.$keyword.'%');
                    })
                    ->orderBy('id', 'desc')
                    ->get();

        return response()->json($articles);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = \App\Category::where('table', 'notices')->get();
        return view('notices.create', ['categories' => $categories]);
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

        $article = new Notice;
        $article->user_id = Auth::id();
        $article->category_id = $request->input('category');
        $article->subject = $request->input('subject');
        $article->content = $request->input('content');
        $article->save();

        if ($request->has('attachments')) {
            $attachments = Attachment::whereIn('id', $request->input('attachments'))->get();
            $attachments->each(function ($attachment) use ($article) {
                $attachment->attach_id = $article->id;
                $attachment->attach_type = 'notices';
                $attachment->save();
            });
        }

        return redirect()->route('notices.index');
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

        $list_url = route('notices.index');

        $writable = false;
        if (Auth::check()) {
            $admin = explode(',', env('ADMIN'));
            $admin = array_map('trim', $admin);
            $writable = in_array(Auth::user()->user_id, $admin) || $article->user_id == Auth::id();
        }

        return view('notices.show', ['article' => $article, 'list' => $list_url, 'writable' => $writable]);
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
        $categories = \App\Category::where('table', 'notices')->get();
        return view('notices.edit', ['article' => $article, 'categories' => $categories]);
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

        $article = Notice::findorFail($id);

        $admin = explode(',', env('ADMIN'));
        $admin = array_map('trim', $admin);
        if (!in_array(Auth::user()->user_id, $admin) && $article->user_id != Auth::id()) {
            return back()->withErrors(['errors' => '수정 권한이 없습니다.']);
        }

        $article->category_id = $request->input('category');
        $article->subject = $request->input('subject');
        $article->content = $request->input('content');
        $article->save();

        if ($request->hasFile('attach')) {
            $attach = $request->attach;
            $path = $request->attach->store('attachments');

            $attachment = new Attachment;
            $attachment->user_id = Auth::id();
            $attachment->attach_id = $article->id;
            $attachment->attach_type = 'notices';
            $attachment->path = $path;
            $attachment->name = $attach->getClientOriginalName();
            $attachment->mime = $attach->getClientMimeType();
            $attachment->size = $attach->getClientSize();
            $attachment->save();
        }

        return redirect()->route('notices.show', ['id' => $article->id]);
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

        $admin = explode(',', env('ADMIN'));
        $admin = array_map('trim', $admin);
        if (!in_array(Auth::user()->user_id, $admin) && $article->user_id != Auth::id()) {
            return response()->json(['errors' => '삭제 권한이 없습니다.'], 403);
        }

        $article->comments()->delete();
        $article->attachments()->delete();
        $article->delete();

        return response()->json(['list' => route('notices.index')]);
    }
}
