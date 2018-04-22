@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Обновить параметры:</div>
                    <div class="card-body">
                        {!! Form::model($param, ['method' => 'PATCH', 'action' => ['ParamsController@update', $param->id]]) !!}
                        @include('params.form', ['submit_text' => 'Обновить'])
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop


