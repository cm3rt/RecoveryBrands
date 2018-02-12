<!-- View stored in resources/views/meetings.blade.php -->

<html>
<body>
{{--<h1>Hello, {{ $meetings }}</h1>--}}
<h1>At least the array was passed. Wasn't it?</h1>
@foreach($meetings as $k => $meeting)
    Meeting Type:  {{$meeting['meeting_type']}} <br />
    Meeting Name: {{$meeting['meeting_name']}} <br />
    Details: {{$meeting['details']}}<br />
    Language: {{$meeting['language']}}<br />
    {{$meeting['raw_address']}}<br />
    {{$meeting['location']}}<br />

    @foreach ($meeting['time'] as $timeInfo)
        {{ucwords($timeInfo)}} <br />
    @endforeach

@endforeach


</body>
</html>