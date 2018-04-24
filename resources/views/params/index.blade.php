@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Cписок параметров:</div>
                    <div class="card-body">
                        <div class="mt-1 mb-1">
                            <a class="btn btn-primary btn-sm" href="{{route('params.create')}}">Добавить</a>
                        </div>
                        @if($list)
                            <ul class="list-group">
                                @foreach($list as $item)
                                    <li class="list-group-item">
                                        <a href="{{url('params', ['id' => $item->id])}}">
                                            {{$item->id}} {{$item->value}}
                                        </a>
                                        <a href="{{route('params.edit', ['id' => $item->id])}}">Edit</a>
                                        <a href="{{route('parser-url', ['id' => $item->id])}}">Parser All</a>
                                        {{ Form::open([ 'method'  => 'delete', 'route' => [ 'params.destroy', $item->id ]])}}
                                        {{ Form::submit('Delete', ['class' => 'btn btn-danger']) }}
                                        {{ Form::close() }}
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop


