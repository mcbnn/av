@if(count($data) != 0)
    <ul>
    @foreach($data as $key => $item)
            <li>{{$item}}</li>
    @endforeach
    </ul>
@endif