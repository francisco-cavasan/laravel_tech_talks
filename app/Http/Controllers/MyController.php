<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MyController extends Controller
{
    public function index(Request $request)
    {
        return response()->json([
            'message' => 'Hello World!',
            'request' => $request->all(),
        ]);

    }
}
