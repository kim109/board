<?php

namespace App\Http\Controllers;

use Auth;
use App\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['get']);
    }

    public function get(Request $request, $type, $id)
    {
        if (!$request->ajax()) {
            return response()->json(['errors' => 'invalid connection'], 406);
        }

        $comments = Comment::where([
                        ['commentable_type', $type],
                        ['commentable_id', $id]
                    ])
                    ->with(['user:id,user_id,name', 'children.user:id,user_id,name'])
                    ->get();

        return response()->json(['user'=> Auth::id(), 'comments' => $comments]);
    }

    public function store(Request $request, $type, $id)
    {
        $this->validate($request, [
            'content' => 'required'
        ]);

        $content = $request->input('content');
        $content = strip_tags($content, '<a><strong><p>');
        $content = nl2br($content);

        $comment = new Comment;
        $comment->user_id = Auth::id();
        $comment->commentable_type = $type;
        $comment->commentable_id = $id;
        $comment->content = $content;
        $comment->save();

        return redirect()->back();
    }

    public function update(Request $request, $type, $id, $comment)
    {
        if (!$request->ajax()) {
            return response()->json(['errors' => 'invalid connection'], 406);
        }

        $this->validate($request, [
            'content' => 'required'
        ]);

        $comment = Comment::findorFail($comment);
        if ($comment->user_id != Auth::id()) {
            return response()->json(['error' => 'permission denied'], 403);
        }

        if ($comment->commentable_type != $type || $comment->commentable_id != $id) {
            return response()->json(['error' => 'miss match article id'], 403);
        }

        $content = $request->input('content');
        $content = strip_tags($content, '<a><strong><p>');
        $content = nl2br($content);
        $comment->content = $content;
        $comment->save();

        return response()->json(['result' => 'success']);
    }

    public function destroy(Request $request, $type, $id, $comment)
    {
        if (!$request->ajax()) {
            return response()->json(['errors' => 'invalid connection'], 406);
        }

        $comment = Comment::findorFail($comment);
        if ($comment->user_id != Auth::id()) {
            return response()->json(['error' => 'permission denied'], 403);
        }

        $comment->children()->delete();
        $comment->delete();

        return response()->json(['result' => 'success']);
    }

    public function reply(Request $request, $type, $id, $comment)
    {
        if (!$request->ajax()) {
            return response()->json(['errors' => 'invalid connection'], 406);
        }

        $parent = Comment::findorFail($comment);
        if ($parent->commentable_type != $type || $parent->commentable_id != $id) {
            return response()->json(['error' => 'miss match article id'], 403);
        }

        $content = $request->input('content');
        $content = strip_tags($content, '<a><strong><p>');
        $content = nl2br($content);

        $reply = new Comment;
        $reply->user_id = Auth::id();
        $reply->commentable_type = 'comments';
        $reply->commentable_id = $comment;
        $reply->content = $content;
        $reply->save();

        return response()->json(['result' => 'success']);
    }
}
