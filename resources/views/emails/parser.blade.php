@if(count($data) != 0)
    <ul>
    @foreach($data as $item)
            <li><a target="_blank" href = "https://avito.ru{{$item->url}}">{{$item->key}}</li>
    @endforeach
    </ul>
@endif