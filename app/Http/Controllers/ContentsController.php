<?php

namespace App\Http\Controllers;

use App\Contents;
use Illuminate\Http\Request;

class ContentsController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  int  $param_id
     * @return \Illuminate\Http\Response
     */
    public function index($param_id)
    {
        $contents = Contents::where('param_id', $param_id)->limit(20)->orderBy('id', 'desc')->get();
        if(!$contents)abort(404);
        return view('contents.index', compact('contents'));
    }
}
