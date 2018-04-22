@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Параметр {{$param->id}}:</div>
                    <div class="card-body">
                        <ul>
                            <li>name: {{$param->name}}</li>
                            <li>value: {{$param->value}}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop


