<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateParamsRequest;
use Illuminate\Http\Request;
use App\Params;

class ParamsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $list = Params::all();
        return view('params.index', compact('list'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('params.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CreateParamsRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateParamsRequest $request)
    {
        $input = $request->all();
        Params::create($input);
        return redirect('params');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $param = Params::find($id);
        if(!$param)abort(404);
        return view('params.show', compact('param'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $param = Params::find($id);
        if(!$param)abort(404);
        return view('params.edit', compact('param'));
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
        $param = Params::find($id);
        if(!$param)abort(404);
        $param->update($request->all());
        return redirect('params');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $param = Params::find($id);

        if(!$param)abort(404);
        $param->contents()->delete();
        $param->delete();
        return redirect('params');
    }
}
