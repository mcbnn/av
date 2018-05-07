@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">

                <div class="card">
                    <div class="card-header">Cписок:</div>
                    <div class="card-body">
                        <a href="{{route('home')}}">
                            Вернуться
                        </a>
                        @if($contents)
                            <table class = "table table-bordered">
                                <thead>
                                    <th>id</th>
                                    <th>key</th>
                                    <th>datetime</th>
                                </thead>
                                <tbody>
                                @foreach($contents as $item)
                                    <tr>
                                        <td>
                                            {{$item->id}}
                                        </td>
                                        <td>
                                            <a target="_blank" href = "{{$item->url_full}}">
                                            {{$item->key}}
                                            </a>
                                        </td>
                                        <td>
                                            {{$item->updated_at->format('d.m.Y H:i:s')}}
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


