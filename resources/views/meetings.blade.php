<!-- View stored in resources/views/meetings.blade.php -->

<html>
<body>
{{--<h1>Hello, {{ $meetings }}</h1>--}}
<h1>Meeting List</h1>
<h2>Meetings in: {{$city . ', ' . $state}}</h2>
<h2>Nearest to: {{ucwords($address)}}</h2>
@foreach($meetings as $k => $meeting)
    <h3>{{$meeting['meeting_name']}}</h3>
    Meeting Type:  {{$meeting['meeting_type']}} <br />
    Details: {{$meeting['details']}}<br />
    Language: {{$meeting['language']}}<br />
    Location: {{$meeting['raw_address']}}<br />
    {{round($meeting['distance'], 2)}} Miles away <br />

    @foreach ($meeting['time'] as $key=>$timeInfo)
        @if (strtolower($key) != "id")
        {{ucwords($key) . ": " .  ucwords($timeInfo)}} <br />
        @endif
    @endforeach
@endforeach


</body>
</html>