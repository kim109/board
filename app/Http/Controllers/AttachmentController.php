<?php

namespace App\Http\Controllers;

use Auth;
use App\Attachment;
use Illuminate\Http\Request;

class AttachmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->only(['store', 'remove']);
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

        if ($request->has('w') || $request->has('h')) {
            list($width, $height) = getimagesize($path);
            $new_width = $request->input('w');
            $new_height = $request->input('h');

            $thumb = imagecreatetruecolor($new_width, $new_height);

            if ($attachment->mime == 'image/jpeg') {
                $source = imagecreatefromjpeg($path);
            } elseif ($attachment->mime == 'image/gif') {
                $source = imagecreatefromgif($path);
            } elseif ($attachment->mime == 'image/png') {
                $source = imagecreatefrompng($path);
            }

            imagecopyresampled($thumb, $source, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
            imagejpeg($thumb, null, 100);
        } else {
            return response()->file($path);
        }
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

        if ($request->has('article_id')) {
            $attachment->attach_id = $request->input('article_id');
        }
        if ($request->has('type')) {
            if ($request->input('type') == 'artwork' && !str_is('image/*', $file->getClientMimeType())) {
                return response()->json([
                  'success' => false,
                  'message' => '이미지 파일만 업로드 가능합니다.'
                ]);
            }
            $attachment->attach_type = $request->input('type');
        }

        $attachment->path = $path;
        $attachment->name = $file->getClientOriginalName();
        $attachment->mime = $file->getClientMimeType();
        $attachment->size = $file->getClientSize();
        $attachment->save();

        $result = [
            'id'   => $attachment->id,
            'name' => $file->getClientOriginalName(),
            'type' => $file->getClientMimeType()
        ];

        // 본문에 들어간 이미지 이면..
        if ($request->input('type') == 'artwork') {
            $result['success'] = true;
            $result['link'] = '/thumbnail/'.$attachment->id;
        }

        return response()->json($result);
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
