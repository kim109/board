<?php

namespace App\Http\Controllers;

use Auth;
use App\Seminar;
use App\Comment;
use App\Attachment;

use App\Insurance;
use App\InsuranceReply;

use Illuminate\Http\Request;

class SummaryController extends Controller
{
    public function list(Request $request)
    {
        if (!$request->ajax()) {
            return response()->json(['errors' => 'invalid connection'], 406);
        }
        
        $articles = Seminar::with(['user:id,user_id,name', 'category:id,name'])
                    ->withCount('comments')
                    ->where('open', true)
                    ->orderBy('id', 'desc')
                    ->get();

        return response()->json($articles);
    }

    public function jisiklist(Request $request)
    {
        if (!$request->ajax()) {
            return response()->json(['errors' => 'invalid connection'], 406);
        }
    
        $articles = Insurance::with(['user:id,user_id,name', 'category:id,name'])
                    ->withCount('comments')
                    ->where('open', true)
                    ->orderBy('id', 'desc')
                    ->get();

        return response()->json($articles);
    }
}
