<?php

namespace App\Http\Controllers;

use Auth;
use App\Attachment;
use Illuminate\Http\Request;

class AttachmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Request $request)
    {
        if (!$request->hasFile('file')) {
            return response()->json('File not passed', 422);
        }

        $file = $request->file('file');
        $path = $request->file->store('attachments');

        $attachment = new Attachment;
        $attachment->user_id = \Auth::id();
        $attachment->attach_id = null;
        $attachment->attach_type = null;
        $attachment->path = $path;
        $attachment->name = $file->getClientOriginalName();
        $attachment->mime = $file->getClientMimeType();
        $attachment->size = $file->getClientSize();
        $attachment->save();

        return response()->json([
            'id'   => $attachment->id,
            'name' => $file->getClientOriginalName(),
            'type' => $file->getClientMimeType()
        ]);
    }

    public function download(Request $request, $id, $md5)
    {
        $attachment = Attachment::findorFail($id);

        if (md5($attachment->path) != $md5) {
            return '404';
        }

        $path = storage_path('app/'.$attachment->path);

        return response()->download($path, $attachment->name);
    }

    public function thumbnail(Request $request, $id)
    {
        $attachment = Attachment::findorFail($id);
        $path = storage_path('app/'.$attachment->path);

        if (!str_is('image/*', $attachment->mime)) {
            return '404';
        }

        return response()->file($path);
    }

    public function remove(Request $request, $id)
    {
        $attachment = Attachment::findorFail($id);
        if ($attachment->user_id != \Auth::id()) {
            return response()->json(['error' => 'permission denied'], 403);
        }
        $path = storage_path('app/'.$attachment->path);
        $attachment->delete();
        unlink($path);

        return response()->json(['result' => 'success']);
    }
}
