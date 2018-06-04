@if(count($data) != 0)
    <ul>
    @foreach($data as $key => $item)
            <li>{{$item['text']}}</li>
    @endforeach
    </ul>
@endif