<?php

namespace App\Http\Controllers\General;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ImageTinyMceController extends Controller
{
    public function imageUpload(Request $request)
    {
        $file = $request->file('file');
        $path = $file->store('images', 'public');

        return response()->json(['location' => asset("storage/$path")]);
    }
}
