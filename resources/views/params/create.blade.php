@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Cоздание параметров:</div>
                    <div class="card-body">
                        {!! Form::open(['url' => 'params']) !!}
                        @include('params.form', ['submit_text' => 'Создать'])
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop


