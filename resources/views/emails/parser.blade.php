@if(count($data) != 0)
    <ul>
    @foreach($data as $key => $item)
            <li><a target="_blank" href = "https://avito.ru{{$item}}">{{$key}}</li>
    @endforeach
    </ul>
@endif