@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">Cписок параметров:</div>
                    <div class="card-body">
                        <div class="mt-1 mb-1">
                            <a class="btn btn-primary btn-sm" href="{{route('params.create')}}">Добавить</a>
                        </div>
                        @if($list)
                            <table class = "table table-bordered">
                                <thead>
                                    <th>id</th>
                                    <th>name</th>
                                    <th>value</th>
                                    <th>edit</th>
                                    <th>parser ALL</th>
                                    <th>count</th>
                                    <th>cron</th>
                                    <th>update</th>
                                    <th>delete</th>
                                </thead>
                                <tbody>
                                @foreach($list as $item)
                                    <tr>
                                        <td>
                                            <a href="{{url('params', ['id' => $item->id])}}">
                                                {{$item->id}}
                                            </a>
                                        </td>
                                        <td>
                                            {{ Str::limit($item->name, 10) }}
                                        </td>
                                        <td>
                                            <a target="_blank" href="{{$item->value}}">
                                                {{$item->value}}
                                            </a>
                                        </td>
                                        <td>
                                            <a href="{{route('params.edit', ['id' => $item->id])}}">Edit</a>
                                        </td>
                                        <td>
                                            <a href="{{route('parser-url', ['id' => $item->id])}}">Parser All</a>
                                        </td>
                                        <td>
                                            {{$item->count}}
                                        </td>
                                        <td>
                                            {{$item->cron}}
                                        </td>
                                        <td>
                                            {{$item->updated_at->format('d.m.Y H:i:s')}}
                                        </td>
                                        <td>
                                            {{ Form::open([ 'method'  => 'delete', 'route' => [ 'params.destroy', $item->id ]])}}
                                            {{ Form::submit('Delete', ['class' => 'btn btn-danger']) }}
                                            {{ Form::close() }}
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop


