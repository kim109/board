<?php

namespace App\Http\Controllers;

use App\Attachment;
use Illuminate\Http\Request;

class AttachmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
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

        if (!str_is('image/*', $attachment->mime) ) {
            return '404';
        }

        return response()->file($path);
    }
}
